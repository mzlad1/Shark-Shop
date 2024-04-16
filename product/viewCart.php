<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
</head>
<body>
<main class="main">
<?php

    if (!isset($_SESSION['username'])) {
        header('Location: ../signin-up/signin.php');
        exit();
    }
    include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';
echo "<link rel='stylesheet' href='../product/search.css'>";
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
echo "<link rel='stylesheet' href='viewCart.css'>";
?>
<section class="tableBoard">
<?php
    $pdo = db_connect();

    $userId = $_SESSION['user_id'] ?? null;


    if (isset($_POST['delete']) && isset($_POST['cartId'])) {
        $cartId = $_POST['cartId'];
        $stmt = $pdo->prepare("DELETE FROM cart WHERE id = :cartId AND customerId = :userId");
        $stmt->execute(['cartId' => $cartId, 'userId' => $userId]);
    }


    if (isset($_POST['update']) && isset($_POST['cartId']) && isset($_POST['quantity'])) {
        $cartId = $_POST['cartId'];
        $newQuantity = $_POST['quantity'];
        $stmt = $pdo->prepare("UPDATE cart SET quantity = :quantity WHERE id = :cartId AND customerId = :userId");
        $stmt->execute(['quantity' => $newQuantity, 'cartId' => $cartId, 'userId' => $userId]);
    }

    if ($userId) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE customerId = :userId");
        $stmt->execute(['userId' => $userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$cartItems) {
            echo '<p>Your cart is empty.</p>';
        } else {
            echo '<table>';
            echo '<tr><th>Product id</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th><th>Actions</th></tr>';
            $totalCost = 0;
            foreach ($cartItems as $item) {
                $totalCost += $item['quantity'] * $item['price'];
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['productId']) . '</td>';
                echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="cartId" value="' . $item['id'] . '">';
                echo '<td><input type="number" name="quantity" value="' . htmlspecialchars($item['quantity']) . '" min="1"></td>';
                echo '<td>$' . htmlspecialchars($item['price']) . '</td>';
                echo '<td>$' . (htmlspecialchars($item['quantity']) * htmlspecialchars($item['price'])) . '</td>';
                echo '<td><input type="submit" name="update" value="Update"><input type="submit" name="delete" value="Delete"></td>';
                echo '</form>';
                echo '</tr>';

            }
            echo '<tr>';
            echo '<th colspan="4">Total</th>';
            echo '<td>$' . (htmlspecialchars($totalCost)) . '</td>';
            echo '</table>';

            echo '<form method="post" action="checkout.php">';
            echo '<input type="submit" name="checkout" value="Checkout">';
            echo '</form>';

        }
    } else {
        echo '<p>Please <a href="../signin-up/signin.php">log in</a> to view your cart.</p>';
    }
    ?>
   
</section>
</main>
</body>
</html>
