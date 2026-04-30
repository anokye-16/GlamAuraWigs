<?php
// Check if Railway's MYSQL_URL is available, otherwise use individual variables or localhost
if (getenv('MYSQL_URL')) {
    $url = parse_url(getenv('MYSQL_URL'));
    $host = $url['host'];
    $user = $url['user'];
    $pass = $url['pass'];
    $dbname = ltrim($url['path'], '/');
    $port = $url['port'];
} else {
    $host = getenv('MYSQLHOST') ?: "localhost";
    $user = getenv('MYSQLUSER') ?: "root";
    $pass = getenv('MYSQLPASSWORD') ?: ""; 
    $dbname = getenv('MYSQLDATABASE') ?: "glamaura";
    $port = getenv('MYSQLPORT') ?: 3306;
}

// Create connection using the detected credentials
$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>