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
    <title>Mind Garden</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
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
        <div class="row">
            <div class="col col-md-10 col-lg-8 mx-auto text-center" id="notes-container">
                <h4>New note</h4>
                <div class="d-flex align-items-center">
                    <small>2020/05/21 - 16:12 <span class="badge badge-secondary">Saving...</span><span
                            class="badge badge-success">Changes
                            saved!</span><span class="badge badge-danger">Save
                            failed!</span></small>
                    <button class="btn btn-primary btn-sm ml-auto">Archive note</button>
                </div>
                <div class="form-control mt-1 mb-3 text-left" id="new-note-field" contenteditable="true"></div>
                <h4>Previous notes</h4>
                <div class="d-flex align-items-center">
                    <small>2020/05/20 - 10:34 <span class="badge badge-secondary">Saving...</span><span
                            class="badge badge-success">Changes
                            saved!</span><span class="badge badge-danger">Save
                            failed!</span></small>
                    <button class="btn btn-outline-danger btn-sm ml-auto">Delete note</button>
                </div>
                <div class="form-control mt-1 mb-3 previous-entry text-left" contenteditable="true">Today I am so PMS-y
                    it's
                    moronic. I had to punch my teddy bear over 13 times just to get the image of Tiffani and Jeremiah
                    trying out for the HOCKEY TEAM out of my head. They were my best friends. Now I only harbor malice
                    towards them. I don't need this baloney, I have too much homework to do to deal with that. Right now
                    I'm listening to The New Kids on the Block and all it's doing is making me more PMS-y. Jeremiah can
                    go die for all I care. I feel like I am completely alone, and dressed only in my punkest expression.
                    I'm gonna IM Tracie and see if she wants to get a milkshake before I am forced to eat another
                    cheesecake.</div>
                <div class="d-flex align-items-center">
                    <small>2020/05/20 - 10:34 <span class="badge badge-secondary">Saving...</span><span
                            class="badge badge-success">Changes
                            saved!</span><span class="badge badge-danger">Save
                            failed!</span></small>
                    <button class="btn btn-outline-danger btn-sm ml-auto">Delete note</button>
                </div>
                <div class="form-control mt-1 mb-3 previous-entry text-left" contenteditable="true">What a busy day
                    today! I never
                    had a moment’s rest. The day started with my alarm clock blaring at 7am. I had to be at the Smith’s
                    house by 8am to baby-sit. I really didn’t want to wake up so early on a Saturday, but I’m saving
                    money to buy a new iPod and couldn’t say no to an all-day babysitting job.

                    When I arrived at the Smith’s house, both kids were already awake. Madison was watching cartoons in
                    living room and Jacob was playing with his Legos in his bedroom. The kids were hungry so I made them
                    some oatmeal in the microwave and we all ate breakfast together.

                    We had to leave right after breakfast for Jacob’s soccer practice. Luckily, the Smiths only live a
                    couple blocks from the soccer field so we just walked there. It was a lot of fun watching Jacob play
                    soccer. He’s very talented. I was worried that Madison might be bored, but she stayed busy by
                    playing her Gameboy while Jacob practiced.

                    When practice was over, we went back to the house to make lunch. It’s always a breeze to make lunch
                    for the Smith kids because they love chicken nuggets and French fries. All I had to do was pull the
                    food out of the freezer and bake it in the oven.

                    After lunch, I planned a special surprise for the kids...I took them to see the new Disney movie.
                    The movie theater is a few miles away from their house so I had to call my mom to pick us up in her
                    car and drive us there. The kids loved the movie; they couldn’t stop laughing. I have to admit that
                    I thought it was pretty funny too. The only bad part was that the theater had the air conditioning
                    cranked up and it was freezing cold!

                    My mom picked us up again after the movie and took us back to the Smith’s house. We had only been
                    back for a few minutes when Mrs. Smith came back home. I didn’t expect her home so soon, but she was
                    back early because the power went out at her office.

                    It worked out very well though because as soon as I left the Smith’s, Danielle called to see if I
                    wanted to go to the mall. I checked in at home to make sure it was ok and then met Danielle at her
                    house.

                    While we were at the mall, we definitely “shopped ‘til we dropped”. I bought a t-shirt, a sweater, a
                    pair of jeans, and some flip flops. I know that I won’t need the flip flops for much longer since
                    it’s already October, but they were on clearance and I couldn’t pass them up.

                    Finally after all that shopping, we headed home because I was exhausted. Today was a fun day, but I
                    can’t wait to sleep in tomorrow!</div>

                <button class="btn btn-secondary d-block mx-auto mb-5">Load more notes</button>
            </div>
        </div>
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
    <script src="js/script.js"></script>
</body>

</html>