<?php
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

// Get JSON data
$data = json_decode(file_get_contents("php://input"), true);

$name = isset($data['name']) ? $conn->real_escape_string($data['name']) : '';
$email = isset($data['email']) ? $conn->real_escape_string($data['email']) : '';
$subject = isset($data['subject']) ? $conn->real_escape_string($data['subject']) : '';
$message = isset($data['message']) ? $conn->real_escape_string($data['message']) : '';

if(!$name || !$email || !$message){
    echo json_encode(["status" => "error", "message" => "Name, email and message are required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if($stmt->execute()){
    echo json_encode([
        "status" => "success",
        "message" => "Thank you! Your message has been sent successfully."
    ]);
}else{
    echo json_encode([
        "status" => "error",
        "message" => "Failed to send message. Please try again."
    ]);
}

$conn->close();
?>
