<?php

    session_start();

    $alertString = "";

    if ($_POST) {
        
        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "users-dbase-3133339a99","35ya:hrq'`i0","users-dbase-3133339a99");

        if ($login_email = $_POST["login-email"]) {
            
            $login_pwd = $_POST["login-password"];
            
            $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $login_email)."'";
            
            $result = mysqli_query($link, $query);
            
            // check if login-email is present in database
            if (mysqli_num_rows($result) > 0 ) {
                
                if (password_verify($login_pwd, $result["pwdhash"])) {
                
                    setcookie("id", $result["id"], time() + 60*60*24);
                    header("Location: index.php");
                
                } else {
                    
                    $alertString = "Incorrect password.";
                    
                }
                
            } else {
                
                $alertString = "It seems you aren't signed up yet. You can't login until you're signed up first!";
                
            }

        } 

        if ($signup_email = $_POST["signup-email"]) {
            
            $signup_password = $_POST["signup-password"];

            $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $signup_email)."'";

            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0 ) {
                
                $alertString = "The email address you entered is already signed up! Try logging in instead";
            
            } else {
                
                // add user to database
                $query = "INSERT INTO users (email, pwdhash) VALUES ('".mysqli_real_escape_string($link, $signup_email)."', '".password_hash($signup_password, PASSWORD_DEFAULT)."')";
                mysqli_query($link, $query);
                
                $query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $signup_email)."'";

                $result = mysqli_query($link, $query);

                setcookie("id", $result["id"], time() + 60*60*24);
                header("Location: index.php");
                
            }

        }
        
    }

    if ($_COOKIE["id"]) {
        
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
        <div class="col" id="log-in-page">
            <h1>Secret Diary</h1>
            <span><strong>Store your thoughts permanently and securely.</strong></span>
            <span>Interested? Sign up now.</span>
            <div class="form-container" id="sign-up-form">
                <form method="post">
                    <div class="form-group">
                        <input type="email" class="form-control" id="signup-email" name="signup-email" aria-describedby="emailHelp" placeholder="Your email" required>
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="signup-password" name="signup-password" placeholder="Password" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="signup-stayCheck" name="signup-stayCheck">
                        <label class="form-check-label" for="stayCheck">Stay logged in</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Sign Up!</button>
                </form>
                <button class="btn" id="show-login-btn">Log in</button>
            </div>
            <div class="form-container invisible" id="login-form">
                <form method="post">
                    <div class="form-group">
                        <input type="email" class="form-control" id="login-email" name="login-email" aria-describedby="emailHelp" placeholder="Your email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="login-password" name="login-password" placeholder="Password" required>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="login-stayCheck" class="form-check-input" id="login-stayCheck">
                        <label class="form-check-label" for="login-stayCheck">Stay logged in</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Log in</button>
                </form>
                <button class="btn" id="show-sign-up-btn">Sign up</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="js/script.js"></script>
</body>

</html>
