<?php
session_start();

require_once "../includes/authenticate.php";

/** @var mysqli $db */
require_once "../includes/database.php";

$customerId = mysqli_escape_string($db, $_GET['id']);

$query = "SELECT * FROM reservations WHERE id = '$customerId'" ;
$result = mysqli_query($db, $query) or die ('Error: ' . $query);

if (mysqli_num_rows($result) == 1) {
    $reservation = mysqli_fetch_assoc($result);
    // redirect when db returns no result
} else {
    header('Location: read.php');
    exit;
}

//Close connection
mysqli_close($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <title>Details</title>
</head>
    <body>

        <section class="section">
            <div class="container is-max-desktop">
                    <div class="box">
                        <section class="section">
                            <div class="tile is-ancestor">
                                <div class="tile is-4 is-vertical is-parent">
                                    <div class="tile is-child box">
                                        <p class="title">Klant gegevens</p>

                                        <div class="icon-text">
                                            <span class="icon has-text">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <span><?= htmlentities($reservation['name']) ?></span>
                                        </div>

                                        <div class="icon-text">
                                            <span class="icon has-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <span><?= htmlentities($reservation['email']) ?></span>
                                        </div>

                                        <div class="icon-text">
                                            <span class="icon has-text">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <span><?= htmlentities($reservation['phone']) ?></span>
                                        </div>

                                        <div class="icon-text">
                                            <span class="icon has-text">
                                                <i class="fa-solid fa-user-group"></i>
                                            </span>
                                            <span><?= htmlentities($reservation['people']) ?></span>
                                        </div>

                                        <hr>

                                        <div class="block">
                                            <h5 class="title is-6">0-12 jaar</h5>
                                            <h6 class="subtitle is-6"><?= htmlentities($reservation['0_12']) ?> personen</h6>
                                        </div>

                                       <div class="block">
                                           <h5 class="title is-6">13-64 jaar</h5>
                                           <h6 class="subtitle is-6"><?= htmlentities($reservation['13_64']) ?> personen</h6>
                                       </div>

                                        <div class="block">
                                            <h5 class="title is-6">65+</h5>
                                            <h6 class="subtitle is-6"><?= htmlentities($reservation['65']) ?> personen</h6>
                                        </div>
                                    </div>
                                    <div class="tile is-child box">
                                        <p class="title">Reservatie</p>
                                        <p><?= htmlentities($reservation['date']) ?></p>
                                        <p><?= htmlentities($reservation['time']) ?></p>
                                    </div>
                                </div>
                                <div class="tile is-parent">
                                    <div class="tile is-child box">
                                        <p class="title">Opmerking</p>
                                        <p><?= htmlentities($reservation['comment']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <div class="level">
                            <a class="button" href="reservations_view.php">
                            <span class="icon is-small">
                              <i class="fa-solid fa-angle-left"></i>
                            </span>
                            </a>

                            <a class="button" href="reservation_edit.php?id=<?= $reservation['id'] ?>">
                            <span class="icon is-small">
                              <i class="fa-solid fa-pen-to-square"></i>
                            </span>
                            </a>
                        </div>
                    </div>
            </div>
        </section>
    </body>
</html>
