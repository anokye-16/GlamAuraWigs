<?php

session_start();
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "glamaura";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "DB connection failed"]));
}

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

$cart = $data['cart'];
$total = $data['total'];
$vat = $data['vat'];
$shipping = $data['shipping'];

$customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (customer_id, total, vat, shipping) VALUES (?, ?, ?, ?)");
if (!$stmt->execute()) {
    die(json_encode(["status" => "error", "message" => $stmt->error]));
}

$order_id = $stmt->insert_id;

// Insert order items
$itemStmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, quality, price) VALUES (?, ?, ?, ?, ?)");

foreach ($cart as $item) {
    $itemStmt->bind_param(
        "iiisd",
        $order_id,
        $item['product_id'],
        $item['qty'],
        $item['quality'],
        $item['basePrice']
    );
    if (!$itemStmt->execute()) {
        die(json_encode(["status" => "error", "message" => $itemStmt->error]));
    }
}

echo json_encode(["status" => "success"]);

$conn->close();
?>