<?php
// Try to find the home page file (checking for case sensitivity)
$files = ['home.html', 'Home.html', 'HOME.HTML'];
$found = false;

foreach ($files as $file) {
    if (file_exists($file)) {
        include $file;
        $found = true;
        break;
    }
}

if (!$found) {
    echo "<h1>GlamAura Wigs</h1>";
    echo "<p>Error: Could not find the home page file. Please check your filenames.</p>";
    // List files to help debug
    echo "<h3>Files found in directory:</h3><ul>";
    foreach (scandir('.') as $f) {
        if ($f !== '.' && $f !== '..') echo "<li>$f</li>";
    }
    echo "</ul>";
}
?>
