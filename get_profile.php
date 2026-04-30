<?php
session_start();
// Enable error reporting to find the cause of the Server Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer'){
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$customer_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, full_name, email, phone, shipping_address, city, country, created_at FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    echo json_encode(["status" => "error", "message" => "Customer not found"]);
    exit;
}

$customer = $result->fetch_assoc();

echo json_encode([
    "status" => "success",
    "customer" => $customer
]);

$conn->close();
?>
