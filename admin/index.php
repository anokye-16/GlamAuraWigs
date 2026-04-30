<?php
// Enable error reporting to find out why the admin page is failing
error_reporting(E_ALL);
ini_set('display_errors', 1);

$files = ['dashboard.html', 'Dashboard.html'];
$found = false;

foreach ($files as $file) {
    if (file_exists($file)) {
        include $file;
        $found = true;
        break;
    }
}

if (!$found) {
    echo "<h1>Admin Dashboard Debug</h1>";
    echo "<p>Error: dashboard.html not found in the admin directory.</p>";
    echo "<h3>Files in this folder:</h3><ul>";
    foreach (scandir('.') as $f) {
        if ($f !== '.' && $f !== '..') echo "<li>$f</li>";
    }
    echo "</ul>";
}
?>
