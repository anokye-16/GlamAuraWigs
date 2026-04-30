<?php
session_start();
include '../db.php';

header("Content-Type: application/json");

if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin'){
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $report_type = $_GET['report'] ?? 'summary';

    if ($report_type === 'summary') {
        // Total revenue
        $revenue = $conn->query("SELECT COALESCE(SUM(total), 0) as total FROM orders WHERE status != 'Cancelled'")->fetch_assoc()['total'];

        // Total VAT collected
        $vat = $conn->query("SELECT COALESCE(SUM(vat), 0) as total FROM orders WHERE status != 'Cancelled'")->fetch_assoc()['total'];

        // Total shipping
        $shipping = $conn->query("SELECT COALESCE(SUM(shipping), 0) as total FROM orders WHERE status != 'Cancelled'")->fetch_assoc()['total'];

        // Monthly sales (last 6 months)
        $monthlySales = [];
        $monthlyResult = $conn->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COALESCE(SUM(total), 0) as total, COUNT(*) as order_count
            FROM orders
            WHERE status != 'Cancelled' AND created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ");
        while ($row = $monthlyResult->fetch_assoc()) {
            $monthlySales[] = $row;
        }

        // Payment status breakdown
        $paymentStats = [];
        $paymentResult = $conn->query("
            SELECT status, COUNT(*) as count, COALESCE(SUM(amount), 0) as total
            FROM payments GROUP BY status
        ");
        while ($row = $paymentResult->fetch_assoc()) {
            $paymentStats[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "summary" => [
                "total_revenue" => (float)$revenue,
                "total_vat" => (float)$vat,
                "total_shipping" => (float)$shipping,
                "net_revenue" => (float)($revenue - $vat - $shipping)
            ],
            "monthly_sales" => $monthlySales,
            "payment_stats" => $paymentStats
        ]);
    } else if ($report_type === 'orders') {
        // Detailed order list with payments
        $result = $conn->query("
            SELECT o.*, p.status as payment_status, p.method as payment_method
            FROM orders o
            LEFT JOIN payments p ON o.id = p.order_id
            ORDER BY o.created_at DESC
            LIMIT 200
        ");
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        echo json_encode(["status" => "success", "orders" => $orders]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid method']);
?>
