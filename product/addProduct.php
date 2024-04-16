<?php
session_start();
ob_start();
?>
<?php
if (!isset($_SESSION['usernames'])) {
    header('Location: ../signin-up/signin.php');
    exit();
}

include '../layouts/header.php';
include '../layouts/nav.php';
include '../Signin-up/dp.php';
echo "<link rel='stylesheet' type='text/css' href='../layouts/header.css' />";
echo "<link rel='stylesheet' type='text/css' href='../layouts/footer.css' />";
echo "<link rel='stylesheet' type='text/css' href='../layouts/nav.css' />";
$pdo = db_connect();
$msg = '';

if (isset($_GET['status'])) {
  if ($_GET['status'] == 'success') {
      $msg = "Product added successfully!";
  } elseif ($_GET['status'] == 'error') {
      $msg = "Error adding product.";
  }
}

function generateUniqueCustomerId($pdo) {
  do {
      $productId = mt_rand(1000000000, 9999999999);
      $query = "SELECT COUNT(*) FROM products WHERE id = :productId";
      $stmt = $pdo->prepare($query);
      $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
      $stmt->execute();
      $count = $stmt->fetchColumn();
  } while ($count > 0);

  return $productId;
}

function getNextImageSequenceNumber($productId, $uploadDir) {
  $existingFiles = glob($uploadDir . "item" . $productId . "img*.jpg"); 
  $highestSequence = 0;
  foreach ($existingFiles as $file) {
      preg_match('/item' . $productId . 'img(\d+)/', basename($file), $matches);
      $currentSequence = intval($matches[1]);
      if ($currentSequence > $highestSequence) {
          $highestSequence = $currentSequence;
      }
  }
  return $highestSequence + 1;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['addButton'])) {

    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];
    $remarks = $_POST['remarks'];


    $uploadDir = './productsImg/';
  
    $productId = generateUniqueCustomerId($pdo);
   
    $imgSequenceNo = getNextImageSequenceNumber($productId, $uploadDir);

   
    $imageNames = [];
   
    foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
        if ($_FILES['images']['error'][$key] == UPLOAD_ERR_OK) {
            $fileExtension = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
            $fileName = "item" . $productId . "img" . $imgSequenceNo++ . "." . $fileExtension;
            $targetFile = $uploadDir . $fileName;
            if (move_uploaded_file($tmpName, $targetFile)) {
                $imageNames[] = $fileName;
            } else {
                $msg = "Sorry, there was an error uploading your file.";
                exit();
            }
        }
    }


    $imagesString = implode(',', $imageNames);


    $sql = "INSERT INTO products (id, name, description, category, price, size, quantity, remarks, img) VALUES (:productId, :name, :description, :category, :price, :size, :quantity, :remarks, :images)";
    $stmt = $pdo->prepare($sql);
    



    $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':size', $size);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':remarks', $remarks);
    $stmt->bindParam(':images', $imagesString);


if($stmt->execute()){

    header('Location: ' . $_SERVER["PHP_SELF"] . '?status=success');
    exit();
} else {

    header('Location: ' . $_SERVER["PHP_SELF"] . '?status=error');
    exit();
}
}


?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Product Form</title>
    <link rel="stylesheet" type="text/css" href="addprodStyle.css" />
  </head>

  <body>
    <main class="mainClass"> 
    <div class="form-pane">
      <h1>Add Product</h1>
      <h2>Product Information</h2>
      <form
        action="<?php echo $_SERVER["PHP_SELF"]; ?>"
        method="post"
        enctype="multipart/form-data"
      >
        <div class="form-group">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" required />
        </div>

        <div class="form-group">
          <label for="description">Brief Description:</label>
          <textarea
            id="description"
            name="description"
            rows="4"
            required
          ></textarea>
        </div>

        <div class="form-group">
          <label for="category">Category:</label>
          <select id="category" name="category" required>
            <option value="newarrival">new arrival</option>
            <option value="onsale">on sale</option>
            <option value="featured">featured</option>
            <option value="highdemand">high demand</option>
            <option value="normal">normal</option>
          </select>
        </div>

        <div class="form-group">
          <label for="price">Price:</label>
          <input
            type="number"
            id="price"
            name="price"
            step="0.01"
            min="0"
            required
          />
        </div>

        <div class="form-group">
          <label for="size">Size:</label>
          <input type="text" id="size" name="size" required />
        </div>

        <div class="form-group">
          <label for="quantity">Quantity:</label>
          <input type="number" id="quantity" name="quantity" min="0" required />
        </div>

        <div class="form-group">
          <label for="remarks">Remarks:</label>
          <textarea id="remarks" name="remarks" rows="4" required></textarea>
        </div>

        <div class="form-group">
          <label for="images">Upload Images:</label>
          <input type="file" id="images" name="images[]" accept="image/*" multiple required />
        </div>

        <button type="submit" name="addButton">Submit Product</button>

        
        <div class="form-group">
    <?php if ($msg != ''): ?>
        <span class="error">* <?php echo $msg; ?></span>
    <?php endif; ?>
</div>
      </form>
    </div>
    </main>
  </body>
  <?php
  include '../layouts/footer.php';
  ?>
  </html>
  <?php ob_end_flush(); ?>
