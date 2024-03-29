<?php
session_start();

require_once "../includes/authenticate.php";

//Require DB settings with connection variable
/** @var mysqli $db */
require_once "../includes/database.php";

//Get the result set from the database with a SQL query
$query = "SELECT * FROM reservations" ;
$result = mysqli_query($db, $query) or die ('Error: ' . $query);

//Loop through the result to create a custom array
$reservation = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservation[] = $row;
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
    <title>Reservations</title>
</head>
<body>
    <div class="container has-background-white px-4">
        <nav class="level mt-4">
            <p class="level-item has-text-centered">
                <a class="button is-light is-fullwidth" href="index.php">Home</a>
            </p>

            <p class="level-item has-text-centered title is-size-1">
                Reserveringen
            </p>

            <p class="level-item has-text-centered">
                <a class="button is-light is-fullwidth" href="../reservation_create/reservationdate.php">Reservering maken</a>
            </p>
        </nav>
        <hr>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Email</th>
                    <th>Telefoon</th>
                    <th>Aantal klanten</th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($reservation as $index => $reservations) {?>
                <tr>
                    <td><?= htmlentities($reservations['name']) ?></td>
                    <td><?= htmlentities($reservations['date']) ?></td>
                    <td><?= htmlentities($reservations['time']) ?></td>
                    <td><?= htmlentities($reservations['email']) ?></td>
                    <td><?= htmlentities($reservations['phone']) ?></td>
                    <td><?= htmlentities($reservations['people']) ?></td>
                    <td><a class="has-text-black" href="reservation_detail.php?id=<?= $reservations['id'] ?>">Details</a></td>
                    <td><a class="has-text-danger" href="delete.php?id=<?= $reservations['id'] ?>"><strong>Verwijderen</strong></a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
