<?php
session_start();
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "glamaura";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

$cart = $data['cart'] ?? [];
$total = $data['total'] ?? 0;
$vat = $data['vat'] ?? 0;
$shipping = $data['shipping'] ?? 0;

$customer = $data['customer'] ?? [];
$full_name = $customer['name'] ?? '';
$email = $customer['email'] ?? '';
$phone = $customer['phone'] ?? '';
$address = $customer['address'] ?? '';
$city = $customer['city'] ?? '';
$country = $customer['country'] ?? '';

$payment_method = $data['payment_method'] ?? 'momo';

if(empty($cart) || !$full_name || !$email || !$address){
    echo json_encode(["status"=>"error","message"=>"Missing required fields"]);
    exit;
}

// Get customer_id if logged in
$customer_id = isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'customer' ? $_SESSION['user_id'] : NULL;

$order_status = ($payment_method === 'paystack') ? 'Processing' : 'Pending';

/* INSERT ORDER WITH FULL CUSTOMER DETAILS */
$stmt = $conn->prepare("
    INSERT INTO orders (customer_id, full_name, email, phone, shipping_address, city, country, total, vat, shipping, status, payment_method)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "issssssdddss",
    $customer_id,
    $full_name,
    $email,
    $phone,
    $address,
    $city,
    $country,
    $total,
    $vat,
    $shipping,
    $order_status,
    $payment_method
);

if(!$stmt->execute()){
    echo json_encode(["status"=>"error","message"=>"Order insert failed: ".$stmt->error]);
    exit;
}

$order_id = $stmt->insert_id;

/* INSERT ORDER ITEMS */
$itemStmt = $conn->prepare("
    INSERT INTO order_items (order_id, product_id, quantity, quality, price)
    VALUES (?, ?, ?, ?, ?)
");

foreach($cart as $item){
    $product_id = $item['product_id'];
    $qty = $item['qty'];
    $quality = $item['quality'];
    $price = $item['basePrice'];

    $itemStmt->bind_param(
        "iiisd",
        $order_id,
        $product_id,
        $qty,
        $quality,
        $price
    );
    if(!$itemStmt->execute()){
        echo json_encode(["status"=>"error","message"=>"Item insert failed: ".$itemStmt->error]);
        exit;
    }
}

/* INSERT PAYMENT RECORD */
$payment_status = ($payment_method === 'paystack') ? 'Paid' : 'Pending';
$payStmt = $conn->prepare("INSERT INTO payments (order_id, method, amount, status) VALUES (?, ?, ?, ?)");
$payStmt->bind_param("isds", $order_id, $payment_method, $total, $payment_status);
$payStmt->execute();

/* GENERATE TRACKING NUMBER */
$tracking_number = 'GLAM-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));

/* INSERT DELIVERY RECORD */
$deliveryStmt = $conn->prepare("INSERT INTO deliveries (order_id, status, estimated_date, tracking_number) VALUES (?, 'Pending', DATE_ADD(CURDATE(), INTERVAL 5 DAY), ?)");
$deliveryStmt->bind_param("is", $order_id, $tracking_number);
$deliveryStmt->execute();

/* REDUCE INVENTORY */
$invStmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity - ? WHERE product_id = ?");
foreach($cart as $item){
    $qty = $item['qty'];
    $product_id = $item['product_id'];
    $invStmt->bind_param("ii", $qty, $product_id);
    $invStmt->execute();
}

echo json_encode([
    "status"=>"success",
    "order_id"=>$order_id,
    "tracking_number"=>$tracking_number
]);

$conn->close();
?>
