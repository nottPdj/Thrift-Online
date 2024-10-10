<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/item_class.php');

  $db = getDatabaseConnection();

  $items = Item::searchItem($db, $_GET['search']);

  header("Content-Type: application/json");
  echo json_encode($items);
  exit();
?>