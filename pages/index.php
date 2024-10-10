<?php
declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
session_set_cookie_params(0, '/', 'localhost', false, true);
$session = new Session();

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_class.php');
require_once (__DIR__ . '/../database/item_class.php');
require_once(__DIR__ . '/../database/cart_class.php');

require_once (__DIR__ . '/../templates/common_tpl.php');
require_once (__DIR__ . '/../templates/item_tpl.php');
require_once (__DIR__ . '/../templates/filter_tpl.php');
require_once (__DIR__ . '/../templates/chat_tpl.php');
require_once (__DIR__ . '/../templates/cart_tpl.php');

$db = getDatabaseConnection();

if ($session->isLoggedIn())
    $user = User::getUser($db, $session->getId());
else {
    $user = null;
}

$items_user_info = Item::getItemsUserInfo($db);
$categories = Item::getParameterFields($db, 'Category');
$brands = Item::getBrands($db);
$sizes = Item::getParameterFields($db, 'Size');
$conditions = Item::getParameterFields($db, 'Conditions');

$cart_items = Cart::getCartById($db, intval($user->id));


drawHeader($session, $user); ?>

<main id="content-wrap">


<?php
    drawBanner();
    drawCart($cart_items);
    drawFilter($categories, $brands, $sizes, $conditions);
    drawItemMiniatures($items_user_info);
    drawChat();
?>


</main>

<?php drawFooter();
?>