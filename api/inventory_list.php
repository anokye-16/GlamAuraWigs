<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$stmt = $conn->prepare("
    SELECT p.id, p.name as product_name, i.id as inventory_id, i.stock_quantity, i.last_updated
    FROM products p
    LEFT JOIN inventory i ON p.id = i.product_id
    ORDER BY i.stock_quantity ASC
");

$stmt->execute();
$result = $stmt->get_result();
$inventory = [];

while($row = $result->fetch_assoc()){
    // if a product has no inventory record yet, assume 0
    $row['stock_quantity'] = $row['stock_quantity'] ?? 0;
    $inventory[] = $row;
}

echo json_encode(['status' => 'success', 'inventory' => $inventory]);
?>
