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
$status = $data['status'] ?? '';

$validStatuses = ['Pending', 'Processing', 'Shipped', 'Out for Delivery', 'Delivered', 'Cancelled'];

if(!$order_id || !in_array($status, $validStatuses)){
    echo json_encode(["status" => "error", "message" => "Invalid data"]);
    exit;
}

// Update order status
$stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $order_id);

if($stmt->execute()){
    // Self-healing: Check if partner_id column exists
    $checkCol = $conn->query("SHOW COLUMNS FROM deliveries LIKE 'partner_id'");
    if($checkCol->num_rows == 0) {
        $conn->query("ALTER TABLE deliveries ADD COLUMN partner_id INT NULL");
    }

    // Check if delivery record exists for this order
    $check = $conn->prepare("SELECT id FROM deliveries WHERE order_id = ?");
    $check->bind_param("i", $order_id);
    $check->execute();
    $res = $check->get_result();
    
    $partner_id = !empty($data['partner_id']) ? (int)$data['partner_id'] : null;
    
    try {
        if($res->num_rows > 0){
            // Update existing record
            $deliveryStmt = $conn->prepare("UPDATE deliveries SET status = ?, partner_id = ?, last_updated = NOW() WHERE order_id = ?");
            $deliveryStmt->bind_param("sii", $status, $partner_id, $order_id);
        } else {
            // Insert new record
            $deliveryStmt = $conn->prepare("INSERT INTO deliveries (order_id, status, tracking_number, partner_id) VALUES (?, ?, ?, ?)");
            $tracking = "GLAM-" . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            $deliveryStmt->bind_param("issi", $order_id, $status, $tracking, $partner_id);
        }
        $deliveryStmt->execute();
    } catch (Exception $e) {
        // If the column doesn't exist yet, we still want to update the order status
        error_log("Delivery update failed: " . $e->getMessage());
    }

    echo json_encode(["status" => "success", "message" => "Order status updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update status"]);
}

$conn->close();
?>
