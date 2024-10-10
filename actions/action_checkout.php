<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../utils/save_image.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/user_class.php');
  require_once(__DIR__ . '/../database/user_class.php');
  require_once(__DIR__ . '/../database/cart_class.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if ($user) {
//  if (makePayement())
      Cart::clearCart($db, $session->getId());
    header('Location: /pages/index.php');
  }
  else {
    header('Location: ../pages/login.php');
  }

?>
