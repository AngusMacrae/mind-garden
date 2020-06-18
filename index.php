<?php

session_start();

if (array_key_exists("id", $_COOKIE)) {
    $_SESSION["id"] = $_COOKIE["id"];
}

if (isset($_SESSION["id"])) {

    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

    if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        die ("There was an error connecting to the database");
    }

    // get user email
    $query = "SELECT email FROM users WHERE id = '".$_SESSION["id"]."'";
    $result = mysqli_query($link, $query);
    $user_email = mysqli_fetch_array($result)[0];

    // get previous posts 
    $query = "SELECT * FROM notes WHERE userid = '".$_SESSION["id"]."' ORDER BY id DESC";
    if ($result = mysqli_query($link, $query)) {

        $row = mysqli_fetch_array($result);
        $first_note = $row;

        $index = 0;
        while ($row = mysqli_fetch_array($result)) {
            $previous_notes[$index] = $row;
            $index += 1;
        }
        
    }

    mysqli_close($link);

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
    <link rel="stylesheet" href="css/index-styles.css">
    <link rel="shortcut icon" href="images/yin-yang.svg" type="image/x-icon">
    <title>Mind Garden</title>
</head>

<body class="text-light">

    <nav class="navbar fixed-top navbar-light bg-light no-shadow">
        <div class="container">
            <a class="navbar-brand" href="#"><img src="images/yin-yang.svg" class="yin-yang-logo mr-3">Mind Garden</a>
            <span class="navbar-text mr-auto"><?php echo $user_email; ?></span>
            <form class="form-inline d-inline-flex" method="post" action="login.php">
                <button type="submit" class="btn btn-outline-danger" name="action" value="logout">Log out</button>
            </form>
            </div>
        </div>
    </nav>

    <main role="main" class="container">
            <section class="col col-md-10 col-lg-8 mx-auto p-0 text-center">
                <h4>New note</h4>

                <article data-noteID="<?php echo $first_note["id"]; ?>" class="note">
                    <div class="d-flex align-items-center">
                        <small class="last-changed mr-2 d-none">Last changed <span class="last-changed-field"><?php echo $first_note["lastupdated"]; ?></span></small>
                        <small class="alerts-container">
                            <span class="badge badge-secondary saving">Saving...</span>
                            <span class="badge badge-success saved">Changes saved!</span>
                            <span class="badge badge-danger save-failed">Save failed!</span>
                            <span class="badge badge-secondary archiving">Archiving...</span>
                            <span class="badge badge-success archived">Note archived!</span>
                            <span class="badge badge-danger archive-failed">Archive failed!</span>
                        </small>
                        <button class="btn btn-primary btn-sm ml-auto archive-note-btn">Archive note</button>
                    </div>
                    <textarea class="form-control mt-1 mb-3 p-2 text-left text-light"><?php echo $first_note["content"]; ?></textarea>
                </article>

            </section>
            <section class="col col-md-10 col-lg-8 mx-auto p-0 text-center">
                <h4>Archived notes</h4>

                <?php foreach($previous_notes as $note): ?>
                <article data-noteID="<?php echo $note["id"]; ?>" class="note">
                    <div class="d-flex align-items-center">
                        <small class="last-changed mr-2 d-block">Last changed <span class="last-changed-field"><?php echo $note["lastupdated"]; ?></span></small>
                        <small class="alerts-container">
                            <span class="badge badge-secondary saving">Saving...</span>
                            <span class="badge badge-success saved">Changes saved!</span>
                            <span class="badge badge-danger save-failed">Save failed!</span>
                            <span class="badge badge-secondary deleting">Deleting...</span>
                            <span class="badge badge-success deleted">Note deleted!</span>
                            <span class="badge badge-danger delete-failed">Delete failed!</span>
                        </small>
                        <button class="btn btn-danger btn-sm ml-auto delete-note-btn">Delete note</button>
                    </div>
                    <textarea class="form-control mt-1 mb-3 p-2 text-left text-light"><?php echo $note["content"]; ?></textarea>
                </article>
                <?php endforeach; ?>

                <p id="no-archived-alert">You have no archived notes</p>

            </section>
            <!-- <button class="btn btn-secondary d-block mx-auto mb-5">Load more notes</button> -->
        
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
    <script src="js/moment.min.js"></script>
    <script src="js/script.js"></script>
</body>

</html>