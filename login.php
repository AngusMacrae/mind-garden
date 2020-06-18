<?php

session_start();

if (isset($_POST["action"]) && $_POST["action"] == "logout") {

    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";
    unset($_SESSION);
    session_destroy();
    $alertString = createAlert("success", "You have been logged out.");
    
} else if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["action"])) {

    $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

    if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: ".mysqli_connect_error();
        die ("There was an error connecting to the database");
    }

    $email = $_POST["email"];
    $password = $_POST["password"];
    $remain = isset($_POST["remain"]) ? true : false;
    $action = $_POST["action"];

    if ($action == "signup") {

        if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {
            $alertString = createAlert("warning", "The email address you entered is already signed up! Try logging in instead.");
        } else {
            signup($link, $email, $password, $remain);
        }

    } else if ($action == "login") {

        if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {

            $row = mysqli_fetch_array($result);

            if (password_verify($password, $row["pwdhash"])) {
                login($row["id"], $remain);
            } else {
                $alertString = createAlert("danger", "Incorrect password.");
            }

        } else {
            $alertString = createAlert("warning", "It seems you aren't signed up yet. You can't login until you're signed up first!");
        }

    }

}

function selectUser($link, $email) {
    $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $email)."'";
    return mysqli_query($link, $query);
}

function signup($link, $email, $password, $remain) {
    $query = "INSERT INTO users (email, pwdhash) VALUES ('".mysqli_real_escape_string($link, $email)."', '".password_hash($password, PASSWORD_DEFAULT)."')";
    mysqli_query($link, $query);
    $newUserID = mysqli_insert_id($link);

    $query = "INSERT INTO notes (userid, content, created, lastupdated) VALUES (".$newUserID.", '', '', '')";
    mysqli_query($link, $query);

    login($newUserID, $remain);
}

function login($id, $remain) {
    $_SESSION["id"] = $id;

    if ($remain) {
        setcookie("id", $id, time() + 60*60*24);
    }

    header("Location: index.php");
}

function createAlert($type, $message) {
    // $type can be warning, danger or success
    return "<div class='alert alert-".$type." col-md-8 col-lg-6 my-4 mx-auto no-shadow'>".$message."</div>";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mind Garden</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/yin-yang.svg" type="image/x-icon">
    <link rel="stylesheet" href="css/login-styles.css">
</head>

<body class="text-light">

    <div class="container">
        <main class="row align-items-center">
            <div class="col text-center">
                <h1><img src="images/yin-yang.svg" class="yin-yang-logo mr-2">Mind Garden</h1>
                <p><strong>Keeping a diary? Writing a novel? Or just need a place to organise your thoughts?</strong></p>
                <p><strong>Use Mind Garden.</strong></p>
                <p>Sign up for free now.</p>
                <div class="form-container col-12 col-md-6 col-lg-4 mx-auto">
                    <form method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Your email address" required
                                value="<?php echo $email; ?>">
                            <small class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password"
                                placeholder="Your password" required value="<?php echo $password; ?>">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="remain" name="remain"
                                <?php echo ($remain) ? "checked" : ""; ?>>
                            <label class="form-check-label" for="remain">Stay logged in</label>
                        </div>
                        <button type="submit" class="btn btn-secondary" name="action" value="login">Log in</button>
                        <button type="submit" class="btn btn-primary" name="action" value="signup">Sign Up!</button>
                    </form>
                </div>
                <?php echo $alertString; ?>
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>