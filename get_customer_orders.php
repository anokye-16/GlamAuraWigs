<?php
session_start();
include 'db.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer'){
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$customer_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT o.id, o.total, o.status, o.created_at, d.tracking_number 
    FROM orders o
    LEFT JOIN deliveries d ON o.id = d.order_id
    WHERE o.customer_id = ? 
    GROUP BY o.id
    ORDER BY o.created_at DESC
");

$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while($row = $result->fetch_assoc()){
    $orders[] = $row;
}

echo json_encode([
    "status" => "success",
    "orders" => $orders
]);

$conn->close();
?>
