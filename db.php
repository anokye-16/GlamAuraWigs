<?php
// Check if Railway's MYSQL_URL is available
$mysql_url = getenv('MYSQL_URL');

if ($mysql_url) {
    $url = parse_url($mysql_url);
    $host = $url['host'] ?? null;
    $user = $url['user'] ?? null;
    $pass = $url['pass'] ?? null;
    $dbname = isset($url['path']) ? ltrim($url['path'], '/') : null;
    $port = $url['port'] ?? 3306;
} else {
    $host = getenv('MYSQLHOST') ?: "localhost";
    $user = getenv('MYSQLUSER') ?: "root";
    $pass = getenv('MYSQLPASSWORD') ?: ""; 
    $dbname = getenv('MYSQLDATABASE') ?: "glamaura";
    $port = getenv('MYSQLPORT') ?: 3306;
}

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Database Connection Failed.");
}
?>