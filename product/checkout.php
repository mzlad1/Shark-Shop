<?php
session_start();
ob_start();
?>

<?php
if (!isset($_SESSION['username'])) {
  header('Location: ../Signin-up/signin.php');
  exit();
}
include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';

$pdo = db_connect();
$userId = $_SESSION['user_id'] ?? null;
$total = 0;
$status = "Waiting for processing";

function generateUniqueOrderId($pdo) {
    do {
        $orderId = mt_rand(1000000000, 9999999999);
        $query = "SELECT COUNT(*) FROM orders WHERE id = :orderId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    } while ($count > 0);
    return $orderId;
}




if (isset($_POST['update'])) {
    $totalCost = calculateTotalCost($pdo, $userId);
    $orderId = generateUniqueOrderId($pdo);
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ccnumber = $_POST['ccnumber'];
    $expdate = $_POST['expdate'];
    $ccname = $_POST['ccname'];
    $bank = $_POST['bank'];

    $date = date("Y-m-d");

    $stmt = $pdo->prepare("INSERT INTO orders (customerId, dorder, name, address, email, tele,total,status,id) VALUES (:userId, :date, :name, :address, :email, :phone, :total, :status, :orderId)");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':address', $address, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':total', $totalCost, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
    $stmt->execute();


    $_SESSION['orderId'] = $orderId;


    $stmt = $pdo->prepare("SELECT * FROM cart WHERE customerId = :userId");
    $stmt->execute(['userId' => $userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);


    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("INSERT INTO orderdetails (orderId, productId, quantity, price, name) VALUES (:orderId, :productId, :quantity, :price, :name)");
        $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
        $stmt->bindParam(':productId', $item['productId'], PDO::PARAM_INT);
        $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':price', $item['price'], PDO::PARAM_INT);
        $stmt->bindParam(':name', $item['name'], PDO::PARAM_STR);
        $stmt->execute();
    }


    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("UPDATE products SET quantity = quantity - :quantity WHERE id = :productId");
        $stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
        $stmt->bindParam(':productId', $item['productId'], PDO::PARAM_INT);
        $stmt->execute();
    }


    $stmt = $pdo->prepare("DELETE FROM cart WHERE customerId = :userId");
    $stmt->execute(['userId' => $userId]);

    header("Location: ../product/wlcOrder.php");
    ob_end_flush();
    exit();
}

function calculateTotalCost($pdo, $userId) {
  $stmt = $pdo->prepare("SELECT * FROM cart WHERE customerId = :userId");
  $stmt->execute(['userId' => $userId]);
  $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalCost = 0;
  foreach ($cartItems as $item) {
      $totalCost += $item['quantity'] * $item['price'];
  }
  return $totalCost;
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :userId");
$stmt->execute(['userId' => $userId]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Checkout</title>
    <link rel="stylesheet" type="text/css" href="../product/checkoutStyle.css" />
    <link rel="stylesheet" type="text/css" href="../layouts/nav.css" />
    <link rel="stylesheet" type="text/css" href="../layouts/header.css" />
    <link rel="stylesheet" type="text/css" href="../layouts/footer.css" />
</head>
<body>
    <div class="center-pane">
      <div class="background-box">
        <div class="signup-legend">Signup</div>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <div class="pane">
            <div class="left-box">
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($customer['name'] ?? ''); ?>" required />
              <label for="address">Shipping Address:</label>
              <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($customer['address'] ?? ''); ?>" placeholder="Flat/House No, Street, City, Country" required />
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($customer['email'] ?? ''); ?>" required />
              <label for="phone">Telephone:</label>
              <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($customer['phone'] ?? ''); ?>" required />
            </div>
            <div class="right-box">
              <label for="ccnumber">Credit Card Number:</label>
              <input type="number" id="ccnumber" name="ccnumber" value="<?php echo htmlspecialchars($customer['ccnumber'] ?? ''); ?>" required />
              <label for="expdate">Expiration Date:</label>
              <input type="month" id="expdate" name="expdate" value="<?php echo htmlspecialchars($customer['expdate'] ?? ''); ?>" required />
              <label for="ccname">Name on Card:</label>
              <input type="text" id="ccname" name="ccname" value="<?php echo htmlspecialchars($customer['ccname'] ?? ''); ?>" required />
              <label for="bank">Bank Issued:</label>
              <input type="text" id="bank" name="bank" value="<?php echo htmlspecialchars($customer['bank'] ?? ''); ?>" required />
            </div>
          </div>
          <?php
          $totalCost = 0;
    if ($userId) {
        $stmt = $pdo->prepare("SELECT * FROM cart WHERE customerId = :userId");
        $stmt->execute(['userId' => $userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$cartItems) {
            echo '<p>Your cart is empty.</p>';
        } else {
            echo '<table>';
            echo '<tr><th>Product id</th><th>Product Name</th><th>Quantity</th><th>Price</th><th>Total</th></tr>';
            $totalCost = 0;
            foreach ($cartItems as $item) {
                $totalCost += $item['quantity'] * $item['price'];
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['productId']) . '</td>';
                echo '<td>' . htmlspecialchars($item['name']) . '</td>';
                echo '<form method="post" action="">';
                echo '<input type="hidden" name="cartId" value="' . $item['id'] . '">';
                echo '<td>' . htmlspecialchars($item['quantity']) . '</td>';
                echo '<td>$' . htmlspecialchars($item['price']) . '</td>';
                echo '<td>$' . (htmlspecialchars($item['quantity']) * htmlspecialchars($item['price'])) . '</td>';
                echo '</form>';
                echo '</tr>';

            }
            $total = $totalCost;
            echo '<tr>';
            echo '<th colspan="4">Total</th>';
            echo '<td>$' . (htmlspecialchars($totalCost)) . '</td>';
            echo '</table>';
        }
        
    } 
    ?>
          <button type="submit" name="update" style="margin-top: 20px;">Confirm</button>
        </form>

      </div>
    </div>
    <?php include '../layouts/footer.php'; 
    ?>
</body>
</html>
