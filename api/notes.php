<?php

session_start();

$link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

if (mysqli_connect_error()) {
    echo "Failed to connect to MySQL: ".mysqli_connect_error();
    die ("There was an error connecting to the database");
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_SESSION["id"])) {

    if ($method == "GET") {}

    if ($method == "PUT" && isset($_GET["noteID"])) {
        
        $payload = json_decode(file_get_contents('php://input'));

        if (isset($payload->noteContent) && isset($payload->lastUpdated)) {

            $query = "UPDATE notes SET content = '".mysqli_real_escape_string($link, $payload->noteContent)."', lastupdated = '".$payload->lastUpdated."' WHERE id = '".$_GET["noteID"]."' AND userid = '".$_SESSION["id"]."'";
            $result = mysqli_query($link, $query);

            if(!$result) {
                http_response_code(400);
                die('Could not update note: ' . mysql_error());
            } else {
                http_response_code(200);
                echo json_encode($payload);
            }

        } else {
            http_response_code(400);
            die('Invalid request body');
        }
        
    }

    if ($method == "POST") {

        $query = "INSERT INTO notes (userid, content, created, lastupdated) VALUES (".$_SESSION["id"].", '', '', '')";
        $result = mysqli_query($link, $query);

        if(!$result) {
            http_response_code(500);
            die('Could not archive note: ' . mysql_error());
        } else {
            http_response_code(201);
            $response = array( 'newNoteID'=> mysqli_insert_id($link));
            echo json_encode($response);
        }

    }

    if ($method == "DELETE"  && isset($_GET["noteID"])) {

        $query = "DELETE FROM notes WHERE id = '".$_GET["noteID"]."' AND userid = '".$_SESSION["id"]."'";
        $result = mysqli_query($link, $query);
        
        if(!$result) {
            http_response_code(400);
            die('Could not delete note: ' . mysql_error());
        } else {
            http_response_code(204);
        }

    }

}

mysqli_close($link);

?>