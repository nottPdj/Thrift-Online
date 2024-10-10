<?php
declare(strict_types=1);

require_once (__DIR__ . '/../utils/session.php');
session_set_cookie_params(0, '/', 'localhost', false, true);
$session = new Session();

require_once (__DIR__ . '/../database/connection_db.php');
require_once (__DIR__ . '/../database/user_class.php');

$db = getDatabaseConnection();

if (User::usernameExists($db, $_POST['username'])) {
  $session->addAlertMessage('failure', 'Error: Username already exists.');
  $session->setLastError("UsernameExists");
  echo "username already exists\n";
  header('Location: /pages/register.php?error=' . $session->getLastError());
} 
else if (User::emailExists($db, $_POST['email'])) {
  $session->addAlertMessage('failure', 'Error: Email already exists.');
  $session->setLastError("EmailExists");
  echo "email already exists\n";
  header('Location: /pages/register.php?error=' . $session->getLastError());
}
else {
  $user = User::addUser($db, $_POST['username'], $_POST['name'], $_POST['password'], $_POST['email']);
  $session->setId($user->id);
  $session->setUsername($user->username);
  header('Location: /pages/index.php');
}

?>