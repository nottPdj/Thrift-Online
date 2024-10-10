<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/user_class.php');
  require_once(__DIR__ . '/../database/item_class.php');
  require_once(__DIR__ . '/../utils/save_image.php');


  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if ($user) {
    $image_path = saveImage($_FILES['photos']);
    Item::addToDB($db, floatval($_POST['price']), $_POST['title'], $_POST['description'], $_POST['gender'], $_POST['category'], $_POST['brand'], $_POST['size'], $_POST['conditions'], $image_path, $session->getId());
}

  header('Location: ../pages/index.php');
?>