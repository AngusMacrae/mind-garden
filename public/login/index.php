<?php

session_start();

require_once '../../private/UsersModel.php';
$usersModel = new UsersModel;

if (isset($_POST["action"]) && $_POST["action"] == "logout") {
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";
    unset($_SESSION);
    session_destroy();
    $alertHTML = createAlert("success", "You have been logged out.");
} else if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["action"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $remain = isset($_POST["remain"]) ? true : false;
    $action = $_POST["action"];

    if ($action == "signup") {

        $userID = $usersModel->signup($email, $password);

        if ($userID == null) {
            $alertHTML = createAlert("warning", "The email address you entered is already signed up! Try logging in instead.");
        } else {
            enterApp($userID);
        }

    } else if ($action == "login") {

        $userID = $usersModel->login($email, $password);

        if ($userID == 'NOT_SIGNED_UP') {
            $alertHTML = createAlert("warning", "It seems you aren't signed up yet. You can't login until you're signed up first!");
        } else if ($userID == 'INCORRECT_PASSWORD') {
            $alertHTML = createAlert("danger", "Incorrect password.");
        } else {
            enterApp($userID);
        }
    }
}

function enterApp($userID) {
    $_SESSION["id"] = $userID;
            
    if ($remain) {
        setcookie("id", $userID, time() + 60*60*24);
    }
    
    header("Location: ../");
}

function createAlert($type, $message) {
    // $type can be warning, danger or success
    return "<div class='alert alert-{$type} col-md-8 col-lg-6 my-4 mx-auto no-shadow'>{$message}</div>";
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
    <link rel="stylesheet" href="../css/login-styles.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" defer>
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous" defer>
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous" defer>
    </script>
</head>

<body class="text-light">
    <div class="container">
        <main class="row align-items-center">
            <div class="col text-center">
                <h1><img src="../images/yin-yang.svg" class="yin-yang-logo mr-2">Mind Garden</h1>
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
                <?php echo $alertHTML; ?>
            </div>
        </main>
    </div>
</body>

</html>