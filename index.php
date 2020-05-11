<?php

    session_start();

    if ($_POST["email"] == "logout") {
        
        setcookie("id", "", time() - 60*60);
        header("Location: login.php");

        
    }

    if ($_COOKIE["id"])  {
        
        // retrieve user details and previous diary entries from database and set them to variables which will be displayed below in the HTML
        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "users-dbase-3133339a99","35ya:hrq'`i0","users-dbase-3133339a99");

        $query = "SELECT * FROM users WHERE id = '".$_COOKIE["id"]."'";
        
        $result = mysqli_query($link, $query);
        
        $user_email = $result["email"];
        
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

<body>

    <div class="container">
        <div class="col" id="diary-page">
            <h1>Secret Diary - <?php echo $user_email; ?></h1>
            <form method="post">
                <input type="text" name="email" value="logout">
                <button class="btn" id="log-out-btn">Log out</button>
            </form>
            <h2>New diary entry</h2>
            <textarea>enter text here</textarea>
            <h2>Previous entries</h2>
            <textarea></textarea>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
