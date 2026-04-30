<?php
include 'db.php';
$res = $conn->query("SELECT id, name, image FROM products ORDER BY id DESC LIMIT 5");
while($row = $res->fetch_assoc()){
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Image: " . $row['image'] . "\n";
}
?>
