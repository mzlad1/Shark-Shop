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
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
echo "<link rel='stylesheet' href='viewCart.css'>";
?>
<section class="tableBoard">
<?php
    $pdo = db_connect();


    $orderId = $_GET['id'] ?? null;

    if ($orderId) {
        $stmt = $pdo->prepare("SELECT * FROM orderdetails WHERE orderId = :orderId");
        $stmt->execute(['orderId' => $orderId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$cartItems) {
            echo '<p>Your cart is empty.</p>';
        } else {


            echo '<td>OrderId = ' . htmlspecialchars($orderId) . '</td>';
            echo '<table>';
            echo '<tr><th>Product id</th><th>Product Name</th><th>Quantity</th><th>Price</th></tr>';
            $totalCost = 0;
            
            foreach ($cartItems as $item) {
                $totalCost += $item['quantity'] * $item['price'];
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['productId']) . '</td>';
                echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                echo '<form method="post" action="">';
                echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                echo '<td>$' . htmlspecialchars($item['price']) . '</td>';
                echo '</form>';
                echo '</tr>';

            }
            echo '</table>';
        }
    }
    ?>   
</section>
</main>
</body>
</html>
