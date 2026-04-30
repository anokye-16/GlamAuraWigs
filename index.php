<?php
// Enable error reporting to find out why the page is blank
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (file_exists('home.html')) {
    include 'home.html';
} else {
    echo "<h1>Error: home.html not found in the root directory!</h1>";
}
?>
