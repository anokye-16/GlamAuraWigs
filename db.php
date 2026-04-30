<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
try {
    $conn = new mysqli($host, $user, $pass, $dbname, $port);
    if ($conn->connect_error) {
        throw new Exception("Database Connection Failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("<h1>Database Error</h1><p>" . $e->getMessage() . "</p>");
}
?>