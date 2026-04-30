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
    echo "<!-- Debug: Using MYSQL_URL -->";
} else {
    $host = getenv('MYSQLHOST') ?: "localhost";
    $user = getenv('MYSQLUSER') ?: "root";
    $pass = getenv('MYSQLPASSWORD') ?: ""; 
    $dbname = getenv('MYSQLDATABASE') ?: "glamaura";
    $port = getenv('MYSQLPORT') ?: 3306;
    echo "<!-- Debug: Using Individual/Fallback Variables -->";
}

// PRINT DEBUG INFO (WITHOUT PASSWORD)
echo "<div style='color:blue; font-size:12px; border:1px solid blue; padding:10px; margin:10px;'>";
echo "<strong>DEBUG CONNECTION INFO:</strong><br>";
echo "Host: " . ($host ?: 'NOT SET') . "<br>";
echo "User: " . ($user ?: 'NOT SET') . "<br>";
echo "Database: " . ($dbname ?: 'NOT SET') . "<br>";
echo "Port: " . ($port ?: 'NOT SET') . "<br>";
echo "</div>";

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