<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>System Status</h1>";

// Function to find a file anywhere in the directory structure
function findFile($filename, $dir = '.') {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($path)) {
            $res = findFile($filename, $path);
            if ($res) return $res;
        } elseif (strtolower($file) === strtolower($filename)) {
            return $path;
        }
    }
    return null;
}

$homeFile = findFile('home.html');

if ($homeFile) {
    echo "<p>✅ Found home page at: $homeFile</p><hr>";
    include $homeFile;
} else {
    echo "<p style='color:red;'>❌ ERROR: Could not find home.html anywhere in your repository.</p>";
    echo "<h3>Files found in the root:</h3><ul>";
    foreach (scandir('.') as $f) {
        echo "<li>$f</li>";
    }
    echo "</ul>";
}
?>
