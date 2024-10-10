<?php
declare(strict_types=1);

class Message {

    public int $sender_id;
    public int $receiver_id;
    public string $date;
    public string $msg;

    public function __construct($sender_id, $receiver_id, $date, $msg)
    {
      $this->sender_id = $sender_id;
      $this->receiver_id = $receiver_id;
      $this->date = $date;
      $this->mag = $msg;
    }



    static function sendMessage(PDO $db, int $sender_id, int $receiver_id, string $msg){
        $stmt = $db->prepare('
        INSERT INTO Message (sender_id, receiver_id, date, msg)
        VALUES(?, ?, ?, ?)
        ');

        $stmt->execute(array($sender_id, $receiver_id, date('Y-m-d H:i:s'), $msg));

    }

    static function getConversation(PDO $db, int $self_id, int $other_id){
        $stmt = $db->prepare('
        SELECT 
            Message.*,
            User.username,
            User.image_path
        FROM Message
        JOIN User ON Message.sender_id = User.id
        WHERE (sender_id = ? AND receiver_id = ?)
              OR
              (receiver_id = ? AND sender_id = ?)
        ORDER BY date
        ');

    $stmt->execute(array($self_id, $other_id, $self_id, $other_id));

    $messages = $stmt->fetchAll();

    return $messages;
    }

    static function getUsersConversation(PDO $db, int $user_id) : array {
    $stmt = $db->prepare('
    Select last_message.* , User.username, User.image_path
    FROM
        (SELECT
            CASE
                WHEN sender_id = ? THEN receiver_id
                ELSE sender_id
            END as id,
            MAX(date) AS date,
            FIRST_VALUE(msg) OVER (PARTITION BY CASE WHEN sender_id = ? THEN receiver_id ELSE sender_id END ORDER BY date DESC) as msg
        FROM Message
        WHERE (sender_id = ? OR receiver_id = ?)
        GROUP BY
            CASE
                WHEN sender_id = ? THEN receiver_id
                ELSE sender_id
            END) as last_message
    JOIN User
    ON last_message.id = User.id
    ORDER BY date DESC
    ');

    $stmt->execute(array($user_id, $user_id, $user_id, $user_id, $user_id));
    $items_user_info = $stmt->fetchAll();

    return $items_user_info;
    }


    static function getLastMessage(PDO $db, int $user1, int $user2) : array {
        $stmt = $db->prepare('
            SELECT Message.*, User.username, User.image_path
            FROM Message JOIN User
            ON Message.sender_id = User.id
            WHERE (sender_id IN (?, ?)) OR (receiver_id IN (?, ?))
            ORDER BY date DESC
            LIMIT 1
        ');

        $stmt->execute(array($user1, $user2, $user1, $user2));
        $message = $stmt->fetch();

        return $message;
    }
}

?>