<?php
session_start();
// Enable error reporting to find the cause of the Server Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    // Get JSON POST data or Form data
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $full_name = $data['full_name'] ?? $_POST['full_name'] ?? '';
    $email = $data['email'] ?? $_POST['email'] ?? '';
    $raw_password = $data['password'] ?? $_POST['password'] ?? '';

    if (empty($full_name) || empty($email) || empty($raw_password)) {
        echo json_encode(['status' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if (!$conn) {
        echo json_encode(['status' => false, 'message' => 'Database connection missing.']);
        exit;
    }

    $full_name = $conn->real_escape_string($full_name);
    $email = $conn->real_escape_string($email);
    $password = password_hash($raw_password, PASSWORD_BCRYPT);
 
    // check if email exists in customers table
    $check = $conn->query("SELECT id FROM customers WHERE email='$email'");
    
    if($check && $check->num_rows > 0){
        echo json_encode([
            'status'=>false,
            'message'=>'Email already exists'
        ]);
        exit;
    }
    
    // insert user into customers table
    $sql = "INSERT INTO customers (full_name, email, password) VALUES ('$full_name','$email','$password')";
    if($conn->query($sql)){
        $user_id = $conn->insert_id;
        
        // AUTO LOGIN AFTER SIGNUP
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $full_name;
        $_SESSION['user_type'] = 'customer';
        
        echo json_encode([
            'status'=>true,
            'message'=>'Account created successfully',
            'user_id'=>$user_id,
            'full_name'=>$full_name,
            'email'=>$email,
            'is_admin'=>false
        ]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Error creating account: ' . $conn->error]);
    }
}
?>