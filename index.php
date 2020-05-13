<?php

    session_start();

    $user_email = "";

    if (array_key_exists("id", $_COOKIE)) {
        
        $_SESSION["id"] = $_COOKIE["id"];
        
    }

    if (array_key_exists("id", $_SESSION)) {
        
        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");
        
        if (mysqli_connect_error()) {
            echo "Failed to connect to MySQL: ".mysqli_connect_error();
            die ("There was an error connecting to the database");
        }
        
        // retrieve previous diary entries from database and set them to variables which will be displayed below in the HTML
        $query = "SELECT * FROM users WHERE id = '".$_SESSION["id"]."'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_array($result);
        $user_email = $row["email"];
        
    } else {
        
        header("Location: login.php");
        
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secret Diary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body id="index-body">
    <nav class="navbar fixed-top navbar-dark bg-dark">
        <div class="container col-md-7 justify-content-start">
            <a class="navbar-brand">Secret Diary</a>
            <span class="navbar-text mr-auto">Logged in - <?php echo $user_email; ?></span>
            <form class="form-inline">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <span class="navbar-text mx-sm-2">|</span>
            <form method="post" action="login.php">
                <button type="submit" class="btn btn-outline-danger" id="logout-btn" name="logout" value="1">Log out</button>
            </form>
        </div>
    </nav>
    <main role="main" class="container col-md-7">
        <div class="row">
            <div class="col" id="diary-page">
                <form method="post" class="form-inline">
                    <div class="form-group">
                        <label for="new-note-field">New note</label>
                        <textarea class="form-control" id="new-note-field" name="note"></textarea>
                    </div>
                </form>
                <h3>Previous entries</h3>
                <textarea></textarea>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/script_index.js"></script>
</body>

</html>
