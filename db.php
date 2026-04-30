<?php
// Try to get database credentials from different possible sources
$mysql_url = getenv('MYSQL_URL');
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$dbname = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT') ?: 3306;

// If MYSQL_URL is provided, it takes priority
if ($mysql_url) {
    $url = parse_url($mysql_url);
    if ($url) {
        $host = $url['host'] ?? $host;
        $user = $url['user'] ?? $user;
        $pass = $url['pass'] ?? $pass;
        $dbname = isset($url['path']) ? ltrim($url['path'], '/') : $dbname;
        $port = $url['port'] ?? $port;
    }
}

// Fallback for local development
if (!$host) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "glamaura";
}

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    // If it's an API request, return JSON. Otherwise, show a nice message.
    if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false || isset($_GET['api'])) {
        header('Content-Type: application/json');
        die(json_encode(['status' => false, 'message' => 'Database connection failed.']));
    }
    die("Database Connection Failed.");
}
?>