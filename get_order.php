<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost", "root", "", "glamaura");

if ($conn->connect_error) {
    echo json_encode(["status" => "error"]);
    exit;
}

$order_id = $_GET['order_id'] ?? 0;

/* GET ORDER */
$orderStmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$orderStmt->bind_param("i", $order_id);
$orderStmt->execute();

$orderResult = $orderStmt->get_result();
$order = $orderResult->fetch_assoc();

if (!$order) {
    echo json_encode(["status" => "error"]);
    exit;
}

/* GET ITEMS */
$itemStmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
$itemStmt->bind_param("i", $order_id);
$itemStmt->execute();

$items = $itemStmt->get_result()->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "order" => $order,
    "items" => $items
]);

$conn->close();
