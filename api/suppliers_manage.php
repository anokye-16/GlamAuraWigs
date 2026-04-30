<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Fetch suppliers
if($method === 'GET') {
    $res = $conn->query("SELECT *, company_name as name FROM suppliers ORDER BY id DESC");
    $suppliers = [];
    while($row = $res->fetch_assoc()) {
        $suppliers[] = $row;
    }
    echo json_encode(['status' => 'success', 'suppliers' => $suppliers]);
    exit;
}

// POST: Add new supplier
if($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $company_name = $conn->real_escape_string($data['name'] ?? '');
    $contact_person = $conn->real_escape_string($data['contact_person'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    $address = $conn->real_escape_string($data['address'] ?? '');
    
    $stmt = $conn->prepare("INSERT INTO suppliers (company_name, contact_person, email, phone, address) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $company_name, $contact_person, $email, $phone, $address);
    
    if($stmt->execute()) echo json_encode(['status' => 'success']);
    else echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

// PUT: Update supplier
if($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $id = (int)$data['id'];
    $company_name = $conn->real_escape_string($data['name'] ?? '');
    $contact_person = $conn->real_escape_string($data['contact_person'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $phone = $conn->real_escape_string($data['phone'] ?? '');
    
    $stmt = $conn->prepare("UPDATE suppliers SET company_name=?, contact_person=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssi", $company_name, $contact_person, $email, $phone, $id);
    
    if($stmt->execute()) echo json_encode(['status' => 'success']);
    else echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

// DELETE: Delete supplier
if($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    if(isset($data['id'])) {
        $id = (int)$data['id'];
        $conn->query("DELETE FROM suppliers WHERE id = $id");
    }
    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['error' => 'Invalid method']);
?>
