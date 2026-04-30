<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

echo "<h1>Database Table Scan</h1>";

$tables = ['admin', 'customers', 'products', 'orders'];
foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<p style='color:green;'>✅ Table '$table' exists.</p>";
    } else {
        echo "<p style='color:red;'>❌ Table '$table' is MISSING!</p>";
    }
}
?>
