<?php

session_start();

$alertString = "";
$email = "";
$password = "";
$remain = "";
$checked = "";

if ($_POST) {

    if (array_key_exists("logout", $_POST)) {

        setcookie("id", "", time() - 60*60);
        $_COOKIE["id"] = "";
        unset($_SESSION);
        session_destroy();
        $alertString = "<div class='alert col-md-8 col-lg-6 my-4 mx-auto alert-success'>You have been logged out.</div>";
        
    } else {

        $link = mysqli_connect("shareddb-u.hosting.stackcp.net", "user12345678", "user12345678", "users-dbase-3133339a99");

        if (mysqli_connect_error()) {
            echo "Failed to connect to MySQL: ".mysqli_connect_error();
            die ("There was an error connecting to the database");
        }

        $email = $_POST["email"];
        $password = $_POST["password"];
        $remain = isset($_POST["remain"]) ? true : false;
        $checked = ($remain) ? "checked" : "";
        $signup = $_POST["signup"];

        if ($signup == 1) {

            if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {

                $alertString = "<div class='alert col-md-8 col-lg-6 my-4 mx-auto alert-warning'>The email address you entered is already signed up! Try logging in instead.</div>";

            } else {

                signup($link, $email, $password);
                login(mysqli_insert_id($link), $remain);

            }

        } else if ($signup == 0) {

            if (mysqli_num_rows($result = selectUser($link, $email)) > 0) {

                $row = mysqli_fetch_array($result);

                if (password_verify($password, $row["pwdhash"])) {

                    login($row["id"], $remain);

                } else {

                    $alertString = "<div class='alert col-md-8 col-lg-6 my-4 mx-auto alert-danger'>Incorrect password.</div>";

                }

            } else {

                $alertString = "<div class='alert col-md-8 col-lg-6 my-4 mx-auto alert-warning'>It seems you aren't signed up yet. You can't login until you're signed up first!</div>";

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

    $query = "INSERT INTO notes (userid, content, created, lastupdated) VALUES (".mysqli_insert_id($link).", '', '', '')";
    mysqli_query($link, $query);

}

function login($id, $remain) {

    $_SESSION["id"] = $id;

    if ($remain) {
        setcookie("id", $id, time() + 60*60*24);
    }

    header("Location: index.php");

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
    <link rel="stylesheet" href="styles.css">
</head>

<body id="login-body">

    <div class="container">
        <div class="row page-row align-items-center">
            <div class="col text-center" id="log-in-page">
                <h1><svg class="yin-yang-logo large" enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><g fill="#6bbef6"><path d="m486.66 356.132c-.932 0-1.878-.174-2.795-.543-3.844-1.545-5.707-5.913-4.162-9.756 11.478-28.555 17.297-58.779 17.297-89.833 0-50.081-15.215-98.097-43.999-138.857-28.156-39.872-67.1-69.942-112.618-86.959-3.88-1.45-5.85-5.771-4.399-9.651 1.451-3.879 5.774-5.847 9.651-4.398 48.354 18.076 89.717 50.013 119.619 92.357 30.582 43.303 46.746 94.311 46.746 147.508 0 32.979-6.184 65.085-18.379 95.427-1.177 2.926-3.989 4.705-6.961 4.705z"/><path d="m424.599 446.06c-2.02 0-4.032-.81-5.511-2.41-2.811-3.042-2.623-7.788.419-10.599 9.984-9.225 19.253-19.363 27.548-30.132 2.525-3.28 7.234-3.895 10.518-1.365 3.282 2.527 3.894 7.237 1.365 10.519-8.808 11.435-18.649 22.2-29.251 31.996-1.443 1.332-3.268 1.991-5.088 1.991z"/><path d="m256 512c-68.38 0-132.667-26.629-181.02-74.981-2.929-2.929-2.929-7.678 0-10.606 2.93-2.929 7.678-2.929 10.607 0 45.519 45.519 106.04 70.587 170.413 70.587 40.455 0 80.466-10.214 115.706-29.54 3.634-1.993 8.19-.662 10.183 2.97 1.991 3.632.662 8.19-2.97 10.182-37.445 20.534-79.95 31.388-122.919 31.388z"/><path d="m23.537 351.515c-3.027 0-5.879-1.846-7.015-4.845-10.963-28.943-16.522-59.449-16.522-90.67 0-68.38 26.629-132.667 74.98-181.02s112.64-74.98 181.02-74.98c4.143 0 7.5 3.358 7.5 7.5s-3.357 7.5-7.5 7.5c-64.374 0-124.895 25.068-170.413 70.587-45.519 45.519-70.587 106.039-70.587 170.413 0 29.399 5.231 58.117 15.55 85.356 1.468 3.874-.483 8.203-4.356 9.67-.876.331-1.774.489-2.657.489z"/></g><path d="m80.185 157.047c-1.326 0-2.668-.351-3.884-1.089-3.541-2.148-4.67-6.761-2.521-10.302 18.655-30.743 44.984-56.469 76.141-74.396 32.124-18.485 68.806-28.256 106.079-28.256 34.962 0 69.636 8.656 100.272 25.033 3.652 1.953 5.031 6.497 3.078 10.15-1.952 3.652-6.497 5.032-10.15 3.079-28.468-15.218-60.696-23.262-93.2-23.262-34.651 0-68.747 9.08-98.599 26.256-28.971 16.67-53.452 40.59-70.798 69.176-1.411 2.326-3.884 3.611-6.418 3.611z" fill="#528ecb"/><path d="m315.165 460.352c-3.226 0-6.205-2.098-7.181-5.346-1.19-3.967 1.061-8.149 5.027-9.34 39.919-11.981 75.816-36.999 101.08-70.444 26.105-34.56 39.904-75.787 39.904-119.223 0-45.326-15.706-89.626-44.226-124.739-2.612-3.215-2.122-7.938 1.093-10.55 3.217-2.612 7.938-2.122 10.55 1.093 30.685 37.778 47.583 85.437 47.583 134.196 0 46.726-14.847 91.079-42.936 128.264-27.17 35.969-65.787 62.878-108.736 75.77-.718.216-1.444.319-2.158.319z" fill="#528ecb"/><path d="m256 468.996c-117.446 0-212.996-95.549-212.996-212.996 0-11.925.995-23.89 2.957-35.564.687-4.085 4.556-6.838 8.64-6.153 4.085.687 6.84 4.555 6.153 8.64-1.825 10.855-2.75 21.984-2.75 33.078 0 109.175 88.82 197.996 197.996 197.996 4.143 0 7.5 3.358 7.5 7.5s-3.357 7.499-7.5 7.499z" fill="#528ecb"/><circle cx="256" cy="256" fill="#5e6b75" r="160.47"/><path d="m369.469 142.53c-31.332-31.332-82.137-31.339-113.476 0-31.332 31.332-31.325 82.138.007 113.47s31.339 82.137 0 113.476c-31.332 31.332-82.137 31.325-113.469-.007-62.671-62.671-62.671-164.268 0-226.939 62.663-62.664 164.267-62.67 226.938 0z" fill="#f6f1f1"/><circle cx="312.684" cy="199.214" fill="#f6f1f1" r="31.123"/><circle cx="199.214" cy="312.684" fill="#5e6b75" r="31.123"/></g></svg>Mind Garden</h1>
                <span class="mb-2"><strong>Keeping a diary? Writing a novel? Or just need a place to organise your
                        thoughts?</strong></span>
                <span class="mb-2"><strong>Use Mind Garden.</strong></span>
                <span class="mb-2">Sign up for free now.</span>
                <div class="form-container col-12 col-md-6 col-lg-4 mx-auto" id="sign-up-form">
                    <form method="post">
                        <div class="form-group">
                            <input type="email" class="form-control" id="signup-email" name="email"
                                aria-describedby="emailHelp" placeholder="Your email address" required
                                value="<?php echo $email; ?>">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
                                else.</small>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" id="signup-password" name="password"
                                placeholder="Your password" required value="<?php echo $password; ?>">
                        </div>
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="signup-stayCheck" name="remain"
                                <?php echo $checked; ?>>
                            <label class="form-check-label" for="stayCheck">Stay logged in</label>
                        </div>
                        <button type="submit" class="btn btn-outline-primary" name="signup" value="0">Log in</button>
                        <button type="submit" class="btn btn-primary" name="signup" value="1">Sign Up!</button>
                    </form>
                </div>
                <?php echo $alertString; ?>
            </div>
        </div>
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