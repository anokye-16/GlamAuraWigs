<?php
// Serve the home page content directly
if (file_exists('Home.html')) {
    include 'Home.html';
} else {
    include 'home.html';
}
?>
