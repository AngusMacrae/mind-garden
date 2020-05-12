<?php

    session_start();

    $alertString = "alert will appear here";

    if ($_POST) {
        
        if (array_key_exists("logout", $_POST)) {

            session_destroy();

        } else {
        
            $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

            if (mysqli_connect_error()) {
                echo "Failed to connect to MySQL: ".mysqli_connect_error();
                die ("There was an error connecting to the database");
            }

            $email = $_POST["email"];
            $password = $_POST["password"];
            // $remain = $_POST["remain"];
            $remain = true;
            $signup = $_POST["signup"];
        
            if ($signup == 1) {

                if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {

                    $alertString = "The email address you entered is already signed up! Try logging in instead.";

                } else {

                    signup($link, $email, $password);

                    $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $email)."'";
                    $result = mysqli_query($link, $query);
                    $row = mysqli_fetch_array($result);

                    login($row["id"], $remain);

                }

            } else if ($signup == 0) {

                if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {

                    $row = mysqli_fetch_array($result);

                    if (password_verify($password, $row["pwdhash"])) {

                        login($row["id"], $remain);

                    } else {

                        $alertString = "Incorrect password.";

                    }

                } else {

                    $alertString = "It seems you aren't signed up yet. You can't login until you're signed up first!";

                }

            }
            
        }

    }

    function selectUser($link, $email) {

        $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $email)."'";
        return mysqli_query($link, $query);

    }

    function signup($link, $email, $password) {

        $query = "INSERT INTO users (email, pwdhash) VALUES ('".mysqli_real_escape_string($link, $email)."', '".password_hash($password, PASSWORD_DEFAULT)."')";
        mysqli_query($link, $query);

    }

    function login($id, $remain) {

        $_SESSION["id"] = $id;

        if ($remain) {

//            setcookie("id", $row["id"], time() + 60*60*24);
        }

        header("Location: index.php");

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
        <div class="row justify-content-center">
            <div class="col col-md-8 col-xl-6" id="log-in-page">
                <h1>Secret Diary</h1>
                <span><strong>Store your thoughts permanently and securely.</strong></span>
                <span>Interested? Sign up now.</span>
                <div class="form-container" id="sign-up-form">
                    <form method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="signup-email" name="email" aria-describedby="emailHelp" placeholder="Your email" required>
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="signup-password" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="signup-stayCheck" name="remain">
                            <label class="form-check-label" for="stayCheck">Stay logged in</label>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" name="signup" value="0">Log in</button>
                        <button type="submit" class="btn btn-primary" name="signup" value="1">Sign Up!</button>
                    </form>
                </div>
                <p><?php echo $alertString; ?></p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
