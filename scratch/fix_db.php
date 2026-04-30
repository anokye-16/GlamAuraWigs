<?php
$conn = new mysqli("localhost", "root", "", "glamaura");
if ($conn->connect_error) die("Connection failed");

$sql = "ALTER TABLE deliveries ADD COLUMN partner_id INT NULL";
if ($conn->query($sql) === TRUE) {
    echo "Column added successfully";
} else {
    echo "Error or column already exists: " . $conn->error;
}
$conn->close();
?>
