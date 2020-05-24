<?php

if ($payload = json_decode(file_get_contents('php://input'))) {

    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

    if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        die ("There was an error connecting to the database");
    }

    $operation = $payload->operation;
    $userid = $payload->userID;

    if ($operation == "delete") {

        $targetNote = $payload->targetNote;

        $query = "DELETE FROM notes WHERE id = '".$targetNote."'";
        $result = mysqli_query($link, $query);

        if(!$result) {
            die('Could not delete data: ' . mysql_error());
        }

        $response = array( 'status'=> 'ok', 'operation'=> 'delete', 'targetNote'=> $targetNote);
        
    }

    if ($operation == "update") {

        $targetNote = $payload->targetNote;

        $content = strtr($payload->noteContent, array("\r\n" => '<br />', "\r" => '<br />', "\n" => '<br />')); 
         
        // $content = $payload->noteContent;
        $lastupdated = $payload->lastUpdated;
        
        $query = "UPDATE notes SET content = '".mysqli_real_escape_string($link, $content)."', lastupdated = '".$lastupdated."' WHERE id = '".$targetNote."'";
        $result = mysqli_query($link, $query);
        if(!$result) {
            die('Could not update data: ' . mysql_error());
        }

        $response = array( 'status'=> 'ok', 'operation'=> 'update', 'targetNote'=> $targetNote);
        
    }
    
    if ($operation == "new") {

        $query = "INSERT INTO notes (userid, content, created, lastupdated) VALUES (".$userid.", '', '', '')";
        $result = mysqli_query($link, $query);
        if(!$result) {
            die('Could not create new record: ' . mysql_error());
        }

        $response = array( 'status'=> 'ok', 'operation'=> 'new');
        
    }
    
} else {
    $response = array( 'status'=> 'bad', 'message'=> 'Invalid request');
}

echo json_encode($response);

?>