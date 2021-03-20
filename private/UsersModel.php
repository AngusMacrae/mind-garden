<?php

require_once 'NotesModel.php';
$notesModel = new NotesModel;

class UsersModel {
  private $db_connection;

  public function __construct() {
    $this->db_connection = pg_connect("host=".getenv('DB_HOST')." dbname=".getenv('DB_NAME')." user=".getenv('DB_USER')." password=".getenv('DB_PASSWORD')) or die("Could not connect to database");
  }

  private function query($query_string) {
    return pg_query($this->db_connection, $query_string);
  }

  private function getUserByEmail($email) {
    $escaped_email = pg_escape_string($email);
    $result =  $this->query("SELECT * FROM users WHERE email = '{$escaped_email}'");
    if (pg_num_rows($result) > 0) {
      return pg_fetch_array($result, 0, PGSQL_ASSOC);
    } else {
      return null;
    }
  }

  public function getUserByID($userID) {
    $escaped_userID = pg_escape_string($userID);
    $result = $this->query("SELECT * FROM users WHERE id = '{$escaped_userID}'");
    if (pg_num_rows($result) > 0) {
      return pg_fetch_array($result, 0, PGSQL_ASSOC);
    } else {
      return null;
    }
  }

  public function signup($email, $password) {
    $user = $this->getUserByEmail($email);

    if ($user) {
      return null;
    } else {
      $escaped_email = pg_escape_string($email);
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);
      $result = $this->query("INSERT INTO users (email, pwdhash) VALUES ('{$escaped_email}', '{$hashed_password}') RETURNING id");
      $newUserID = pg_fetch_array($result, 0, PGSQL_ASSOC)['id'];
      $notesModel->newNote($newUserID);
      return $newUserID;
    }
  }

  public function login($email, $password) {
    $user = $this->getUserByEmail($email);

    if (!$user) {
      return 'NOT_SIGNED_UP';
    } else {
      if (password_verify($password, $user["pwdhash"])) {
          return $user["id"];
      } else {
          return 'INCORRECT_PASSWORD';
      }
    }
  }
}

?>