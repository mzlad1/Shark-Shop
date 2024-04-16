<?php
session_start();
include '../Signin-up/dp.php';
$pdo = db_connect();

$error_message = "";
$success_message = "";
$redirectUrl = "";


if (isset($_GET['redirect'])) {
    $redirectUrl = $_GET['redirect'];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


    $stmt = $pdo->prepare("SELECT * FROM emp WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            $_SESSION['usernames'] = $user['username'];
            header("Location: ../main/mainPage.php");
            exit();
        } else {
            $error_message = "Password incorrect.";
        }
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: " . (!empty($redirectUrl) ? $redirectUrl : '../main/mainPage.php'));
                exit();
            } else {
                $error_message = "Password incorrect.";
            }
        } else {
            $error_message = "Username does not exist.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Signin</title>
    <link rel="stylesheet" type="text/css" href="login.css" />
  </head>
  <body>
  <div class="center-pane">
    <div class="background-box">
        <div class="account-form">
            <h2>Signin</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>?redirect=<?php echo urlencode($redirectUrl); ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required />

                <?php if (!empty($error_message)): ?>
                    <p class="error"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <p class="success"><?php echo $success_message; ?></p>
                <?php endif; ?>

                <button type="submit">Login</button>
                <a href="step1reg.php"><br>Don't have an account?</a>
            </form>
        </div>
    </div>
</div>
  </body>
</html>