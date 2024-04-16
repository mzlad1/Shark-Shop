<?php
session_start();

if (isset($_SESSION['newUserId'])) {
    $newUserId = $_SESSION['newUserId'];
    unset($_SESSION['newUserId']);
} else {
    header('Location: signup.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirmation!</title>
    <link rel="stylesheet" href="step3style.css" />
  </head>
  <body>

    <div class="welcome-message">
      <h1>
        Welcome to
        <strong class="shark-strong">SHARK</strong>
        Shop
      </h1>
      <p>Your user ID is: <?php echo htmlspecialchars($newUserId); ?></p>
    </div>

    <div class="buttons">
      <a href="./signin.php" class="welcome-buttons-login">Login Now!</a>
    </div>
  </body>
</html>
