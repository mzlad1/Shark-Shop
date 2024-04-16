<?php
session_start();
include '../Signin-up/dp.php';
$pdo = db_connect();
$currentUrl = "";
$currentUrl = $_SESSION['currentUrl'];
$quantity = $_POST['quantity'];

if(!isset($_SESSION['username'])){
    echo '<a href="../Signin-up/signin.php?redirect=' . urlencode($currentUrl) . '">Login to Add to Cart</a>';
    exit();
    
}else{
    $productId = "";
    $userId = "";
    $productId = $_SESSION['productId'];
    $query = "SELECT * FROM products WHERE id = :productId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
   
    $price = $product['price'];
    $totalPrice = $price * $quantity;
    $name = $product['name'];
    $userId = $_SESSION['user_id'];


    $query2 = "INSERT INTO cart (customerId, productId, quantity, price, name) VALUES (:userId, :productId, :quantity, :price, :name)";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt2->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt2->bindParam(':quantity', $quantity, PDO::PARAM_INT);
    $stmt2->bindParam(':price', $price, PDO::PARAM_INT);
    $stmt2->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt2->execute();



    header("Location: ../main/mainPage.php");
    exit();




}

?>