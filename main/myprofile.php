<?php
    session_start();
    if (!isset($_SESSION['username'])) {
        header('Location: ../signin-up/signin.php');
        exit();
    }
    include '../Signin-up/dp.php';
    include '../layouts/header.php';
    include '../layouts/nav.php';
    echo "<link rel='stylesheet' type='text/css' href='../layouts/header.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/footer.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/nav.css' />";
    echo "<link rel='stylesheet' type='text/css' href='myProfile.css' />";
$pdo = db_connect();
if (isset($_SESSION['user_id'])) {
    $studentId = $_SESSION['user_id'];


    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :studentId");
    $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
    $stmt->execute();


    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($student) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>My Profile</title>
</head>
<body>
    <div class="student-info">
        <img src="../imgs/user.png" alt="Student Photo" class="student-photo">
        <h2>UserID: <?php echo htmlspecialchars($student['id']); ?><br> Name: <?php echo htmlspecialchars($student['name']); ?></h2>
    
    <br><br>
        <p style="font-weight: bold;">Contact</p>
        <p>Email: <?php echo htmlspecialchars($student['email']); ?></p><br><br>
        <p>Tel: <?php echo htmlspecialchars($student['phone']); ?></a>
        <p style="font-style: italic;">Address: <?php echo htmlspecialchars($student['address']); ?></p><br><br>
        </div>
</body>
</html>
<?php


    }
}

$pdo = null;
include '../layouts/footer.php';
?>