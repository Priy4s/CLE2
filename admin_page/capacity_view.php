<?php
session_start();

require_once "../includes/authenticate.php";

/** @var mysqli $db */
require_once '../includes/database.php';

//$query = "SELECT * FROM day_capacities";

$query = "
    SELECT c.id, c.capacity, r.date, SUM(r.people) as total_people
    FROM prj_2023_2024_ressys_t6.reservations r
    JOIN prj_2023_2024_ressys_t6.day_capacities c ON r.date = c.date
    GROUP BY r.date
";

$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

//         Er wordt een nieuwe array gemaakt waarin alle
//         rijen uit de db komen. In dit geval is een rij een album.
$dayCapacities = [];
//         mysqli_fetch_assoc haalt een rij uit de db en zet deze om naar
//         een associatieve array. De namen van de index corresponderen met de
//         kolomnamen (velden) van de tabel
//         Als er geen rijen meer zijn in het resultaat geeft mysqli_fetch_assoc
//         'false' terug en stopt de while loop.
while($row = mysqli_fetch_assoc($result))
{
//         Elke rij wordt aan de array 'daycapacities' toegevoegd.
    $dayCapacities[] = $row;
}
mysqli_close($db);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Day Capacity List</title>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>
<body>
    <p class="level-item has-text-centered title is-size-1">
        Capaciteit
    </p>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Datum</th>
            <th>Capaciteit</th>
            <th>Personen</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        <?php
        // $daycapacities wordt doorlopen met een foreach loop en zo kunnen de onderdelen
        // getoond worden.
        foreach ($dayCapacities as $dayCapacity) { ?>
            <tr>
                <td><?= isset($dayCapacity['id']) ? $dayCapacity['id'] : '' ?></td>
                <td><?= isset($dayCapacity['date']) ? $dayCapacity['date'] : '' ?></td>
                <td><?= isset($dayCapacity['capacity']) ? $dayCapacity['capacity'] : '' ?></td>
                <td><?= isset($dayCapacity['total_people']) ? $dayCapacity['total_people'] : '' ?></td>
                <td><a href="capacity_edit.php?id=<?= isset($dayCapacity['id']) ? $dayCapacity['id'] : '' ?>">Edit</a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <p class="level-item has-text-centered">
        <a class="button is-danger is-fullwidth" href="index.php">Terug</a>
    </p>
</body>
</html>
