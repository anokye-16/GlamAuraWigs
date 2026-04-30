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
    // Fetch POs with supplier and product names
    $sql = "SELECT po.*, s.company_name as supplier_name, p.name as product_name 
            FROM purchase_orders po
            JOIN suppliers s ON po.supplier_id = s.id
            JOIN products p ON po.product_id = p.id
            ORDER BY po.id DESC";
    $res = $conn->query($sql);
    $orders = [];
    while($row = $res->fetch_assoc()) $orders[] = $row;
    echo json_encode(['status' => 'success', 'orders' => $orders]);
    exit;
}

if($method === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $sid = (int)$data['supplier_id'];
    $pid = (int)$data['product_id'];
    $qty = (int)$data['quantity'];
    $price = (float)$data['unit_price'];
    $total = $qty * $price;

    $stmt = $conn->prepare("INSERT INTO purchase_orders (supplier_id, product_id, quantity, unit_price, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iiidd", $sid, $pid, $qty, $price, $total);
    
    if($stmt->execute()) echo json_encode(['status' => 'success']);
    else echo json_encode(['error' => $conn->error]);
    exit;
}

if($method === 'PUT'){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $action = $data['action']; // e.g. 'receive'

    if($action === 'receive'){
        // 1. Get PO details
        $res = $conn->query("SELECT product_id, quantity FROM purchase_orders WHERE id=$id AND status='Pending'");
        if($po = $res->fetch_assoc()){
            $pid = $po['product_id'];
            $qty = $po['quantity'];

            // 2. Update PO status
            $conn->query("UPDATE purchase_orders SET status='Received', received_date=NOW() WHERE id=$id");

            // 3. Update Inventory (Add quantity)
            // Check if record exists
            $invCheck = $conn->query("SELECT id FROM inventory WHERE product_id=$pid");
            if($invCheck->num_rows > 0){
                $conn->query("UPDATE inventory SET stock_quantity = stock_quantity + $qty WHERE product_id=$pid");
            } else {
                $conn->query("INSERT INTO inventory (product_id, stock_quantity) VALUES ($pid, $qty)");
            }
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['error' => 'Order already received or not found']);
        }
    }
    exit;
}

if($method === 'DELETE'){
    $data = json_decode(file_get_contents('php://input'), true);
    $id = (int)$data['id'];
    $conn->query("DELETE FROM purchase_orders WHERE id=$id AND status='Pending'");
    echo json_encode(['status' => 'success']);
    exit;
}
?>
