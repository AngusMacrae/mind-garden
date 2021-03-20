<?php

session_start();

require_once '../../private/NotesModel.php';
$notesModel = new NotesModel;

$method = $_SERVER['REQUEST_METHOD'];

if (isset($_SESSION["id"])) {

    // if ($method == "GET") {
    //     $notes = $notesModel->getNotes($_SESSION["id"]);
    // }

    if ($method == "PUT" && isset($_GET["noteID"])) {
        $payload = json_decode(file_get_contents('php://input'));

        if (isset($payload->noteContent) && isset($payload->lastUpdated)) {
            $result = $notesModel->updateNote($_SESSION["id"], $_GET["noteID"], $payload);
            if ($result) {
                http_response_code(200);
                echo json_encode($payload);
            } else {                
                http_response_code(400);
                die('Could not update note');
            }
        } else {
            http_response_code(400);
            die('Invalid request body');
        }
    }

    if ($method == "POST") {
        $result = $notesModel->newNote($_SESSION["id"]);

        if ($result) {
            http_response_code(201);
            $response = array( 'newNoteID'=> $result);
            echo json_encode($response);
        } else {
            http_response_code(500);
            die('Could not archive note');
        }
    }

    if ($method == "DELETE"  && isset($_GET["noteID"])) {
        $result = $notesModel->deleteNote($_SESSION["id"], $_GET["noteID"]);

        if ($result) {
            http_response_code(204);
        } else {
            http_response_code(500);
            die('Could not delete note');
        }
    }

}

?>