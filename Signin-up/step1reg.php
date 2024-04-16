<?php
session_start();
include ('dp.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['address'] = $_POST['address'];
    $_SESSION['dob'] = $_POST['dob'];
    $_SESSION['idnumber'] = $_POST['idnumber'];
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['phone'] = $_POST['phone'];
    $_SESSION['ccnumber'] = $_POST['ccnumber'];
    $_SESSION['expdate'] = $_POST['expdate'];
    $_SESSION['ccname'] = $_POST['ccname'];
    $_SESSION['bank'] = $_POST['bank'];


    header("Location: step2reg.php");
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
        <div class="signup-legend">Signup</div>
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <div class="pane">
            <div class="left-box">
              <label for="name">Name:</label>
              <input type="text" id="name" name="name" required />
              <label for="address">Address:</label>
              <input type="text" id="address" name="address" placeholder="Flat/House No, Street, City, Country" required />
              <label for="dob">Date of Birth:</label>
              <input type="date" id="dob" name="dob" required />
              <label for="idnumber">ID Number:</label>
              <input type="number" id="idnumber" name="idnumber" required />
              <label for="email">Email:</label>
              <input type="email" id="email" name="email" required />
              <label for="phone">Telephone:</label>
              <input type="tel" id="phone" name="phone" required />
            </div>
            <div class="right-box">
              <label for="ccnumber">Credit Card Number:</label>
              <input type="number" id="ccnumber" name="ccnumber" required />
              <label for="expdate">Expiration Date:</label>
              <input type="month" id="expdate" name="expdate" required />
              <label for="ccname">Name on Card:</label>
              <input type="text" id="ccname" name="ccname" required />
              <label for="bank">Bank Issued:</label>
              <input type="text" id="bank" name="bank" required />
            </div>
          </div>
          <button type="submit" style="margin-top: 20px;">Next</button>
        </form>
      </div>
    </div>
  </body>
</html>

