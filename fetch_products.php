<?php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo '
        <div class="card">
            <img src="'.$row['image'].'">
            <h3>'.$row['name'].'</h3>
            <p class="price">$'.$row['base_price'].'</p>
            <div class="details">'.$row['description'].'</div>
            <button onclick="addToCart('.$row['id'].')">Add to Cart</button>
        </div>
        ';
    }
}
?>