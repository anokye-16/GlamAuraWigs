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
    $res = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
    $feedback = [];
    while($row = $res->fetch_assoc()) $feedback[] = $row;
    echo json_encode(['status' => 'success', 'feedback' => $feedback]);
    exit;
}

if($method === 'PUT'){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $response = $conn->real_escape_string($data['admin_response'] ?? '');
    
    $sql = "UPDATE feedback SET admin_response='$response', responded_at=NOW(), status='Responded' WHERE id=$id";
    if($conn->query($sql)) echo json_encode(['status' => 'success']);
    else echo json_encode(['error' => $conn->error]);
    exit;
}

if($method === 'DELETE'){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $conn->query("DELETE FROM feedback WHERE id=$id");
    echo json_encode(['status' => 'success']);
    exit;
}
?>
