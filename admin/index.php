<?php
// Serve the admin dashboard content directly
if (file_exists('dashboard.html')) {
    include 'dashboard.html';
} else {
    include 'Dashboard.html';
}
?>
