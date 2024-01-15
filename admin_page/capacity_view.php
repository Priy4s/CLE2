<?php
session_start();

//         Gegevens voor de connectie
$host       = 'localhost';
$username   = 'root';
$password   = '';
$database   = 'cle_2';

//         Verbinding met de database en foutafhandeling. Als verbinding niet gelukt is, wordt
//         "or die" uitgevoerd. Deze stopt de code en toont de
//         foutmelding op het scherm
$db = mysqli_connect($host, $username, $password, $database)
or die('Error: '.mysqli_connect_error());

$query = "SELECT * FROM day_capacities";

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

function getNameById($db, $id, $tableName, $columnName = 'name') {
    $query = "SELECT $columnName FROM $tableName WHERE id = '$id'";
    $result = mysqli_query($db, $query);
    $row = mysqli_fetch_assoc($result);
    return isset($row[$columnName]) ? $row[$columnName] : '';
}

// Debug statement
// echo "User ID: " . $_SESSION['user'];

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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
<a href="logout.php">Log-Out</a>
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
            <td><?= isset($dayCapacity['people']) ? $dayCapacity['people'] : '' ?></td>
            <td><a href="capacity_edit.php?id=<?= isset($dayCapacity['id']) ? $dayCapacity['id'] : '' ?>">Edit</a></td>
        </tr>
        <?php
    }
    mysqli_close($db);
    ?>
    </tbody>
</table>
</body>
</html>
