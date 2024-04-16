<?php
session_start();
ob_start();
?>
<?php

include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';


echo "<link rel='stylesheet' href='addEmpStyle.css'>";
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
$pdo = db_connect();


$error_message = "";
$success_message = "";



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    $query = "SELECT COUNT(*) FROM users WHERE username = :username";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->fetchColumn();

 
        $query2 = "SELECT COUNT(*) FROM emp WHERE username = :username";
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':username', $username);
        $stmt2->execute();
        $count2 = $stmt2->fetchColumn();

    if ($count > 0 || $count2 > 0) {
        $error_message = "Username already exists.";
    } elseif (strlen($username) < 6 || strlen($username) > 13) {
        $error_message = "Username must be between 6-13 characters long.";
    } elseif (strlen($password) < 8 || strlen($password) > 12) {
        $error_message = "Password must be between 8-12 characters long.";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    }


    if (empty($error_message)) {
        $success_message = "Employee added successfully!";
        

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO emp (username, password) VALUES (:username, :hashedPassword)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hashedPassword', $hashedPassword);

        $stmt->execute();
        header("Location: ../main/addEmp.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Add Employee</title>
    <link rel="stylesheet" type="text/css" href="step1Style.css" />
  </head>
  <div class="center-pane">
    <div class="background-box">
        <div class="e-account-form">
            <h2>Add Employee</h2>
            <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" pattern=".{6,13}" title="Username must be 6-13 characters long" required />

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" pattern=".{8,12}" title="Password must be 8-12 characters long" required />

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required />

                <?php if (!empty($error_message)): ?>
                    <p class="error"><?php echo $error_message; ?></p>
                <?php endif; ?>

                <?php if (!empty($success_message)): ?>
                    <p class="success"><?php echo $success_message; ?></p>
                <?php endif; ?>

                <button type="submit">Add</button>
            </form>
            

        </div>
    </div>
    
</div>
<?php
include '../layouts/footer.php';
?>
    
</body>
</html>
<?php
ob_end_flush();
?>
