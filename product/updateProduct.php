<?php
session_start();
ob_start();
?>
<?php
if (!isset($_SESSION['usernames'])) {
    header('Location: ../signin-up/signin.php');
    exit();
}
include '../Signin-up/dp.php';
include '../layouts/header.php';
include '../layouts/nav.php';
echo "<link rel='stylesheet' href='../product/search.css'>";
echo "<link rel='stylesheet' href='../layouts/nav.css'>";
echo "<link rel='stylesheet' href='../layouts/header.css'>";
echo "<link rel='stylesheet' href='../layouts/footer.css'>";
$pdo = db_connect();


$allowed_sort_columns = ['id', 'price', 'category', 'quantity', 'name'];


$sort_column = $_SESSION['sort_column'] ?? 'id';
if (isset($_GET['sort']) && in_array($_GET['sort'], $allowed_sort_columns)) {
    $sort_column = $_GET['sort'];
    $_SESSION['sort_column'] = $sort_column;
}


$where_clauses = [];
$params = [];
$shortlistedIds = [];


if (!empty($_GET['name'])) {
    $where_clauses[] = "name LIKE :name";
    $params[':name'] = '%' . $_GET['name'] . '%';
}


if (!empty($_GET['product-Id'])) {
    $where_clauses[] = "id = :id";
    $params[':id'] = $_GET['product-Id'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['shortlist'])) {
    $shortlistedIds = $_POST['shortlist'];
}

$query = "SELECT * FROM products";
if ($where_clauses) {
    $query .= " WHERE " . implode(' AND ', $where_clauses);
}
$query .= " ORDER BY {$sort_column}";

$stmt = $pdo->prepare($query);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['new_quantity'])) {
    $new_quantity = $_POST['new_quantity'];
    $id = $_POST['id'];

    $query1 = "SELECT quantity FROM products WHERE id = :id";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->bindParam(':id', $id);
    $stmt1->execute();
    $current_quantity = $stmt1->fetch(PDO::FETCH_ASSOC);
    $current_quantity = $current_quantity['quantity'];

    $new_quantity1 = $current_quantity + $new_quantity;
    $query = "UPDATE products SET quantity = :new_quantity WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':new_quantity', $new_quantity1);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: ../product/updateProduct.php");
    exit();
}

?>

<main class="mainClass">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
<div class="form-row">
    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name">
    </div>

    <div class="form-row">
    <label for="product-Id">Product ID:</label>
<input type="number" id="product-Id" name="product-Id">
<input type="submit" value="Search">
</div>

</form>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <table>
    <thead>
        <tr>
            <th><input type="submit" value="Shortlist" class="shortlist"></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=id">id</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=name">Product name</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=price">Price</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=category">Category</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=quantity">Quantity</a></th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        foreach ($results as $product):
            $product['category'] = strtolower(htmlspecialchars($product['category']));

            if (empty($shortlistedIds) || in_array($product['id'], $shortlistedIds)):
        ?>
        <tr class="<?= $product['category']; ?>">
            <td><input type="checkbox" name="shortlist[]" value="<?= $product['id']; ?>" <?= in_array($product['id'], $shortlistedIds) ? 'checked' : ''; ?>></td>
            <td><a href="../main/view.php?id=<?= $product['id']; ?>"><?= $product['id']; ?></a></td>
            <td><?= $product['name']; ?></td>
            <td><?= $product['price']; ?></td>
            <td><?= $product['category']; ?></td>
            <td><?= $product['quantity']; ?></td>
            <td>
                <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="post">
                    <input type="hidden" name="id" value="<?= $product['id']; ?>">
                    <input type="number" name="new_quantity" min="0">
                    <input type="submit" value="Update">
                </form>
            </td>
        </tr>
        <?php 
            endif;
        endforeach; 
        ?>
    </tbody>
    </table>
    
</form>
</main>
<?php include '../layouts/footer.php'; 
ob_end_flush();
?>