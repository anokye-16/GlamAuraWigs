<?php
session_start();
// Enable error reporting to find the cause of the Server Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../db.php';

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    // Get JSON POST data or Form data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $email = $conn->real_escape_string($data['email'] ?? $_POST['email'] ?? '');
    $password = $data['password'] ?? $_POST['password'] ?? '';

    if(empty($email) || empty($password)){
        echo json_encode(['status'=>false, 'message'=>'Email and password are required']);
        exit;
    }

    // 1. CHECK ADMIN TABLE
    $admin_result = $conn->query("SELECT * FROM admin WHERE email='$email'");
    if($admin_result && $admin_result->num_rows > 0){
        $admin = $admin_result->fetch_assoc();
        if(password_verify($password, $admin['password'])){
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['full_name'];
            $_SESSION['user_email'] = $admin['email'];
            $_SESSION['user_type'] = 'admin';
            
            echo json_encode([
                'status'=>true,
                'message'=>'Admin login successful',
                'user_id'=>$admin['id'],
                'full_name'=>$admin['full_name'],
                'email'=>$admin['email'],
                'is_admin'=>true
            ]);
            exit;
        }
    }

    // 2. CHECK CUSTOMERS TABLE
    $customer_result = $conn->query("SELECT * FROM customers WHERE email='$email'");
    if($customer_result && $customer_result->num_rows > 0){
        $customer = $customer_result->fetch_assoc();
        if(password_verify($password, $customer['password'])){
            $_SESSION['user_id'] = $customer['id'];
            $_SESSION['user_name'] = $customer['full_name'];
            $_SESSION['user_email'] = $customer['email'];
            $_SESSION['user_type'] = 'customer';
            
            echo json_encode([
                'status'=>true,
                'message'=>'Login successful',
                'user_id'=>$customer['id'],
                'full_name'=>$customer['full_name'],
                'email'=>$customer['email'],
                'is_admin'=>false
            ]);
            exit;
        }
    }

    echo json_encode([
        'status'=>false,
        'message'=>'Invalid email or password'
    ]);
}
?>
