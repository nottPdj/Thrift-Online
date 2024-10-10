<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once(__DIR__ . '/../database/connection_db.php');

    require_once(__DIR__ . '/../templates/common_tpl.php');

    $db = getDatabaseConnection();

    drawHeader($session, null);
    drawLoginForm();
    drawFooter();
?>