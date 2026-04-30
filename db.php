<?php
// Use Railway environment variables if they exist, otherwise fallback to XAMPP defaults
$host = getenv('MYSQLHOST') ?: "localhost";
$user = getenv('MYSQLUSER') ?: "root";
$pass = getenv('MYSQLPASSWORD') ?: ""; 
$dbname = getenv('MYSQLDATABASE') ?: "glamaura";
$port = getenv('MYSQLPORT') ?: 3306;

// Create connection using environment variables or fallbacks
$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>