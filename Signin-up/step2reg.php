<?php
session_start();
include 'dp.php';
$pdo = db_connect();


$error_message = "";
$success_message = "";


$name = $_SESSION['name'];
$address = $_SESSION['address'];
$dob = $_SESSION['dob'];
$idnumber = $_SESSION['idnumber'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];
$ccnumber = $_SESSION['ccnumber'];
$expdate = $_SESSION['expdate'];
$ccname = $_SESSION['ccname'];
$bank = $_SESSION['bank'];

function generateUniqueCustomerId($pdo) {
    do {
        $customerId = mt_rand(1000000000, 9999999999);
        $query = "SELECT COUNT(*) FROM users WHERE id = :customerId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $customerId;
}

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
        $customerId = generateUniqueCustomerId($pdo);
        $success_message = "Account created successfully!";

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, address, dob, idnumber, email, phone, ccnumber, expdate, ccname, bank, id, username, password) VALUES (:name, :address, :dob, :idnumber, :email, :phone, :ccnumber, :expdate, :ccname, :bank, :customerId, :username, :hashedPassword)";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':idnumber', $idnumber);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':ccnumber', $ccnumber);
        $stmt->bindParam(':expdate', $expdate);
        $stmt->bindParam(':ccname', $ccname);
        $stmt->bindParam(':bank', $bank);
        $stmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':hashedPassword', $hashedPassword);

        $stmt->execute();

        $_SESSION['newUserId'] = $customerId;
        header('Location: step3reg.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Registration Form</title>
    <link rel="stylesheet" type="text/css" href="step1Style.css" />
  </head>
  <body>
  <div class="center-pane">
    <div class="background-box">
        <div class="e-account-form">
            <h2>Create Account</h2>
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

                <button type="submit">Create Account</button>
            </form>

        </div>
    </div>
    
</div>

  </body>
</html>
