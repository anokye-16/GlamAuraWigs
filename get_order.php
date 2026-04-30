<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "glamaura");

if ($conn->connect_error) {
    echo json_encode(["status" => "error"]);
    exit;
}

$order_id = $_GET['order_id'] ?? 0;

/* GET ORDER WITH TRACKING ID */
$orderStmt = $conn->prepare("
    SELECT o.*, d.tracking_number 
    FROM orders o 
    LEFT JOIN deliveries d ON o.id = d.order_id 
    WHERE o.id=?
");
$orderStmt->bind_param("i", $order_id);
$orderStmt->execute();

$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    echo json_encode(["status" => "error"]);
    exit;
}

/* GET ITEMS WITH PRODUCT NAMES */
$itemStmt = $conn->prepare("
    SELECT oi.*, p.name as product_name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id=?
");
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();

$items = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "order" => $order,
    "items" => $items
]);

$conn->close();
?>
