<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$stmt = $conn->prepare("
    SELECT o.id, o.full_name, o.email, o.total, o.status, o.created_at, 
           p.status as payment_status, p.method as payment_method,
           d.tracking_number
    FROM orders o
    LEFT JOIN payments p ON o.id = p.order_id
    LEFT JOIN deliveries d ON o.id = d.order_id
    GROUP BY o.id
    ORDER BY o.created_at DESC
");

$stmt->execute();
$result = $stmt->get_result();
$orders = [];

while($row = $result->fetch_assoc()){
    $orders[] = $row;
}

echo json_encode(['status' => 'success', 'orders' => $orders]);
?>
