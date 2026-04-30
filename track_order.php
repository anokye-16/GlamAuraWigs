<?php
session_start();
header("Content-Type: application/json");

include 'db.php';

$input = $_GET['order_id'] ?? '';
$email = $_GET['email'] ?? '';

// If user is logged in, we can use their session email if none provided
if(!$email && isset($_SESSION['user_email'])){
    $email = $_SESSION['user_email'];
}

if(!$input){
    echo json_encode(["status" => "error", "message" => "Please enter an Order ID or Tracking Number"]);
    exit;
}

$order = null;
$order_id = 0;

// CHECK IF INPUT IS A TRACKING NUMBER (e.g. GLAM-XXXXXX)
if(strpos($input, 'GLAM-') === 0){
    $stmt = $conn->prepare("SELECT order_id FROM deliveries WHERE tracking_number = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $res = $stmt->get_result();
    if($row = $res->fetch_assoc()){
        $order_id = $row['order_id'];
    } else {
        echo json_encode(["status" => "error", "message" => "Tracking number not found"]);
        exit;
    }
} else {
    $order_id = (int)$input;
}

// FETCH ORDER (Security check: must match email OR belong to logged-in user)
$sql = "SELECT * FROM orders WHERE id = ?";
if($email){
    $sql .= " AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $order_id, $email);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
}

$stmt->execute();
$orderResult = $stmt->get_result();
$order = $orderResult->fetch_assoc();

if(!$order){
    echo json_encode(["status" => "error", "message" => "Order not found or access denied"]);
    exit;
}

/* GET DELIVERY DETAILS */
$deliveryStmt = $conn->prepare("SELECT * FROM deliveries WHERE order_id = ?");
$deliveryStmt->bind_param("i", $order_id);
$deliveryStmt->execute();
$delivery = $deliveryStmt->get_result()->fetch_assoc();

/* GET ITEMS */
$itemStmt = $conn->prepare("
    SELECT oi.*, p.name as product_name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();
$items = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "order" => $order,
    "delivery" => $delivery,
    "items" => $items
]);
?>
