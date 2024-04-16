
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" type="text/css" href="header.css" />
</head>
<body>
    <header >
        <div class="logo">
            <img src="../imgs/logo.png" alt="Logo" class="im">
        </div>
        
        <div class="app-name">
            <h1 class="it">SHARK STORE</h1>
        </div>
        
        <nav >
            <ul>
              
                <li  ><a href="../main/aboutus.php">About Us</a></li>
                <?php
                if(empty($_SESSION['usernames']) && !empty($_SESSION['username'])){
                    echo '<li><a href="../product/viewCart.php">Cart</a></li>';
                    echo '<li><a href="../main/myprofile.php">My Profile</a></li>';
                }
                ?>
                
                <li>
                    <?php
                
                    if (!empty($_SESSION['username']) || !empty($_SESSION['usernames'])) {
                        if(isset($_SESSION['username'])){
                            $userName = $_SESSION['username'];
                        } else {
                            $userName = $_SESSION['usernames'];
                        }
                        ?>
                        <div class="user-account">
                            <span>Welcome, <?php echo $userName; ?></span>
                            
                        </div>
                        <?php
                    } else {
                        ?>
                        <a href="../Signin-up/signin.php">Log In</a>
                        <?php
                    }
                    ?>
                </li>
                <li>
                 
                    <?php
                    if (!empty($_SESSION['username']) || !empty($_SESSION['usernames'])) {
                        ?>

                <img src="../imgs/user.png" alt="User Photo" class="user">
                        <?php
                    }
                    ?>
                </li>
                <?php
                if (!empty($_SESSION['username']) || !empty($_SESSION['usernames'])) {
                    ?>
                    <li><a href="../Signin-up/logout.php">Log Out</a></li>
                    <?php
                }
                ?>
              
            </ul>
        </nav>
    </header>