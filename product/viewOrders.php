<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
</head>
<body>
<main class="main">
<?php
    if (!isset($_SESSION['username'])) {
        header('Location: ../signin-up/signin.php');
        exit();
    }
    include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
echo "<link rel='stylesheet' href='viewCart.css'>";
?>
<section class="tableBoard">
<?php

$pdo = db_connect();
$userId = $_SESSION['user_id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE customerId = :userId ORDER BY FIELD(status, 'waiting for processing') DESC, dorder ASC");
$stmt->execute(['userId' => $userId]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['user_id'])) {
    echo '<p>Please <a href="../signin-up/signin.php">login</a> to view your orders.</p>';
}
else if (!$orders) {
    echo '<p>You have no orders.</p>';
}else {
    echo '<table>';
    echo '<tr><th>Order id</th><th>Date<th>Total</th><th>Status</th></tr>';
    foreach ($orders as $order) {
        $statusClass = $order['status'] === 'Waiting for processing' ? 'boldStatus' : '';
        echo '<tr class="'.$statusClass.'">';
        echo '<td><a href="orderDetails.php?id=' . htmlspecialchars($order['id']) . '">'. htmlspecialchars($order['id']) .'</a></td>';
        echo '<td>' . htmlspecialchars($order['dorder']) . '</td>';
        echo '<td>$' . htmlspecialchars($order['total']) . '</td>';
        echo '<td>' . htmlspecialchars($order['status']) . '</td>';

        echo '</tr>';
    }
    echo '</table>';
}

    ?>   
</section>
</main>
<?php include '../layouts/footer.php'; ?>
</body>
</html>
