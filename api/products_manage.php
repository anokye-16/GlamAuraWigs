<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

// GET: Fetch products
if($method === 'GET') {
    $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
    $products = [];
    while($row = $res->fetch_assoc()) {
        $products[] = $row;
    }
    echo json_encode(['status' => 'success', 'products' => $products]);
    exit;
}

// POST: Add new product
if($method === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = (float)$_POST['price'];
    $description = $conn->real_escape_string($_POST['description'] ?? '');
    $image = '';

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $target_dir = "../images/";
        if(!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        
        $imageName = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $imageName;
        
        if(move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)){
            $image = "images/" . $imageName;
        }
    }

    $stmt = $conn->prepare("INSERT INTO products (name, base_price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $image);
    
    if($stmt->execute()){
        $pid = $conn->insert_id;
        $conn->query("INSERT INTO inventory (product_id, stock_quantity) VALUES ($pid, 0)");
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product: ' . $conn->error]);
    }
    exit;
}

$json = file_get_contents('php://input');
$data = json_decode($json, true);

// PUT: Update product
if($method === 'PUT') {
    $id = (int)$data['id'];
    $name = $conn->real_escape_string($data['name']);
    $price = (float)$data['price'];
    
    $stmt = $conn->prepare("UPDATE products SET name=?, base_price=? WHERE id=?");
    $stmt->bind_param("sdi", $name, $price, $id);
    
    if($stmt->execute()) echo json_encode(['status' => 'success']);
    else echo json_encode(['status' => 'error', 'message' => $conn->error]);
    exit;
}

// DELETE: Delete product
if($method === 'DELETE') {
    if(isset($data['id'])) {
        $id = (int)$data['id'];
        $conn->query("DELETE FROM inventory WHERE product_id = $id");
        $conn->query("DELETE FROM products WHERE id = $id");
    }
    echo json_encode(['status' => 'success']);
    exit;
}

echo json_encode(['error' => 'Invalid method']);
?>
