<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="nav.css" />
</head>

<body>

<nav class="nav">
        <ul>
            <?php
            if (isset($_SESSION['usernames'])) {
                
                echo '<li><a href="../main/mainPage.php">Main Page</a></li>';
                echo '<li><a href="../product/addProduct.php">Add Product</a></li>';
                echo '<li><a href="../product/updateProduct.php">Update Quantity</a></li>';
                echo '<li><a href="../main/addEmp.php">Add employee</a></li>';
                echo '<li><a href="../product/viewOrdersEmp.php">View Orders</a></li>';
            } elseif (isset($_SESSION['username'])) {
                
                echo '<li><a href="../main/mainPage.php">Main Page</a></li>';
                echo '<li><a href="../product/search.php">Search a product</a></li>';
                echo '<li><a href="../product/viewOrders.php">View Orders</a></li>';
            } else {
                
                echo '<li><a href="../main/mainPage.php">Main Page</a></li>';
                echo '<li><a href="../product/search.php">Search a product</a></li>';
            }
            ?>
        </ul>
    </nav>


</body>

</html>