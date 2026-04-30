<?php
include 'db.php';

$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        echo '
        <div class="product-card">
            <img src="'.$row['image'].'">
            <div class="card-body">
                <h3>'.$row['name'].'</h3>
                <p class="price">GH¢'.$row['base_price'].'</p>
                <div class="desc">'.$row['description'].'</div>
                <button class="add-btn" data-id="'.$row['id'].'">Add to Cart</button>
            </div>
        </div>
        ';
    }
}
?>