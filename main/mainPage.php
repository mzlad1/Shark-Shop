<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link  type="text/css" rel="stylesheet" href="index.css">
    <title>Students Record</title>

</head>
<?php

include '../Signin-up/dp.php';
    include '../layouts/header.php';
    include '../layouts/nav.php';
    echo "<link rel='stylesheet' type='text/css' href='../layouts/header.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/footer.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/nav.css' />";
    
?>
<body>
    <main class="main">
    <section class="tableBoard">
    <?php 
    
    
    if (!empty($_SESSION['usernames'])){
        echo '<p>Add a new product to the shop --> <a href="../product/addProduct.php" class="add-link">Add Product</a></p>';
    }
    
    ?>
    <?php
    $pdo = db_connect();
    $sql = "SELECT img, id, name, category, price, quantity FROM products";
    $result = $pdo->query($sql);

    $x = $result->fetchAll(PDO::FETCH_ASSOC);

    
    if (count($x) > 0) {
        echo '<table>';
echo '<tr>
        <th>Product Image</th>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Category</th>
        <th>Price</th>
        <th>Quantity</th>
      </tr>';
      
foreach ($x as $row) {
    $row['quantity'] = htmlspecialchars($row['quantity']);
    $row['category'] = strtolower(htmlspecialchars($row['category']));
    
    if ($row['quantity'] == 0) {
        $quantity_class = 'out-of-stock';
        $quantity_text = "Out of Stock";
    } else {
        $quantity_class = 'in-stock';
        $quantity_text = "In Stock";
    }


    $imgParts = explode(',', $row['img']);
    $row['img'] = trim($imgParts[0]);
    echo '<tr class="' . $row['category'] . '">
            <td><img src="' ."../product/productsImg/". htmlspecialchars($row["img"]) . '" alt="Image" width="100"></td>
            <td><a href="view.php?id=' . htmlspecialchars($row["id"]) . '">' . htmlspecialchars($row["id"]) . '</a></td>
            <td>' . htmlspecialchars($row["name"]) . '</td>
            <td>' . $row["category"] . '</td>
            <td>' . htmlspecialchars($row["price"]) . '</td>
            <td class="' . $quantity_class . '">' . $quantity_text . '</td>

          </tr>';
}
echo '</table>';

    } else {
        echo "No Products Available.";
    }
    
    ?>
    </section>
    
    </main>
    <?php
    include '../layouts/footer.php';
    ?>            
                </body>
                </html>
