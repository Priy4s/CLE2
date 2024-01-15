<?php
session_start();

//May I visit this page?
//if (!isset($_SESSION['user_id'])) {
//    header("Location: index.php");
//}

/** @var mysqli $db */
require_once "../includes/database.php";

$customerId = mysqli_escape_string($db, $_GET['id']);

$query = "SELECT * FROM reservations WHERE id = '$customerId'" ;
$result = mysqli_query($db, $query) or die ('Error: ' . $query);

//Loop through the result to create a custom array
$reservation = [];
while ($row = mysqli_fetch_assoc($result)) {
    $reservation[] = $row;
}



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
    <title>Document</title>
</head>
    <body>
        <table class="table is-striped is-fullwidth">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Datum</th>
                    <th>Tijd</th>
                    <th>Email</th>
                    <th>Telefoon</th>
                    <th>Aantal klanten</th>
                    <th>0-12</th>
                    <th>13-64</th>
                    <th>65</th>
                    <th>Comment</th>
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
                        <td><?= htmlentities($reservations['0_12']) ?></td>
                        <td><?= htmlentities($reservations['13_64']) ?></td>
                        <td><?= htmlentities($reservations['65']) ?></td>
                        <td><?= htmlentities($reservations['comment']) ?></td>
                        <td><a class="has-text-black" href="reservation_detail.php?id=<?= $reservations['id'] ?>">Bewerken</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
</html>
