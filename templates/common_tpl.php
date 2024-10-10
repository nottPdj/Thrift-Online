<?php 
  declare(strict_types = 1); 

  require_once(__DIR__ . '/../utils/session.php');
?>

<?php function drawHeader(Session $session, ?User $user) { ?>
    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" ºnt="width=device-width, initial-scale=1.0">
    <title>Thrift Online</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <script src="https://kit.fontawesome.com/23e2bceb56.js" crossorigin="anonymous"></script>
    <script src="../javascript/script.js" defer></script>
    <script src="../javascript/filter.js" defer></script>
    <script src="../javascript/cart.js" defer></script>
    <script src="../javascript/chat.js" defer></script>
</head>

<body>
    <header class="navbar">
        <section id="navtop">
            <a href="index.php" id="logo">
                <img alt="logo" src="../images/logo.png" width=300>
            </a>
            <section class="search">
                <span class="search-icon material-symbols-outlined">search</span>
                <input type="text" name="search" placeholder="Search an item...">
            </section>
            <section class="session_options">
                <?php if ($session->isLoggedIn()) {
                    drawLoggedInOptions($user);
                } else {
                    drawLoggedOutOptions();
                } ?>
            </section>
        </section>
    </section>
    <?php 
    if ($_SERVER['REQUEST_URI'] == "/pages/" or $_SERVER['REQUEST_URI'] == "/pages/index.php" )
    { ?>
    <nav id="navbottom">
    <input type="checkbox" id="hamburger"> 
        <label class="hamburger" for="hamburger"></label>   
        <ul>
            <li>
                <a href="#" id="navTitles">Men</a>
            </li>
            <li>
                <a href="#" id="navTitles">Women</a>
            </li>
            <li>
                <a href="#" id="navTitles">Kids</a>
            </li>
        </ul>
        </nav>
    <?php }
        drawAlertMessages($session);
    ?>
    </header>
<?php } ?>


<?php function drawAlertMessages(Session $session) { ?>
    <section id="alert_msgs">
      <?php foreach ($session->getAlertMessages() as $messsage) { ?>
        <article class="<?=$messsage['type']?>">
          <?=$messsage['text']?>
          <button><i class="fa-solid fa-xmark"></i></button>
        </article>
      <?php } ?>
    </section>
<?php } ?>


<?php function drawBanner() {?>
    <div class="banner_image">
     <div class="banner_text">
        <h1>Join the team</h1>
        <a href="../pages/add_item.php"> 
        <button>Sell an item</button>
        </a>
     </div>
    </div>
<?php } ?>

<?php function drawFooter() { ?>
    <footer>
        <p>Thrift Online Team 2024</p>
        <p>© All Rights Reserved </p>
     </footer>
  </body>

</html>
<?php } ?>

<?php function drawLoggedOutOptions() { ?>
    <a href="login.php"><button>Login</button></a>
    <a href="register.php"><button>Register</button></a>
<?php } ?>

<?php function drawLoggedInOptions(User $user) { ?>
        <div class="img_username">
            <img src=<?="../uploads/small_" . $user->image_path?> alt="">
            <a href=<?="../pages/profile.php?user=" . $user->id?>><h3><?=$user->username?></h3></a>
        </div>
        <button class="chat_button"><i class="fa-solid fa-message"></i></button>
        <button class="cart_button"><i class="fa-solid fa-cart-shopping"></i></button>
        <a href="../actions/action_logout.php"><button id="logoutbutton">Logout</button></a>
<?php } ?>

<?php function drawLoginForm() { ?>
    <main id="loginmain">
        <section class="Login">
            <form action="../actions/action_login.php" method="post">
                <h1>Login</h1>
                <section id="input-box">
                    <input type="text" name='username' placeholder="Username" required>
                </section>
                <section id="input-box">
                    <input type="password" name='password' placeholder="Password" required>
                </section>
                <!-- <section id="Remember">
                    <label><input type="checkbox">Remember me</label>
                </section> -->
                <button type="submit" class="login-button">Sign in</button>
                <a href="register.php">Register</a>
            </form>
        </section>        
    </main>
<?php } ?>

<?php function drawRegisterForm() { ?>
    <main id="registermain">
        <section class="Register">
            <form action="../actions/action_register.php" method="post">
                <h1>Register</h1>
                <section id="input-box">
                    <input type="text" name='name' placeholder="Name" required>
                </section>
                <section id="input-box">
                    <input type="text" name='username' placeholder="Username" required>
                </section>
                <section id="input-box">
                    <input type="email" name='email' placeholder="Email address" required>
                </section>
                <section id="input-box">
                    <input type="password" name='password' placeholder="Password" required>
                </section>
                <button type="submit" class="register-button">Register</button>
                <a href="../pages/login.php">Login</a>
            </form>
        </section>        
    </main>
<?php } ?>

