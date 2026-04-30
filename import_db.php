<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

echo "<h1>Starting One-Click Database Import...</h1>";

$sql_file = 'glamaura.sql';

if (!file_exists($sql_file)) {
    // Try capitalized
    $sql_file = 'Glamaura.sql';
}

if (!file_exists($sql_file)) {
    die("<p style='color:red;'>❌ ERROR: Could not find glamaura.sql file in the root directory.</p>");
}

$sql = file_get_contents($sql_file);

// Remove comments and empty lines
$sql = preg_replace('/--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Disable foreign key checks to allow truncate/drop
$conn->query("SET FOREIGN_KEY_CHECKS = 0;");

if ($conn->multi_query($sql)) {
    do {
        /* store first result set */
        if ($result = $conn->store_result()) {
            $result->free();
        }
    } while ($conn->next_result());
    
    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
    
    echo "<p style='color:green; font-size:24px;'>✅ SUCCESS! Your database has been imported. All tables and products are now live!</p>";
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
} else {
    echo "<p style='color:red;'>❌ Error during import: " . $conn->error . "</p>";
}
?>
