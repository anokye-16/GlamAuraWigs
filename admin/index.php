<?php
// Try to find the dashboard page file (checking for case sensitivity)
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
    echo "<h1>Admin Dashboard</h1>";
    echo "<p>Error: Could not find the dashboard page file.</p>";
}
?>
