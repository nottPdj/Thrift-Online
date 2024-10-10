<?php
  declare(strict_types = 1);

  class User {
    public static string $error;
    public int $id;
    public string $name;
    public string $username;
    public string $email;
    public string $image_path;

    public function __construct(int $id, string $name, string $username, string $email, string $image_path)
    {
      $this->id = $id;
      $this->name = $name;
      $this->username = $username;
      $this->email = $email;
      $this->image_path = $image_path;
    }


    static function checkUserPassword(PDO $db, string $username, string $password) : ?User {
      $stmt = $db->prepare('
        SELECT id, name, username, email, password, image_path
        FROM User 
        WHERE lower(username) = ?
        ');

      $stmt->execute(array($username));
      $user = $stmt->fetch();
  
      if (password_verify($password, $user['password'])) {
        return new User(
          $user['id'],
          $user['name'],
          $user['username'],
          $user['email'],
          $user['image_path']
        );
      } 
      else {
        return null;
      }
    }

    static function getUser(PDO $db, int $id) : ?User {
      $stmt = $db->prepare('
        SELECT id, name, username, email, image_path
        FROM User 
        WHERE id = ?
        ');

      $stmt->execute(array($id));
      $user = $stmt->fetch();
      
      if ($user === null) {
        return null;
      }

      return new User(
        $user['id'],
        $user['name'],
        $user['username'],
        $user['email'],
        $user['image_path']
      );
    }

    static function getIdByUsername(PDO $db, string $username) : ?int {
      $stmt = $db->prepare('
        SELECT id
        FROM User 
        WHERE username = ?
        ');

      $stmt->execute(array($username));
      $id = $stmt->fetch();
      
      if ($id === null) {
        return null;
      }

      return intval($id['id']);
      
    }

    static function usernameExists(PDO $db, string $username){
        $stmt = $db->prepare('
        SELECT username
        FROM User 
        WHERE lower(username) = ?
      ');
      $stmt->execute(array($username));
      if ($stmt->fetch()){
        return true;
      }
      return false;
    }

    static function emailExists(PDO $db, string $email){
        $stmt = $db->prepare('
        SELECT email
        FROM User 
        WHERE lower(email) = ?
      ');
      $stmt->execute(array($email));
      if ($stmt->fetch()){
        return true;
      }
      return false;
    }

    static function addUser(PDO $db, string $username, string $name ,string $password, string $email) : ?User{

        $stmt = $db->prepare('
        INSERT INTO User (name, username, password, email, image_path, admin)
        VALUES(?, ?, ?, ?, "default.png", 0)
        ');

        $stmt->execute(array(
          preg_replace("/[^a-zA-Z\s]/", '',$name), 
          preg_replace("/[^a-zA-Z0-9_]/", '',$username), 
          password_hash($password, PASSWORD_BCRYPT) ,
          preg_replace("/[^a-zA-Z0-9@_.]/", '', $email)));

        $id = intval($db->lastInsertId());

        return new User(
            $id,
            $name,
            $username,
            $email,
            "default.png"
          );
    }

    static function editProfile(PDO $db, int $id, string $name, string $username, string $email, string $image_path){
      $stmt = $db->prepare('
      UPDATE User SET name = ?, username = ?, email = ?, image_path = ?
      WHERE id = ?
    ');

      $stmt->execute(array(
        preg_replace("/[^a-zA-Z\s]/", '',$name), 
        preg_replace("/[^a-zA-Z0-9_]/", '',$username), 
        preg_replace("/[^a-zA-Z0-9@_.]/", '', $email),
        $image_path,
        $id));

    }

    static function changePassword(PDO $db, int $id, string $password){
      $stmt = $db->prepare('
      UPDATE User SET password = ?
      WHERE id = ?
    ');

      $stmt->execute(array(password_hash($password, PASSWORD_BCRYPT), $id));

    }

    static function setAdmin(PDO $db, int $target_id, bool $on_off){
      $stmt = $db->prepare('
      UPDATE User SET admin = ?
      WHERE id = ?
    ');

      $stmt->execute(array($on_off, $target_id));

    }

    static function isAdmin(PDO $db, int $id){
      $stmt = $db->prepare('
      SELECT admin
      FROM User 
      WHERE id = ? and admin = 1
    ');
    $stmt->execute(array($id));
    if ($stmt->fetch()){
      return true;
    }
    return false;
  }

  static function deleteUser(PDO $db, int $id){
    $stmt = $db->prepare('
    DELETE FROM User 
    WHERE id = ?
  ');

    $stmt->execute(array($id));

  }



  }
?>