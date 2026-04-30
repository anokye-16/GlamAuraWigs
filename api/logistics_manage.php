<?php
session_start();
include '../db.php';
header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if($method === 'GET'){
    $res = $conn->query("SELECT * FROM partners ORDER BY id DESC");
    $partners = [];
    while($row = $res->fetch_assoc()) $partners[] = $row;
    echo json_encode(['partners' => $partners]);
    exit;
}

if($method === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $name = $conn->real_escape_string($data['company_name']);
    $contact = $conn->real_escape_string($data['contact_person'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $type = $conn->real_escape_string($data['partnership_type'] ?? 'Delivery');

    $sql = "INSERT INTO partners (company_name, contact_person, email, phone, partnership_type) 
            VALUES ('$name', '$contact', '$email', '$phone', '$type')";
    
    if($conn->query($sql)){
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['error' => $conn->error]);
    }
    exit;
}

if($method === 'DELETE'){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $conn->query("DELETE FROM partners WHERE id=$id");
    echo json_encode(['status' => 'success']);
    exit;
}
?>
