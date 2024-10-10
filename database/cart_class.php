<?php
  declare(strict_types = 1);

  class Cart {
    static function getCartById(PDO $db, int $user_id){
        $stmt = $db->prepare('
        SELECT Item.*
        FROM Cart JOIN Item
        ON Item.id = Cart.item_id
        WHERE user_id = ?
        ');

      $stmt->execute(array($user_id));

      $cart = array();
      while ($item = $stmt->fetch()) {
        $cart[] = new Item(
          $item['id'],
          $item['price'],
          $item['title'],
          $item['description'],
          $item['gender'],
          $item['category'],
          $item['brand'],
          $item['size'],
          $item['conditions'],
          $item['image_path'],
          $item['seller_id'],
        );
    }

    return $cart;
    }

    static function addToCart(PDO $db, int $user_id, int $item_id){
        $stmt = $db->prepare('
        INSERT INTO Cart (user_id, item_id)
        VALUES(?, ?)
        ');

        $stmt->execute(array($user_id, $item_id));

    }

    static function removeFromCart(PDO $db, int $user_id, int $item_id){
      $stmt = $db->prepare('
      DELETE FROM Cart 
      WHERE user_id = ? and item_id = ?
      ');

      $stmt->execute(array($user_id, $item_id));

  }

    static function clearCart(PDO $db, int $user_id){

      $stmt = $db->prepare('
        SELECT Item.*
        FROM Cart
        JOIN Item on Item.id = Cart.item_id
        WHERE user_id = ?
      ');

      $stmt->execute(array($user_id ));
      $cartItems = $stmt->fetchAll();

      foreach ($cartItems as $item){
        $stmt = $db->prepare('
          INSERT INTO Purchase (buyer_id, item_id)
          VALUES(?, ?)
        ');
        $stmt->execute(array($user_id, $item['id']));
      }

      $stmt = $db->prepare('
        DELETE FROM Cart 
        WHERE user_id = ?
      ');
      $stmt->execute(array($user_id));

    }

    static function inCart(PDO $db, int $user_id, int $item_id) {
        $stmt = $db->prepare('
        SELECT *
        FROM Cart
        WHERE user_id = ? and item_id = ?
      ');

      $stmt->execute(array($user_id, $item_id));
      if ($stmt->fetch()) {
          return true;
      }
      return false;
    }

  }

?>