<?php
session_start();
include '../Signin-up/dp.php';
    include '../layouts/header.php';
    include '../layouts/nav.php';
    echo "<link rel='stylesheet' type='text/css' href='../layouts/header.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/footer.css' />";
    echo "<link rel='stylesheet' type='text/css' href='../layouts/nav.css' />";
    echo "<link rel='stylesheet' type='text/css' href='myProfile.css' />";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>My Profile</title>
  </head>
  <body>
    <div class="student-info">
      <img src="../imgs/logo.png" alt="Student Photo" class="student-photo" />

      <br /><br />
      The store is marketing Palestinian souvenirsfor international customers
      products such as but not limited to: handcraft (product natural , ceramic)
      and food.
    </div>
  </body>
  <?php
    include '../layouts/footer.php';?>
</html>
