<?php

class NotesModel {
  private $db_connection;

  public function __construct() {
    // $this->db_connection = pg_connect("host={$db_host} dbname={$db_name} user={$db_user} password={$db_password}") or die("Could not connect to database");
    $this->db_connection = pg_connect("host=".getenv('DB_HOST')." dbname=".getenv('DB_NAME')." user=".getenv('DB_USER')." password=".getenv('DB_PASSWORD')) or die("Could not connect to database");
  }

  private function query($query_string) {
    return pg_query($this->db_connection, $query_string);
  }

  public function getNotes($userID) {
    $result = $this->query("SELECT * FROM notes WHERE userid = '{$userID}' ORDER BY id DESC");

    if ($result) {
      $index = 0;
      while ($note = pg_fetch_array($result, $index, PGSQL_ASSOC)) {
          $notes[$index] = $note;
          $index += 1;
      }
      return $notes;
    } else {
      return null;
    }
  }
  
  public function updateNote($userID, $noteID, $payload) {
    $escaped_content = pg_escape_string($payload->noteContent);
    $escaped_lastupdated = pg_escape_string($payload->lastUpdated);

    $result = $this->query("UPDATE notes SET content = '{$escaped_content}', lastupdated = '{$escaped_lastupdated}' WHERE id = '{$noteID}' AND userid = '{$userID}'");

    if ($result) {
      return $noteID;
    } else {
      return null;
    }
  }
  
  public function newNote($userID) {
    // should add current data & time in the lastupdated field
    $result = $this->query("INSERT INTO notes (userid, content, created, lastupdated) VALUES ('{$userID}', '', '', '') RETURNING id");
    
    if ($result) {
      $newNoteID = pg_fetch_array($result, 0, PGSQL_ASSOC)['id'];
      return $newNoteID;
    } else {
      return null;
    }
  }
  
  public function deleteNote($userID, $noteID) {
    $result = $this->query("DELETE FROM notes WHERE id = '{$noteID}' AND userid = '{$userID}' RETURNING *");

    if ($result) {
      return $noteID;
    } else {
      return null;
    }
  }
}

?>

