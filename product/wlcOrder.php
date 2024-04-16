<?php
session_start();
include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";


if (isset($_SESSION['orderId'])) {
    $orderId = $_SESSION['orderId'];
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmation!</title>
    <link rel="stylesheet" href="wlcStyle.css" />
  </head>
  <body>
  <main class="mainClass"> 
    <div class="welcome-message">
      <h1>
        Thank you for shopping at Shark Shop!
      </h1>
      <p>Your Order ID is: 
        <a href="orderDetails.php?id=<?php echo urlencode($orderId); ?>">
          <?php echo htmlspecialchars($orderId); ?>
        </a>
      </p>
    </div>
  </main>
  </body>
  <?php include '../layouts/footer.php'; ?>
</html>
