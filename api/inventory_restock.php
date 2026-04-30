<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

$product_id = (int)($data['product_id'] ?? 0);
$add_qty = (int)($data['add_qty'] ?? 0);

if(!$product_id || $add_qty <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

// Check if inventory record exists
$check = $conn->query("SELECT id FROM inventory WHERE product_id = $product_id");

if($check->num_rows > 0) {
    // Update existing
    $stmt = $conn->prepare("UPDATE inventory SET stock_quantity = stock_quantity + ? WHERE product_id = ?");
    $stmt->bind_param("ii", $add_qty, $product_id);
} else {
    // Insert new
    $stmt = $conn->prepare("INSERT INTO inventory (product_id, stock_quantity) VALUES (?, ?)");
    $stmt->bind_param("ii", $product_id, $add_qty);
}

if($stmt->execute()){
    echo json_encode(['status' => 'success', 'message' => 'Stock updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>
