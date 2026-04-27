<?php
header("Content-Type: application/json");

$conn = new mysqli("localhost","root","","glamaura");

if($conn->connect_error){
echo json_encode(["status"=>"error"]);
exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$cart = $data['cart'];
$total = $data['total'];
$vat = $data['vat'];
$shipping = $data['shipping'];

$customer = $data['customer'];
$full_name = $customer['name'] ?? NULL;

if(empty($cart) || !$full_name){
echo json_encode(["status"=>"error","message"=>"Missing data"]);
exit;
}

/* INSERT ORDER (NOW WITH FULL NAME) */
$stmt = $conn->prepare("
INSERT INTO orders (full_name, total, vat, shipping)
VALUES (?, ?, ?, ?)
");

$stmt->bind_param("sddd", $full_name, $total, $vat, $shipping);
$stmt->execute();

$order_id = $stmt->insert_id;

/* INSERT ITEMS */
$itemStmt = $conn->prepare("
INSERT INTO order_items (order_id, product_id, quantity, quality, price)
VALUES (?, ?, ?, ?, ?)
");

foreach($cart as $item){

$product_id = $item['product_id'];
$qty = $item['qty'];
$quality = $item['quality'];
$price = $item['basePrice'];

$itemStmt->bind_param(
"iiisd",
$order_id,
$product_id,
$qty,
$quality,
$price
);

$itemStmt->execute();
}

echo json_encode([
"status"=>"success",
"order_id"=>$order_id
]);

$conn->close();
?>