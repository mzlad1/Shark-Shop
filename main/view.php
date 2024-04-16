<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Details</title>
    <link rel="stylesheet" type="text/css" href="../layouts/header.css">
    <link rel="stylesheet" type="text/css" href="../layouts/footer.css">
    <link rel="stylesheet" type="text/css" href="../layouts/nav.css">
    <link rel="stylesheet" type="text/css" href="../main/view.css">
</head>
<body>
    <?php
include '../Signin-up/dp.php';
    include '../layouts/header.php';
    include '../layouts/nav.php';
    
    $currentUrl = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $_SESSION['currentUrl'] = $currentUrl;
    $pdo = db_connect();



    if (isset($_GET['id'])) {
        $productId = $_GET['id'];
        $_SESSION['productId'] = $productId;
        
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
        } else {
            $userId = "";
        }

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :productId");
        $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
        $stmt->execute();

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            ?>
            <main class= "mainClass">
            <div class="product-pane">
                <div class="product-details">
                    <h2><?php echo $product['name']; ?></h2>
                    <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                    <p><strong>Category:</strong> <?php echo $product['category']; ?></p>
                    <p><strong>Size:</strong> <?php echo $product['size']; ?></p>
                    <p><strong>Remarks:</strong> <?php echo $product['remarks']; ?></p>
                    <form action="../product/addToCart.php" method="post">
                    <label for="quantity"><strong>Quantity:</strong></label>
                    <input type="number" id="quantity" name="quantity" min="1" max="<?php echo $product['quantity'];?>" required ><br><br>
                    
                    <?php
                    if ($product['quantity'] == 0 || !empty($_SESSION['usernames'])) {
                        echo '<input type="submit" value="Add to Cart" class="button" disabled>';
                    } else {
                        echo '<input type="submit" value="Add to Cart" class="button">';
                    } 
                    ?>
                    
                </form>

                    <div class="product-images">
                        <?php
                        $images = explode(',', $product['img']);
                        foreach ($images as $image) {
                            echo '<img src="' ."../product/productsImg/". htmlspecialchars($image) . '" alt="Product Image">';
                        }
                        ?>
                    </div>
                    <p width = "100" height = "100"><strong>Description:</strong> <?php echo $product['description']; ?></p>
                </div>

                <div class="product-image">
                    <?php
                   $mainImage = explode(',', $product['img'])[0];
                    echo '<img src="' ."../product/productsImg/". htmlspecialchars($mainImage) . '" alt="Product Image">';
                    ?>
                </div>
            </div>
            </main>
            <?php
        } else {
            echo "Product not found.";
        }
    } else {
        echo "Product ID not specified.";
    }
    include '../layouts/footer.php';
    ?>
</body>
</html>
