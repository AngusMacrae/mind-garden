<?php

session_start();

$link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

if (mysqli_connect_error()) {
    echo "Failed to connect to MySQL: ".mysqli_connect_error();
    die ("There was an error connecting to the database");
}

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_GET["userID"]) && isset($_GET["noteID"]) && $_GET["userID"] == $_SESSION["id"]) {

    if ($method == "GET") {}

    if ($method == "PUT") {
        
        $payload = json_decode(file_get_contents('php://input'));
        // $content = strtr($payload->noteContent, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />')); 
        $content = $payload->noteContent;
        $lastupdated = $payload->lastUpdated;
        
        $query = "UPDATE notes SET content = '".mysqli_real_escape_string($link, $content)."', lastupdated = '".$lastupdated."' WHERE id = '".$_GET["noteID"]."'";
        $result = mysqli_query($link, $query);

        if(!$result) {
            http_response_code(400);
            die('Could not update data: ' . mysql_error());
        } else {
            http_response_code(200);
            $response = array( 'noteID'=> $_GET["noteID"], 'noteContent'=> $content, 'lastUpdated'=> $lastupdated);
            echo json_encode($response);
        }
        
    }

    if ($method == "POST") {

        $query = "INSERT INTO notes (userid, content, created, lastupdated) VALUES (".$_GET["userID"].", '', '', '')";
        $result = mysqli_query($link, $query);

        if(!$result) {
            http_response_code(400);
            die('Could not archive note: ' . mysql_error());
        } else {
            http_response_code(200);
            $response = array( 'noteID'=> $_GET["noteID"], 'userID'=> $_GET["userID"]);
            echo json_encode($response);
        }

    }

    if ($method == "DELETE") {

        $query = "DELETE FROM notes WHERE id = '".$_GET["noteID"]."'";
        $result = mysqli_query($link, $query);
        
        if(!$result) {
            http_response_code(400);
            echo "Note was not deleted";
            die('Could not delete data: ' . mysql_error());
        } else {
            http_response_code(204);
            echo "Note deleted";
        }

    }

}

mysqli_close($link);

?>