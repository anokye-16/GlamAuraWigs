<?php
session_start();
include 'db.php';

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

$full_name = $conn->real_escape_string($_POST['full_name']);
$email = $conn->real_escape_string($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);

// check if email exists
$check = $conn->query("SELECT id FROM users WHERE email='$email'");

if($check->num_rows > 0){
echo json_encode([
'status'=>false,
'message'=>'Email already exists'
]);
exit;
}

// insert user
$conn->query("INSERT INTO users (full_name, email, password)
VALUES ('$full_name','$email','$password')");

$user_id = $conn->insert_id;

// AUTO LOGIN AFTER SIGNUP
$_SESSION['user_id'] = $user_id;
$_SESSION['user_name'] = $full_name;

echo json_encode([
'status'=>true,
'message'=>'Account created successfully',
'user_id'=>$user_id,
'full_name'=>$full_name,
'email'=>$email
]);

}
?>