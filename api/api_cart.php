<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/item_class.php');
  require_once(__DIR__ . '/../database/cart_class.php');

  if ($session->isLoggedIn()){
    $db = getDatabaseConnection();
    if (isset($_GET['add'])){
        $item_id = intval(urldecode($_GET['add']));
        if (Cart::inCart($db, $session->getId(), $item_id)) {
          $session->addAlertMessage('Error', 'Error: Item already in the cart.');
          exit();
        }
        $item = Item::getItem($db, $item_id);
        Cart::addToCart($db, $session->getId(), intval(urldecode($_GET['add'])));
        header("Content-Type: application/json");
        echo json_encode($item);
        exit();
    }
    if (isset($_POST['remove'])){
        $item_id = intval(urldecode($_POST['remove']));
        if (!Cart::inCart($db, $session->getId(), $item_id)) {
          $session->addAlertMessage('Error', 'Error: Item is not in the cart.');
          exit();
        }
        Cart::removeFromCart($db, $session->getId(), $item_id);
        exit();
    }
  }
?>