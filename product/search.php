<?php
session_start();
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

if (!empty($_GET['min_price']) && !empty($_GET['max_price'])) {
    $where_clauses[] = "price BETWEEN :min_price AND :max_price";
    $params[':min_price'] = $_GET['min_price'];
    $params[':max_price'] = $_GET['max_price'];
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

?>

<main class="mainClass">
<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="get">
<div class="form-row">
    <label for="name">Product Name:</label>
    <input type="text" id="name" name="name">
    </div>

    <div class="form-row">
    <label for="min_price">Min Price:</label>
<input type="number" id="min_price" name="min_price" min="0" step="0.01">
<label for="max_price">Max Price:</label>
<input type="number" id="max_price" name="max_price" min="0" step="0.01">
<input type="submit" value="Search">
</div>

</form>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <table>
    <thead>
        <tr>
            <th><input type="submit" value="Shortlist" class="shortlist"></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=id">id</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=price">Price</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=category">Category</a></th>
            <th><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?sort=quantity">Quantity</a></th>
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
            <td><?= $product['price']; ?></td>
            <td><?= $product['category']; ?></td>
            <td><?= $product['quantity']; ?></td>
        </tr>
        <?php 
            endif;
        endforeach; 
        ?>
    </tbody>
    </table>
    
</form>
</main>
<?php include '../layouts/footer.php'; ?>