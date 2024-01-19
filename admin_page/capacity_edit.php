<?php
session_start();

/** @var $db */
require_once "../includes/database.php";

//Retrieve the GET parameter from the 'Super global'
$capacityId = mysqli_escape_string($db, $_GET['id']);

//Get the record from the database result
$query = "SELECT * FROM day_capacities WHERE id = '$capacityId'";
$result = mysqli_query($db, $query)
or die ('Error: ' . $query);

//If the monster doesn't exist, redirect back to the read page
//if (mysqli_num_rows($result) != 1) {
//    header('Location: read.php');
//    exit;
//}

//Transform the row in the DB table to a PHP array
$dayCapacity = mysqli_fetch_assoc($result);

//Check if Post isset
if (isset($_POST['submit'])) {

    //Postback with the data showed to the user, first retrieve data from 'Super global'
    $capacity = mysqli_escape_string($db, $_POST["capacity"]);

    $errors = [];

    if (empty($capacity)) {
        $errors['capacity'] = "Vul een capaciteit in";
    }

    //If everything is filled in send data to database and send user to detail page.
    if (empty($errors)) {
        $query = "UPDATE day_capacities 
        SET capacity = '$capacity'
        WHERE id = '$capacityId'";
        mysqli_query($db, $query);
        header('Location: capacity_view.php?' );
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <!--Field for capacity-->
        <div class="field">
            <label class="label" for="name">Capaciteit</label>
            <div class="control">
                <input class="input" id="name" placeholder="capacity" type="text" name="capacity" value=" <?= htmlentities($dayCapacity['capacity']) ?>">
                <span class="help is-danger">
                    <?= $errors['capacity'] ?? '' ?>
                </span>
            </div>
        </div>

        <div class="field is-centered">
            <div class="field-label is-normal"></div>
            <div class="field-body">
                <button class="button is-primary is-fullwidth" type="submit" name="submit">Save</button>
            </div>
        </div>
    </form>
</body>
</html>
