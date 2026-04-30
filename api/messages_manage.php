<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Fetch messages
if($method === 'GET') {
    $res = $conn->query("SELECT * FROM messages ORDER BY created_at DESC");
    $messages = [];
    while($row = $res->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(['status' => 'success', 'messages' => $messages]);
    exit;
}

// PUT: Mark as read
if($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if(isset($data['mark_all']) && $data['mark_all'] === true) {
        $conn->query("UPDATE messages SET is_read = 1 WHERE is_read = 0");
    } else if(isset($data['id'])) {
        $id = (int)$data['id'];
        $conn->query("UPDATE messages SET is_read = 1 WHERE id = $id");
    }
    
    echo json_encode(['status' => 'success']);
    exit;
}

// DELETE: Delete message
if($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['id'])) {
        $id = (int)$data['id'];
        $conn->query("DELETE FROM messages WHERE id = $id");
    }
    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['error' => 'Invalid method']);
?>
