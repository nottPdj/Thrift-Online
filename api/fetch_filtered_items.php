<?php
    declare(strict_types=1);

    require_once (__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once(__DIR__ . '/../database/connection_db.php');
    require_once(__DIR__ . '/../database/item_class.php');

    $db = getDatabaseConnection();

    $genders = isset($_GET['Gender']) ? (explode(',', $_GET['Gender'])) : array();
    $categories = isset($_GET['Category']) ? explode(',', $_GET['Category']) : array();
    $brands = isset($_GET['Brand']) ? explode(',', $_GET['Brand']) : array();
    $sizes = isset($_GET['Size']) ? explode(',', $_GET['Size']) : array();
    $conditions = isset($_GET['Condition']) ? explode(',', $_GET['Condition']) : array();
    $prices = isset($_GET['Price']) ? $_GET['Price'] : '';

    $items_user_info = Item::getFilteredItemsUserInfo($db, $genders, $categories, $brands, $sizes, $conditions, $prices);

    header("Content-Type: application/json");
    echo json_encode($items_user_info);
    exit();
?>
