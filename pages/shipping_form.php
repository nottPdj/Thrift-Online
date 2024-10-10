<?php
    declare(strict_types=1);

    require_once (__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once (__DIR__ . '/../database/connection_db.php');
    require_once (__DIR__ . '/../database/user_class.php');

    require_once (__DIR__ . '/../templates/shipping_tpl.php');

    $db = getDatabaseConnection();

    if ($session->isLoggedIn())
        $user = User::getUser($db, $session->getId());
    else {
        $user = null;
    }

    $cart = Cart::getCartById($db, intval($user->id));

    draw_ShippingForm($session, $user, $cart);
    drawFooter();
?>