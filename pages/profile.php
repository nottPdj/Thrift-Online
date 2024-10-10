<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once(__DIR__ . '/../database/connection_db.php');
    require_once(__DIR__ . '/../database/user_class.php');
    require_once(__DIR__ . '/../database/item_class.php');
    require_once(__DIR__ . '/../database/cart_class.php');

    require_once(__DIR__ . '/../templates/common_tpl.php');
    require_once(__DIR__ . '/../templates/profile_tpl.php');
    require_once (__DIR__ . '/../templates/chat_tpl.php');
    require_once (__DIR__ . '/../templates/cart_tpl.php');

    $db = getDatabaseConnection();

    if ($session->isLoggedIn())
        $user = User::getUser($db, $session->getId());
    else {
        $user = null;
    }

    $profile_user = User::getUser($db, intval($_GET['user']));
    $items = Item::getItemsUserInfoByUser($db, intval($_GET['user']));
    
    $cart_items = Cart::getCartById($db, intval($user->id));

    drawHeader($session, $user);
    drawCart($cart_items);
    drawUserProfile($db, $user, $profile_user, $items);
    drawChat();
    drawFooter();

?>