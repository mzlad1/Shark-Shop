<?php
    session_start();
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Order Management</title>
</head>
<body>
<main class="main">
    <?php
    if (!isset($_SESSION['usernames'])) {
        header('Location: ../Signin-up/signin.php');
        exit();
    }
    include '../Signin-up/dp.php'; 
    include '../layouts/header.php';
    include '../layouts/nav.php';
    echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
echo "<link rel='stylesheet' href='viewEmp.css'>";


    $pdo = db_connect();


    $stmt = $pdo->prepare("SELECT * FROM orders ORDER BY FIELD(status, 'waiting for processing') DESC, dorder ASC");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <section class="tableBoard">
        <?php
        if (!$orders) {
            echo '<p>No orders found.</p>';
        } else {
            echo '<table>';
            echo '<tr><th>Order Number</th><th>Order Date</th><th>Total Amount</th><th>Status</th></tr>';
            foreach ($orders as $order) {
                $statusClass = $order['status'] === 'Waiting for processing' ? 'boldStatus' : '';
                echo '<tr class="'.$statusClass.'">';
                echo '<td><a href="empOrderDetails.php?id=' . htmlspecialchars($order['id']) . '" target="_blank">' . htmlspecialchars($order['id']) . '</a></td>';
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
<?php
ob_end_flush();
?>
