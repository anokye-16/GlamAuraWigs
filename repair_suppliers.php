<?php
include 'db.php';

// Check if table exists
$result = $conn->query("SHOW TABLES LIKE 'suppliers'");
if($result->num_rows == 0) {
    echo "Creating table... ";
    $sql = "CREATE TABLE suppliers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        contact_person VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(50),
        address TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    if($conn->query($sql)) echo "Table created successfully!";
    else echo "Error creating table: " . $conn->error;
} else {
    echo "Table already exists. Checking for 'address' column... ";
    $checkCol = $conn->query("SHOW COLUMNS FROM suppliers LIKE 'address'");
    if($checkCol->num_rows == 0) {
        $conn->query("ALTER TABLE suppliers ADD COLUMN address TEXT NULL");
        echo "Column 'address' added!";
    } else {
        echo "Everything looks good!";
    }
}
$conn->close();
?>
