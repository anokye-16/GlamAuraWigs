<?php
include 'db.php';

$email = 'admin@glamaura.com';
$new_password = password_hash('admin123', PASSWORD_BCRYPT);

$sql = "UPDATE admin SET password = '$new_password' WHERE email = '$email'";

if ($conn->query($sql)) {
    if ($conn->affected_rows > 0) {
        echo "<h1>✅ Success!</h1><p>Admin password has been reset to: <strong>admin123</strong></p>";
    } else {
        echo "<h1>⚠️ Warning</h1><p>Admin email not found. Creating a new admin account...</p>";
        $conn->query("INSERT INTO admin (full_name, email, password, role) VALUES ('Admin User', '$email', '$new_password', 'admin')");
        echo "<p>✅ New admin account created with password: <strong>admin123</strong></p>";
    }
} else {
    echo "<h1>❌ Error</h1><p>" . $conn->error . "</p>";
}
?>
