<?php
session_start();
header("Content-Type: application/json");

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "glamaura";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'customer'){
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
    exit;
}

$customer_id = $_SESSION['user_id'];

$data = json_decode(file_get_contents("php://input"), true);

$full_name = $conn->real_escape_string($data['full_name'] ?? '');
$phone = $conn->real_escape_string($data['phone'] ?? '');
$shipping_address = $conn->real_escape_string($data['shipping_address'] ?? '');
$city = $conn->real_escape_string($data['city'] ?? '');
$country = $conn->real_escape_string($data['country'] ?? '');

if(!$full_name){
    echo json_encode(["status" => "error", "message" => "Full name is required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE customers SET full_name = ?, phone = ?, shipping_address = ?, city = ?, country = ? WHERE id = ?");
$stmt->bind_param("sssssi", $full_name, $phone, $shipping_address, $city, $country, $customer_id);

if($stmt->execute()){
    $_SESSION['user_name'] = $full_name;
    echo json_encode([
        "status" => "success",
        "message" => "Profile updated successfully"
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update profile"
    ]);
}

$conn->close();
?>
