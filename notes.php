<?php

if (key_exists("userID", $_POST)) {

    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

    if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        die ("There was an error connecting to the database");
    }

    if ($nextNoteID = $_POST["nextNoteID"]) {

        // retrieveNotes($_POST["userID"], $nextNoteID) {}

    }

    if ($noteToDelete = $_POST["deleteNoteID"]) {

        // deleteNote($noteToDelete) {}

    }

    if ($noteToUpdate = $_POST["noteToUpdate"]) {

        // call updateNote($noteToUpdate) {}

    }

}


// retrieve previous diary entries from database and set them to variables which will be displayed below in the HTML
$query = "SELECT * FROM users WHERE id = '".$_SESSION["id"]."'";
$result = mysqli_query($link, $query);
$row = mysqli_fetch_array($result);
$user_email = $row["email"];


function retrieveNotes($userID, $nextNoteID) {

    // retrieve next five (if that many exist) notes in notes table matching userID
    // echo notes as JSON - noteID, content, time created, time last updated
    // if no more notes, echo sth which the JS can see to let the user know

}

function deleteNote($noteID) {

    // delete note corresponding to $noteID

}

function updateNote($noteID) {

    // if noteID is new then create new note, otherwise update note content and time last updated

}

?>