<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  $session = new Session();

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/user_class.php');

  $db = getDatabaseConnection();

  if (!User::usernameExists($db, $_POST['username'])){
    $session->addAlertMessage('failure', 'Error: No such username.');
    header('Location: /pages/login.php?error=NoSuchUsername');
  }

  $user = User::checkUserPassword($db, $_POST['username'], $_POST['password']);

  if ($user) {
    $session->setId($user->id);
    $session->setUsername($user->username);
    header('Location: /pages/index.php');

} else {
    $session->addAlertMessage('failure', 'Error: Wrong credentials.');
    header('Location: /pages/login.php?error=WrongCredentials');
}

?>