<?php
session_start();
include 'db.php';

header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];

$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if($result->num_rows == 0){
echo json_encode([
'status'=>false,
'message'=>'User not found'
]);
exit;
}

$user = $result->fetch_assoc();

if(password_verify($password, $user['password'])){

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];

echo json_encode([
'status'=>true,
'message'=>'Login successful',
'user_id'=>$user['id'],
'full_name'=>$user['full_name'],
'email'=>$user['email']
]);

} else {

echo json_encode([
'status'=>false,
'message'=>'Invalid password'
]);
}
}
?>