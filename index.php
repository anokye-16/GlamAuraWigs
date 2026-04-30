<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<div style='background:white; color:black; padding:20px; border:5px solid red; font-family:sans-serif; z-index:9999; position:relative;'>";
echo "<h1>System Status Check</h1>";

if (!extension_loaded('mysqli')) {
    echo "<p style='color:red;'>❌ ERROR: The 'mysqli' extension is NOT loaded.</p>";
} else {
    echo "<p style='color:green;'>✅ SUCCESS: 'mysqli' extension is loaded.</p>";
}

include 'db.php';
echo "<p>✅ Database connection file loaded.</p>";
echo "</div>";

include 'home.html';
?>
