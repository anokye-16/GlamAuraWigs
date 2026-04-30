<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

// Auth check
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$response = [
    'kpi' => [],
    'revenue_chart' => ['labels' => [], 'data' => []],
    'order_chart' => ['labels' => ['Pending', 'Processing', 'Shipped', 'Delivered'], 'data' => [0,0,0,0]],
    'low_stock' => [],
    'recent_orders' => []
];

// KPI: Total Revenue
$res = $conn->query("SELECT SUM(total) as rev FROM orders WHERE status != 'Cancelled'");
$response['kpi']['revenue'] = $res->fetch_assoc()['rev'] ?? 0;

// KPI: Total Orders
$res = $conn->query("SELECT COUNT(*) as count FROM orders");
$response['kpi']['orders'] = $res->fetch_assoc()['count'] ?? 0;

// KPI: Total Customers
$res = $conn->query("SELECT COUNT(*) as count FROM customers");
$response['kpi']['customers'] = $res->fetch_assoc()['count'] ?? 0;

// KPI: Total Products
$res = $conn->query("SELECT COUNT(*) as count FROM products");
$response['kpi']['products'] = $res->fetch_assoc()['count'] ?? 0;

// KPI: Low Stock Count
$res = $conn->query("SELECT COUNT(*) as count FROM inventory WHERE stock_quantity <= 10");
$response['kpi']['low_stock'] = $res->fetch_assoc()['count'] ?? 0;

// Revenue Chart: Last 7 Days
for($i=6; $i>=0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $response['revenue_chart']['labels'][] = date('M d', strtotime($date));
    
    $res = $conn->query("SELECT SUM(total) as daily FROM orders WHERE DATE(created_at) = '$date' AND status != 'Cancelled'");
    $row = $res->fetch_assoc();
    $response['revenue_chart']['data'][] = $row['daily'] ? (float)$row['daily'] : 0;
}

// Order Chart (Donut)
$res = $conn->query("SELECT status, COUNT(*) as count FROM orders GROUP BY status");
while($row = $res->fetch_assoc()) {
    $idx = array_search($row['status'], $response['order_chart']['labels']);
    if($idx !== false) {
        $response['order_chart']['data'][$idx] = (int)$row['count'];
    }
}

// Low Stock Table (Limit 5)
$res = $conn->query("
    SELECT p.id, p.name, i.stock_quantity 
    FROM products p 
    JOIN inventory i ON p.id = i.product_id 
    WHERE i.stock_quantity <= 10 
    ORDER BY i.stock_quantity ASC 
    LIMIT 5
");
while($row = $res->fetch_assoc()) {
    $response['low_stock'][] = $row;
}

// Recent Orders (Limit 5)
$res = $conn->query("
    SELECT o.id, o.full_name, o.total, o.status, p.method as payment_method, p.status as payment_status
    FROM orders o
    LEFT JOIN payments p ON o.id = p.order_id
    ORDER BY o.created_at DESC 
    LIMIT 5
");
while($row = $res->fetch_assoc()) {
    $response['recent_orders'][] = $row;
}

echo json_encode($response);
?>
