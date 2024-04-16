<?php
session_start();
ob_start();
?>

<?php
if (!isset($_SESSION['usernames'])) {
    header('Location: ../Signin-up/signin.php');
    exit();
}
include ('../Signin-up/dp.php');
include ('../layouts/header.php');
include ('../layouts/nav.php');

echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
echo "<link rel='stylesheet' href='../product/empDetails.css'>";
$pdo = db_connect(); 


if(isset($_GET['id'])){
    $orderId = $_GET['id'];
} elseif (isset($_POST['id'])) {
    $orderId = $_POST['id'];
} else {
    header("Location: viewOrdersEmp.php"); 
    exit;
}


$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :orderId");
$stmt->execute(['orderId' => $orderId]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found.";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $order['status'] !== 'shipped' && isset($_POST['process'])) {
    $status = $_POST['status'];
    $dshipping = $_POST['dshipping'] ?? null;
    if(!empty($dshipping) && $status === 'Waiting for processing'){
        echo "Shipping date cannot be set for orders that are not shipped.";
        exit;
    }
    $updateStmt = $pdo->prepare("UPDATE orders SET status = :status, dshipping = :dshipping WHERE id = :orderId");
    $updateStmt->execute(['status' => $status, 'dshipping' => $dshipping, 'orderId' => $orderId]);
    header("Location: empOrderDetails.php"); 
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

</head>
<body>
<main class="mainClass"> 

<h2>Order Details</h2>
<p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
<p>Customer Name: <?php echo htmlspecialchars($order['name']); ?></p>
<p>Address: <?php echo htmlspecialchars($order['address']); ?></p>
<p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
<p>Telephone: <?php echo htmlspecialchars($order['tele']); ?></p>
<p>Order Date: <?php echo htmlspecialchars($order['dorder']); ?></p>
<p>Total Amount: $<?php echo htmlspecialchars($order['total']); ?></p>
<p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
<p>Shipping Date: <?php echo htmlspecialchars($order['dshipping'] ?? 'Not set'); ?></p>


<?php if ($order['status'] !== 'shipped'): ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($orderId); ?>">
        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="Waiting for processing" <?php echo $order['status'] === 'Waiting for processing' ? 'selected' : ''; ?>>Waiting for Processing</option>
            <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
        </select>
        <br>
        <label for="dshipping">Shipping Date:</label>
        <input type="date" name="dshipping" id="dshipping" value="<?php echo htmlspecialchars($order['dshipping'] ?? ''); ?>" required>
        <br>
        <input type="submit" value="Process Order" name="process">
    </form>
<?php else: ?>
    <p>This order has already been shipped and cannot be edited.</p>
<?php endif; ?>
</main>
<?php include ('../layouts/footer.php');

?>
</body>
</html>

<?php
ob_end_flush();
?>
