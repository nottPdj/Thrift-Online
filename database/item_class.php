<?php
declare(strict_types=1);

class Item
{
  public int $id;
  public float $price;
  public string $title;
  public string $description;
  public string $gender;
  public string $category;
  public string $brand;
  public string $size;
  public string $conditions;
  public string $image_path;
  public int $seller_id;

  public function __construct(int $id, float $price, string $title, string $description, string $gender, string $category, string $brand, string $size, string $conditions, string $image_path, int $seller_id)
  {
    $this->id = $id;
    $this->price = $price;
    $this->title = $title;
    $this->description = $description;
    $this->gender = $gender;
    $this->category = $category;
    $this->brand = $brand;
    $this->size = $size;
    $this->conditions = $conditions;
    $this->image_path = $image_path;
    $this->seller_id = $seller_id;
  }

  public static function addToDB(PDO $db, float $price, string $title, string $description, string $gender, string $category, string $brand, string $size, string $conditions, string $image_path, int $seller_id)
  {

    $stmt = $db->prepare('
        INSERT INTO Item (price, title, description, gender, category, brand, size, conditions, image_path, seller_id)
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ');


    $stmt->execute(array(
      $price, 
      preg_replace("/[^a-zA-Z0-9\s'.&-]/", '', $title), 
      preg_replace("/[^a-zA-Z0-9\s'.&-]/", '', $description),
      preg_replace("/[^a-zA-Z]/", '', $gender),
      preg_replace("/[^a-zA-Z0-9\s'.&-]/", '', $category),
      preg_replace("/[^a-zA-Z0-9\s'.&-]/", '', $brand),
      preg_replace("/[^a-zA-Z0-9\s]/", '', $size),
      preg_replace("/[^a-zA-Z0-9\s'.&-]/", '', $conditions), 
      $image_path, 
      $seller_id));


  }

  static function getItem(PDO $db, int $item_id): ?Item
  {
    $stmt = $db->prepare('
            SELECT *
            FROM Item
            WHERE id = ? 
            ');

    $stmt->execute(array($item_id));
    $item = $stmt->fetch();

    if ($item === null) {
      return null;
    }

    return new Item(
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

  static function getItems(PDO $db): array
  {
    $stmt = $db->prepare('
        SELECT *
        FROM Item 
        ');

    $stmt->execute(array());

    $items = array();
    while ($item = $stmt->fetch()) {
      $items[] = new Item(
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

    return $items;
  }

  static function deleteItem(PDO $db, int $id)
  {
    $stmt = $db->prepare('
    DELETE FROM Item 
    WHERE id = ?
  ');

    $stmt->execute(array($id));

  }

  static function getItemsUserInfo(PDO $db): array
  {
    $stmt = $db->prepare('
      SELECT Item.*, User.username, User.image_path as user_image_path
      FROM Item
      JOIN User ON Item.seller_id = User.id
      LEFT JOIN Purchase ON Item.id = Purchase.item_id
      WHERE Purchase.item_id IS NULL
    ');

    $stmt->execute(array());
    $items_user_info = $stmt->fetchAll();

    return $items_user_info;
  }

  static function getItemsUserInfoByUser(PDO $db, int $user_id): array
  {
    $stmt = $db->prepare('
        SELECT Item.*, User.username, User.image_path as user_image_path
        FROM Item
        JOIN User on Item.seller_id = User.id 
        LEFT JOIN Purchase ON Item.id = Purchase.item_id
        WHERE User.id = ? AND Purchase.item_id IS NULL 
        ');

    $stmt->execute(array($user_id));
    $items_user_info = $stmt->fetchAll();

    return $items_user_info;
  }

  static function addItemParameter(PDO $db, string $parameter, string $new_field): bool
  {

    $parameter = ucwords($parameter);

    if ($parameter !== 'Category' && $parameter !== 'Size' && $parameter !== 'Conditions') {
      return false;
    }

    switch ($parameter) {
      case 'Category':
        $stmt = $db->prepare('
        INSERT INTO Category (name)
        VALUES(?)
        ');
        break;
      case 'Size':
        $stmt = $db->prepare('
        INSERT INTO Size (name)
        VALUES(?)
        ');
        break;
      case 'Conditions':
        $stmt = $db->prepare('
        INSERT INTO Conditions (name)
        VALUES(?)
        ');
        break;
      
      default:
        break;
    }

    $stmt->execute(array(preg_replace("/[^a-zA-Z0-9\s&,-]/", '', $new_field)));

    return true;
  }

  static function getParameterFields(PDO $db, string $parameter): array
  {
    if ($parameter !== 'Category' && $parameter !== 'Size' && $parameter !== 'Conditions') {
      return array();
    }

    $stmt = $db->prepare("SELECT name FROM $parameter");

    $stmt->execute();

    $fields = array();
    while ($field = $stmt->fetch()) {
      $fields[] = $field['name'];
    }

    return $fields;
  }

  static function getBrands(PDO $db): array
  {
    $stmt = $db->prepare('
    SELECT DISTINCT brand
    FROM Item
    LEFT JOIN Purchase ON Item.id = Purchase.item_id
    WHERE Purchase.item_id IS NULL 
    ');

    $stmt->execute(array());

    $brands = array();
    while ($brand = $stmt->fetch()) {
      $brands[] = $brand['brand'];
    }

    return $brands;
  }


  static function searchItem(PDO $db, string $item): array
  {
    $stmt = $db->prepare(' SELECT Item.*, User.username, User.image_path as user_image_path FROM Item 
    JOIN User on Item.seller_id = User.id 
    LEFT JOIN Purchase ON Item.id = Purchase.item_id
    WHERE Purchase.item_id IS NULL AND (title LIKE ? or User.username LIKE ?)');

    $stmt->execute(array('%' . $item . '%', '%' . $item . '%'));

    $items = array();
    $items = $stmt->fetchAll();
    return $items;
  }

  private static function priceBinToBounds(string $bin): array
  {
    if (str_contains($bin, '+')) {
      $min = preg_replace('[\D]', '', $bin);
      return array($min);
    } else {
      list($min_str, $max_str) = explode('-', $bin);
      $min = preg_replace('[\D]', '', $min_str);
      $max = preg_replace('[\D]', '', $max_str);
      return array($min, $max);
    }
  }

  static function getFilteredItemsUserInfo(PDO $db, array $genders, array $categories, array $brands, array $sizes, array $conditions, string $prices): array
  {
    $query = '
      SELECT Item.*, User.username, User.image_path as user_image_path
      FROM Item
      JOIN User on Item.seller_id = User.id
      LEFT JOIN Purchase ON Item.id = Purchase.item_id
      WHERE Purchase.item_id IS NULL 
    ';
    $exec_array = array();

    if (!empty($genders)) {
      $query .= ' AND gender IN (?' . str_repeat(', ?', count($genders) - 1) . ')';

      $exec_array = array_merge($exec_array, $genders);
    }
    if (!empty($categories)) {
      $query .= ' AND category IN (?' . str_repeat(', ?', count($categories) - 1) . ')';

      $exec_array = array_merge($exec_array, $categories);
    }
    if (!empty($brands)) {
      $query .= ' AND brand IN (?' . str_repeat(', ?', count($brands) - 1) . ')';

      $exec_array = array_merge($exec_array, $brands);
    }
    if (!empty($sizes)) {
      $query .= ' AND size IN (?' . str_repeat(', ?', count($sizes) - 1) . ')';

      $exec_array = array_merge($exec_array, $sizes);
    }
    if (!empty($conditions)) {
      $query .= ' AND conditions IN (?' . str_repeat(', ?', count($conditions) - 1) . ')';

      $exec_array = array_merge($exec_array, $conditions);
    }
    if (!empty($prices)) {
      $bounds = self::priceBinToBounds($prices);

      if (count($bounds) == 1) {
        $query .= ' AND price >= ?';
      } else {
        $query .= ' AND price >= ? and price < ?';
      }

      $exec_array = array_merge($exec_array, $bounds);
    }

    $stmt = $db->prepare($query);
    $stmt->execute($exec_array);
    $items_user_info = $stmt->fetchAll();

    return $items_user_info;
  }

}

?>