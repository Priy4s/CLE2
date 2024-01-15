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


    $insertReservationQuery = "INSERT INTO reservations (`name`, `email`, `phone`, `people`, `comment`, `date`, `time`, `65`, `13_64`, `0_12`  ) 
                           VALUES ('$name', '$email', '$phone', '$amount' , '$comment', '$date', '$desired_time', '$age_group_65', '$age_group_13_64', '$age_group_0_12')";
    mysqli_query($db, $insertReservationQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $insertReservationQuery);

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
                <input class="input" id="medium" type="text" name="name" value="<?= $valName ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['name']) ? $errors['name'] : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Email*</label>
            <div class="control">
                <input class="input" id="medium" type="email" name="email" value="<?= $valEmail ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['email']) ? $errors['email'] : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Telefoonnummer*</label>
            <div class="control">
                <input class="input" id="medium" type="text" name="phone" value="<?= $valPhone ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['phone']) ? $errors['phone'] : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Opmerkingen</label>
            <div class="control">
                <textarea class="textarea" id="large" name="comment" value="<?= $valName ?>" ?> </textarea>
            </div>
            <p class="help is-danger">
                <?= isset($errors['comment']) ? $errors['comment'] : '' ?>
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
<footer>
    <div>
        <h3>Openingstijden</h3>
        <p>Dagelijks geopend van 16:30 - 22:00 uur</p>
        <p>Maandag* &amp; Dinsdag GESLOTEN</p>
        <p>*Feestdagen die op maandag vallen zijn wij geopend</p>
        <h4>GRATIS PARKEREN. LET OP: Woensdag en Donderdag aangepast parkeren in verband met wekelijkse Marktdag
        </h4>
    </div>
    <div>
        <h3>Contact</h3>
        <p>Middenbaan Noord 202, 3191 EL Hoogvliet Rotterdam</p>
        <p>T. 010 438 35 88</p>
    </div>
    <div class="footernav">
        <h3>Navigeer</h3>
        <a href="">Menu</a>
        <a href="">Take-Away Delivery</a>
        <a href="">Reserveren</a>
        <a href="">Jarig?</a>
        <a href="">Bioscoopmenu</a>
        <a href="">Zaalhuur</a>
        <a href="">Privacy Disclaimer</a>
        <ul class="wp-container-1 wp-block-social-links alignleft"><li class="wp-social-link wp-social-link-facebook wp-block-social-link"><a href="https://www.facebook.com/senseofchina" class="wp-block-social-link-anchor"><svg width="24" height="24" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg><span class="wp-block-social-link-label screen-reader-text">Facebook</span></a></li></ul>
    </div>
</footer>
</body>
</html>
