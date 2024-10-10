<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once(__DIR__ . '/../database/connection_db.php');
    require_once(__DIR__ . '/../database/user_class.php');
    require_once(__DIR__ . '/../database/item_class.php');
    require_once(__DIR__ . '/../database/cart_class.php');

    require_once(__DIR__ . '/../templates/common_tpl.php');
    require_once(__DIR__ . '/../templates/item_tpl.php');
    require_once (__DIR__ . '/../templates/chat_tpl.php');
    require_once (__DIR__ . '/../templates/cart_tpl.php');

    $db = getDatabaseConnection();

    if ($session->isLoggedIn())
        $user = User::getUser($db, $session->getId());
    else {
        $user = null;
    }
    
    $item = Item::getItem($db, intval($_GET['item']));
    $seller = User::getUser($db, $item->seller_id);

    $cart_items = Cart::getCartById($db, intval($user->id));
    $in_cart = false;
    foreach ($cart_items as $cart_item) {
        if ($cart_item->id == intval($_GET['item'])) {
            $in_cart = true;
            break;
        }
    }

    drawHeader($session, $user);
    drawCart($cart_items);
    drawItemInfo($db, $user, $seller, $item, $in_cart);
    drawChat();
    drawFooter();
    
?>