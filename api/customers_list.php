<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$stmt = $conn->prepare("
    SELECT c.id, c.full_name, c.email, c.phone, c.shipping_address, c.city, c.country, c.created_at,
           COUNT(o.id) as order_count, SUM(o.total) as total_spend
    FROM customers c
    LEFT JOIN orders o ON c.id = o.customer_id
    GROUP BY c.id
    ORDER BY c.created_at DESC
");

$stmt->execute();
$result = $stmt->get_result();
$customers = [];

while($row = $result->fetch_assoc()){
    $row['total_spend'] = $row['total_spend'] ?? 0;
    $customers[] = $row;
}

echo json_encode(['status' => 'success', 'customers' => $customers]);
?>
