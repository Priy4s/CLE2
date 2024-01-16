<?php
session_start();

$errors = [];
$postData = [];

$valName = '';
$valEmail = '';
$valPhone = '';
$valComment = '';

if (isset($_POST['submit'])) {
    $valName = $_POST['name'] ?? '';
    $valEmail  = $_POST['email'] ?? '';
    $valPhone = $_POST['phone'] ?? '';
    $valComment = $_POST['comment'] ?? '';

    if ($_POST['name'] == '') {
        $errors['name'] = "Naam kan niet leeg zijn";
    }

    if ($_POST['email'] == '') {
        $errors['email'] = "Email kan niet leeg zijn.";
    }

    if ($_POST['phone'] == '') {
        $errors['phone'] = "Telefoonnummer kan niet leeg zijn.";
    } elseif (!is_numeric($_POST['phone'])) {
        $errors['phone'] = "Telefoonnummer moet bestaan uit nummers.";
    } elseif (!is_numeric($_POST['phone']) || strlen($_POST['phone']) < 10 || strlen($_POST['phone']) > 13) {
        $errors['phone'] = "Ongeldig telefoonnummer";
    }


    if (empty($errors)) {
        /** @var mysqli $db */
        // Setup connection with database
        require_once '../includes/database.php';
        $name = mysqli_real_escape_string($db, $valName);
        $email = mysqli_real_escape_string($db, $valEmail);
        $phone = mysqli_real_escape_string($db, $valPhone);
        $comment = mysqli_real_escape_string($db, $valComment);
        $date = mysqli_real_escape_string($db, $_SESSION['reservation_date']);
        $desired_time = mysqli_real_escape_string($db, $_SESSION['desired_time']);
        $amount = mysqli_real_escape_string($db, $_SESSION['amount']);
        $age_group_65 = mysqli_real_escape_string($db, $_SESSION['age_group_65']);
        $age_group_13_64 = mysqli_real_escape_string($db, $_SESSION['age_group_13_64']);
        $age_group_0_12 = mysqli_real_escape_string($db, $_SESSION['age_group_0_12']);
        $capacity = 300;

        $insertReservationQuery = "INSERT INTO reservations (`name`, `email`, `phone`, `people`, `comment`, `date`, `time`, `65`, `13_64`, `0_12`) 
                               VALUES ('$name', '$email', '$phone', '$amount' , '$comment', '$date', '$desired_time', '$age_group_65', '$age_group_13_64', '$age_group_0_12')";
        mysqli_query($db, $insertReservationQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $insertReservationQuery);

        //niet beschikbare plekken aanpassen

        $capacityQuery = "SELECT * FROM day_capacities WHERE date = '$date'";
        $result = mysqli_query($db, $capacityQuery);

        if (mysqli_num_rows($result) == 1) {
            // datum bestaat al in capaciteit database
            $row = mysqli_fetch_assoc($result);
            $people = $row['people'];
            $newPeopleValue = $people + $amount; // Adjust the value according to your logic

            // Update the 'people' value in the day_capacities table
            $updateCapacityQuery = "UPDATE day_capacities SET people = $newPeopleValue WHERE date = '$date'";
            mysqli_query($db, $updateCapacityQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $updateCapacityQuery);
        } else {
            $newCapacityQuery = "INSERT INTO day_capacities (`date`, `capacity`, `people`) VALUES ('$date', '$capacity', '$amount')";
            mysqli_query($db, $newCapacityQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $newCapacityQuery);
        }

        mysqli_close($db);
        header('Location: reservationconfirmation.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Resevering</title>
</head>
<body>
<body>
<nav>
    <div class="navbar">
        <div>
            <img src="../images/sense.png " alt="" class="navimg">
        </div>
        <div>
            <a href="">MENU</a>
            <a href="">TAKE-AWAY DELIVERY</a>
            <a href="" class="selected">RESERVEREN</a>
            <a href="">JARIG?</a>
            <a href="">BIOSCOOPMENU</a>
            <a href="">ZAALHUUR</a>
            <a href="">PRIVACY DISCLAIMER</a>
        </div>
    </div>
</nav>
<main>
    <div class="jumbotron">
        <h1 class="lead">Reserveren</h1>
    </div>
</main>
<section class="form">
<div class="container px-4">
    <h1 class="reserveer">Reserveer</h1>
    <form method="post" action="">
        <div class="field">
            <label class="label">Naam*</label>
            <div class="control">
                <input class="input" id="medium" type="text" name="name" value="<?= htmlspecialchars($valName) ?>">
            </div>
            <p class="errors">
                <?= isset($errors['name']) ? htmlspecialchars($errors['name']) : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Email*</label>
            <div class="control">
                <input class="input" id="medium" type="email" name="email" value="<?= htmlspecialchars($valEmail) ?>">
            </div>
            <p class="errors">
                <?= isset($errors['email']) ? htmlspecialchars($errors['email']) : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Telefoonnummer*</label>
            <div class="control">
                <input class="input" id="medium" type="text" name="phone" value="<?= htmlspecialchars($valPhone) ?>">
            </div>
            <p class="errors">
                <?= isset($errors['phone']) ? htmlspecialchars($errors['phone']) : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Opmerkingen</label>
            <div class="control">
                <textarea class="textarea" id="large" name="comment"><?= htmlspecialchars($valComment) ?></textarea>
            </div>
            <p class="errors">
                <?= isset($errors['comment']) ? htmlspecialchars($errors['comment']) : '' ?>
            </p>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit" name="submit">Reserveer</button>
            </div>
        </div>
    </form>
    <div>
        <a class=reserveer href="../index.php">Terug naar reserveren</a>
    </div>
</div>
</section>
    <div>
        <h3>Openingstijden</h3>
        <p>Dagelijks geopend van 16:30 - 22:00 uur</p>
        <p>Maandag* &amp; Dinsdag GESLOTEN</p>
        <p>*Feestdagen die op maandag vallen zijn wij geopend</p>
        <h4>GRATIS PARKEREN. LET OP: Woensdag en Donderdag aangepast parkeren in verband met wekelijkse Marktdag
        </h4>
    </div>
</footer>
</div>
</body>
</html>
