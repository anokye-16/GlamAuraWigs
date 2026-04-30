<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Status Check</h1>";

if (!extension_loaded('mysqli')) {
    echo "<p style='color:red;'>❌ ERROR: The 'mysqli' extension is NOT loaded on this server.</p>";
} else {
    echo "<p style='color:green;'>✅ SUCCESS: 'mysqli' extension is loaded.</p>";
}

echo "<p>PHP Version: " . phpversion() . "</p>";

include 'db.php';
echo "<p>✅ Database connection file loaded.</p>";

include 'home.html';
?>
