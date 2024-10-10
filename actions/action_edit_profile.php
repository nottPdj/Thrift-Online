<?php
  declare(strict_types = 1);

  require_once(__DIR__ . '/../utils/session.php');
  require_once(__DIR__ . '/../utils/save_image.php');
  $session = new Session();

  if (!$session->isLoggedIn()) die(header('Location: ../pages/login.php'));

  require_once(__DIR__ . '/../database/connection_db.php');
  require_once(__DIR__ . '/../database/user_class.php');

  $db = getDatabaseConnection();

  $user = User::getUser($db, $session->getId());

  if ($user) {
    if (User::checkUserPassword($db, $session->getUsername(), $_POST['current_password']))
    {
        $name = preg_replace("/[^a-zA-Z\s]/", '',$_POST['name']);
        $username = preg_replace("/[^a-zA-Z0-9_]/", '',$_POST['username']);
        $email = preg_replace("/[^a-zA-Z0-9_@.]/", '',$_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        

        if  (($user->username !== $username) && User::usernameExists($db, $username)){
          $session->addAlertMessage('failure', 'Error: Username already exists.');
          die(header('Location: /pages/edit_profile.php?error=UsernameExists'));
        }
        else if (($user->email !== $email) && User::emailExists($db, $email)){
          $session->addAlertMessage('failure', 'Error: Email already exists.');
          die(header('Location: /pages/edit_profile.php?error=EmailExists'));
        }
        
        if (!empty($_POST['password'])){
          if ($password !== $confirm_password){
            $session->addAlertMessage('failure', 'Error: New password and confirm new password dont match.');
            die(header('Location: ../pages/edit_profile.php?error=NewPasswordDoesntMatch'));
          }
          User::changePassword($db, $session->getId(), $password);
        }

        $image_path = !empty($_FILES['photos']['name']) ? saveImage($_FILES['photos']) : $user->image_path;
        User::editProfile($db, $session->getId(), $name, $username, $email, $image_path);
        $session->setUsername($username);

        $session->addAlertMessage('success', 'Your profile has been successfully updated.');

        header('Location: ' . $_SERVER['HTTP_REFERER']);

    }
    else {
      $session->addAlertMessage('failure', 'Error: Wrong password.');
      header('Location: ../pages/edit_profile.php?error=WrongCurrentPassword');
    }
  }
  else {
    header('Location: ../pages/login.php');
  }

?>
