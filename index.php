<?php

session_start();

$user_email = "";
$notes = "<p>You have no saved notes</p>";

if (array_key_exists("id", $_COOKIE)) {

    $_SESSION["id"] = $_COOKIE["id"];

}

if (array_key_exists("id", $_SESSION)) {

    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

    if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        die ("There was an error connecting to the database");
    }

    $query = "SELECT * FROM users WHERE id = '".$_SESSION["id"]."'";
    $result = mysqli_query($link, $query);
    $row = mysqli_fetch_array($result);
    $user_email = $row["email"];

    // grab previous posts and echo under previousNotesHeader
    $query = "SELECT * FROM notes WHERE userid = '".$_SESSION["id"]."'";
    if ($result = mysqli_query($link, $query)) {

        $notes = "";

        while ($row = mysqli_fetch_array($result)) {

            // print_r($row);
            $note_id = $row["id"];
            $note_content = $row["content"];
            $note_created = $row["created"];
            $note_lastupdated = $row["lastupdated"];
            $note = '
            
            <article data-noteID="' . $note_id .'">
            <div class="d-flex align-items-center">
            <small>' . $note_created . ' <span class="badge badge-secondary saving">Saving...</span><span
                class="badge badge-success saved">Changes
                saved!</span><span class="badge badge-danger save-failed">Save
                failed!</span><span class="badge badge-secondary deleting">Deleting...</span><span
                class="badge badge-success deleted">Note deleted!</span><span class="badge badge-danger delete-failed">Delete
                failed!</span></small>
                <button class="btn btn-outline-danger btn-sm ml-auto deleteNoteBtn visible" id="testDeleteBtn">Delete note</button>
            </div>
            <div class="form-control mt-1 mb-3 text-left noteInputField" contenteditable="true">' . $note_content . '</div>
        </article>
            
            ';

            $notes .= $note;

        }
        
    };

} else {

    header("Location: login.php");

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
    <title>Mind Garden</title>
</head>

<body id="index-body">

    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Mind Garden</a>
            <span class="navbar-text mr-auto">Logged in - <?php echo $user_email; ?></span>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse ml-auto" id="navbarSupportedContent">
                <form class="form-inline my-2 my-lg-0 mr-2">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
                <form class="form-inline" method="post" action="login.php">
                    <button type="submit" class="btn btn-outline-danger" id="logout-btn" name="logout" value="1">Log
                        out</button>
                </form>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
        <!-- <div class="row"> -->
            <section class="col col-md-10 col-lg-8 mx-auto text-center notes-container" id="newNoteSection">
                <h4 id="newNoteHeader">New note</h4>
                <article data-noteID="9999">
                    <div class="d-flex align-items-center">
                    <small>2020/05/21 - 16:12 <span class="badge badge-secondary saving">Saving...</span><span
                        class="badge badge-success saved">Changes
                        saved!</span><span class="badge badge-danger save-failed">Save
                        failed!</span><span class="badge badge-secondary deleting">Deleting...</span><span
                        class="badge badge-success deleted">Note deleted!</span><span class="badge badge-danger delete-failed">Delete
                        failed!</span></small>
                        <button class="btn btn-primary btn-sm ml-auto archiveNoteBtn visible">Archive note</button>
                    </div>
                    <div class="form-control mt-1 mb-3 text-left noteInputField" id="new-note-field" contenteditable="true"></div>
                </article>
            </section>
            <section class="col col-md-10 col-lg-8 mx-auto text-center notes-container" id="previousNotesSection">
                <h4 id="previousNotesHeader">Previous notes</h4>
                <article data-noteID="5">
                    <div class="d-flex align-items-center">
                    <small>2020/05/21 - 16:12 <span class="badge badge-secondary saving">Saving...</span><span
                        class="badge badge-success saved">Changes
                        saved!</span><span class="badge badge-danger save-failed">Save
                        failed!</span><span class="badge badge-secondary deleting">Deleting...</span><span
                        class="badge badge-success deleted">Note deleted!</span><span class="badge badge-danger delete-failed">Delete
                        failed!</span></small>
                        <button class="btn btn-outline-danger btn-sm ml-auto deleteNoteBtn visible" id="testDeleteBtn">Delete note</button>
                    </div>
                    <div class="form-control mt-1 mb-3 text-left noteInputField" contenteditable="true">Today I am so PMS-y
                    it's
                    moronic. I had to punch my teddy bear over 13 times just to get the image of Tiffani and Jeremiah
                    trying out for the HOCKEY TEAM out of my head. They were my best friends. Now I only harbor malice
                    towards them. I don't need this baloney, I have too much homework to do to deal with that. Right now
                    I'm listening to The New Kids on the Block and all it's doing is making me more PMS-y. Jeremiah can
                    go die for all I care. I feel like I am completely alone, and dressed only in my punkest expression.
                    I'm gonna IM Tracie and see if she wants to get a milkshake before I am forced to eat another
                    cheesecake.</div>
                </article>
                <article data-noteID="7">
                    <div class="d-flex align-items-center">
                    <small>2020/05/21 - 16:12 <span class="badge badge-secondary saving">Saving...</span><span
                        class="badge badge-success saved">Changes
                        saved!</span><span class="badge badge-danger save-failed">Save
                        failed!</span><span class="badge badge-secondary deleting">Deleting...</span><span
                        class="badge badge-success deleted">Note deleted!</span><span class="badge badge-danger delete-failed">Delete
                        failed!</span></small>
                        <button class="btn btn-outline-danger btn-sm ml-auto deleteNoteBtn visible">Delete note</button>
                    </div>
                    <div class="form-control mt-1 mb-3 text-left noteInputField" contenteditable="true">Today I am so PMS-y
                    it's
                    moronic. I had to punch my teddy bear over 13 times just to get the image of Tiffani and Jeremiah
                    trying out for the HOCKEY TEAM out of my head. They were my best friends. Now I only harbor malice
                    towards them. I don't need this baloney, I have too much homework to do to deal with that. Right now
                    I'm listening to The New Kids on the Block and all it's doing is making me more PMS-y. Jeremiah can
                    go die for all I care. I feel like I am completely alone, and dressed only in my punkest expression.
                    I'm gonna IM Tracie and see if she wants to get a milkshake before I am forced to eat another
                    cheesecake.</div>
                </article>
                
                <?php
                    echo $notes;
                ?>
            </section>
            <!-- <button class="btn btn-secondary d-block mx-auto mb-5">Load more notes</button> -->
        <!-- </div> -->
        
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        let userID = <?php echo $_SESSION["id"]; ?>; 
    </script>
    <script src="script.js"></script>
</body>

</html>