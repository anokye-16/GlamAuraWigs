<?php
session_start();
header("Content-Type: application/json");

if(isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'admin'){
    echo json_encode([
        'authenticated' => true,
        'user' => [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name']
        ]
    ]);
} else {
    echo json_encode([
        'authenticated' => false
    ]);
}
?>
