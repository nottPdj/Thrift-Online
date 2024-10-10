<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));
  
  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/user_class.php');
  require_once(__DIR__ . '/../database/item_class.php');
  
  
  
  $db = getDatabaseConnection();
  if (!User::isAdmin($db, $session->getId()))
    die(header('Location: ../pages/index.php'));

  $user = User::getUser($db, $session->getId());

  $action = $_GET['action'];

  switch ($action) {
    case 'ban':
        User::deleteUser($db, intval($_GET['id']));
        break;
    case 'remove_item':
        Item::deleteItem($db, intval($_GET['id']));
        break;
    case 'add_parameter':
        Item::addItemParameter($db, $_GET['parameter'], $_GET['new_field']);
        break;  
    case 'set_admin':
        if ($_GET['id'] !== $session->getId())
            User::setAdmin($db, intval($_GET['id']), boolval($_GET['on_off']));
        break;
    default:
        break;
  }

  header('Location: ../pages/index.php');
?>