<?php
    declare(strict_types=1);

    require_once (__DIR__ . '/../utils/session.php');
      $session = new Session();

    require_once(__DIR__ . '/../database/connection_db.php');
    require_once(__DIR__ . '/../database/message_class.php');
    require_once(__DIR__ . '/../database/user_class.php');

    
    if ($session->isLoggedIn()){
        $db = getDatabaseConnection();

        if (isset($_POST['msg']) && isset($_POST['user'])) {
            Message::sendMessage($db, $session->getId(), intval(urldecode($_POST['user'])), urldecode($_POST['msg']));
            exit();
        }

        if (isset($_GET['all'])) {
            $chat = Message::getUsersConversation($db, $session->getId());
        } elseif (isset($_GET['user'])) {
            $chat = Message::getLastMessage($db, intval(urldecode($_GET['user'])), $session->getId());
        } elseif (isset($_GET['other'])) {
            $chat = Message::getConversation($db, $session->getId(), intval(urldecode($_GET['other'])));
        }
        header("Content-Type: application/json");
        echo json_encode($chat);
    }
    exit();
?>