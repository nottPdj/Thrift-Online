<?php
  class Session {
    private string $error = "";
    private array $alert_msgs;

    public function __construct() {
        session_set_cookie_params(0, '/', 'localhost', false, true);
        session_start();

        $this->alert_msgs = isset($_SESSION['alert_msgs']) ? $_SESSION['alert_msgs'] : array();
        unset($_SESSION['alert_msgs']);
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['id']);    
    }

    public function logout() {
      session_destroy();
    }

    public function getId() : ?int {
      return isset($_SESSION['id']) ? $_SESSION['id'] : null;    
    }

    public function getUsername() : ?string {
      return isset($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    public function setId(int $id) {
      $_SESSION['id'] = $id;
    }

    public function setUsername(string $username) {
      $_SESSION['username'] = $username;
    }

    public function setLastError(string $error) {
      $this->error = $error;
    }
    public function getLastError() : string {
      return $this->error;
    }

    public function addAlertMessage(string $type, string $text) {
      $_SESSION['alert_msgs'][] = array('type' => $type, 'text' => $text);
    }

    public function getAlertMessages() {
      return $this->alert_msgs;
    }

  }
?>