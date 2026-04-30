<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$order_id = $data['order_id'] ?? 0;
$status = $data['status'] ?? 'Paid';

if(!$order_id){
    echo json_encode(["status" => "error", "message" => "Invalid Order ID"]);
    exit;
}

// Update payment status
$stmt = $conn->prepare("UPDATE payments SET status = ? WHERE order_id = ?");
$stmt->bind_param("si", $status, $order_id);

if($stmt->execute()){
    echo json_encode(["status" => "success", "message" => "Payment status updated to $status"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update payment status"]);
}

$conn->close();
?>
