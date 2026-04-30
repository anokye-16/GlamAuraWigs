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

$method = $_SERVER['REQUEST_METHOD'];

/* ===================== GET: FETCH FEEDBACK ===================== */
if ($method === 'GET') {
    $customer_id = $_GET['customer_id'] ?? null;
    $admin_mode = isset($_GET['admin']) && $_GET['admin'] === '1';

    // Admin gets all feedback, customer gets their own
    if ($admin_mode) {
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
            echo json_encode(["status" => "error", "message" => "Unauthorized"]);
            exit;
        }
        $sql = "SELECT f.*, c.full_name as customer_name 
                FROM feedback f 
                LEFT JOIN customers c ON f.customer_id = c.id 
                ORDER BY f.created_at DESC";
        $result = $conn->query($sql);
    } else if ($customer_id) {
        $stmt = $conn->prepare("SELECT * FROM feedback WHERE customer_id = ? ORDER BY created_at DESC");
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        echo json_encode(["status" => "error", "message" => "customer_id or admin required"]);
        exit;
    }

    $feedback = [];
    while ($row = $result->fetch_assoc()) {
        $feedback[] = $row;
    }

    echo json_encode(["status" => "success", "feedback" => $feedback]);
    $conn->close();
    exit;
}

/* ===================== POST: ADD FEEDBACK ===================== */
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $customer_id = $data['customer_id'] ?? null;
    $customer_name = $conn->real_escape_string($data['customer_name'] ?? '');
    $email = $conn->real_escape_string($data['email'] ?? '');
    $subject = $conn->real_escape_string($data['subject'] ?? '');
    $message = $conn->real_escape_string($data['message'] ?? '');
    $rating = isset($data['rating']) ? (int)$data['rating'] : 5;

    if (!$customer_name || !$message) {
        echo json_encode(["status" => "error", "message" => "Name and message are required"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO feedback (customer_id, customer_name, email, subject, message, rating) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssi", $customer_id, $customer_name, $email, $subject, $message, $rating);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Feedback submitted successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to submit feedback"]);
    }

    $conn->close();
    exit;
}

/* ===================== PUT: UPDATE FEEDBACK STATUS (ADMIN) ===================== */
if ($method === 'PUT') {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? 0;
    $status = $conn->real_escape_string($data['status'] ?? '');
    $admin_response = $conn->real_escape_string($data['admin_response'] ?? '');

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Feedback ID required"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE feedback SET status = ?, admin_response = ?, responded_at = NOW() WHERE id = ?");
    $stmt->bind_param("ssi", $status, $admin_response, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Feedback updated"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Update failed"]);
    }

    $conn->close();
    exit;
}

/* ===================== DELETE: DELETE FEEDBACK (ADMIN) ===================== */
if ($method === 'DELETE') {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
        echo json_encode(["status" => "error", "message" => "Unauthorized"]);
        exit;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'] ?? 0;

    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Feedback ID required"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Feedback deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Delete failed"]);
    }

    $conn->close();
    exit;
}

$conn->close();
echo json_encode(["status" => "error", "message" => "Invalid request method"]);
?>
