<?php
// Start the session
session_start();

$errors = [];
$postData = [];
$postData['age_group_65'] = $_POST['age_group_65'] ?? '';
$postData['age_group_13_64'] = $_POST['age_group_13_64'] ?? '';
$postData['age_group_0_12'] = $_POST['age_group_0_12'] ?? '';
$postData['reservation_date'] = $_POST['reservation_date'] ?? '';
$postData['desired_time'] = $_POST['desired_time'] ?? '';

if (isset($_POST['submit'])) {
    $postData['age_group_65'] = $_POST['age_group_65'] ?? '';
    $postData['age_group_13_64'] = $_POST['age_group_13_64'] ?? '';
    $postData['age_group_0_12'] = $_POST['age_group_0_12'] ?? '';
    $postData['reservation_date'] = $_POST['reservation_date'] ?? '';
    $postData['desired_time'] = $_POST['desired_time'] ?? '';

    if (empty($_POST['age_group_65']) && empty($_POST['age_group_13_64']) && empty($_POST['age_group_0_12'])) {
        $errors['age_groups'] = "Selecteer minimaal één leeftijdsgroep.";
    }

    if ($_POST['reservation_date'] == '') {
        $errors['reservation_date'] = "Datum kan niet leeg zijn.";
    } elseif (strtotime($_POST['reservation_date']) < strtotime(date('Y-m-d'))) {
        $errors['reservation_date'] = "Geselecteerde datum mag niet in het verleden liggen.";
    } elseif (strtotime($_POST['reservation_date']) == strtotime(date('Y-m-d'))) {
        $errors['reservation_date'] = "Voor reservering op de huidige datum, neem telefonisch contact op.";
    }

    if (empty($_POST['desired_time'])) {
        $errors['desired_time'] = "Selecteer een gewenste tijd.";
    } else {
        // Voer een aangepaste validatie uit voor de tijd (bijvoorbeeld: HH:MM-formaat)
        $pattern = "/^(1[7-9]|20|21|22):([0-3][0,5])$/"; // Hier wordt gecontroleerd op 17:00 tot 22:30 in stappen van 30 minuten

        if (!preg_match($pattern, $_POST['desired_time'])) {
            $errors['desired_time'] = "Kies een tijdstip.";
        }
    }


    if (empty($errors)) {
        $amount = $_POST['age_group_65'] + $_POST['age_group_13_64'] + $_POST['age_group_0_12'];

        /** @var mysqli $db */
        require_once '../includes/database.php';
        //check if you add the $amount to 'people' in the day_capacities table it will not exceed 'capacity' if it does not, or if the date does not exist in the databse. Store the variables in session
        // Retrieve existing capacity and people values

        $date = mysqli_real_escape_string($db, $_POST['reservation_date']);
        $capacityQuery = "SELECT capacity, people FROM day_capacities WHERE date = '$date'";
        $result = mysqli_query($db, $capacityQuery);

        if ($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $existingPeople = $row['people'];
            $capacity = $row['capacity'];

            // Check if adding new amount exceeds capacity
            if ($existingPeople + $amount > $capacity) {
                $errors['reservation_date'] = "Er zijn niet genoeg plekken op deze datum.";
            }

        }
        if (empty($errors)) {
            $_SESSION['reservation_date'] = htmlspecialchars($_POST['reservation_date']);
            $_SESSION['age_group_65'] = $_POST['age_group_65'];
            $_SESSION['age_group_13_64'] = $_POST['age_group_13_64'];
            $_SESSION['age_group_0_12'] = $_POST['age_group_0_12'];
            $_SESSION['amount'] = $amount;
            //format time properly
            $format_time = DateTime::createFromFormat('H:i', $_POST['desired_time']);
            $sqlTime = $format_time->format('H:i:s');
            $_SESSION['desired_time'] = $sqlTime;
            header("Location: reservationform.php");
            exit();
        }
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
    <div>
        <p>Reserveer gemakkelijk via onderstaand formulier.
            Voor reserveringen voor dezelfde avond, gelieve te bellen met ons restaurant op nummer 010 438 35 88.
            Voor bijzondere verzoeken, stuur een mail naar info@senseofchina.nl.</p>
    </div>
<div class="container px-4">
    <h1 class="reserveer">Reserveer</h1>
    <form method="post" action="">
        <div class="field">
            <label class="label">Aantal personen 65+</label>
            <div class="control">
                <div class="select">
                    <select name="age_group_65">
                        <?php for ($i = 0 ; $i <= 20; $i++): ?>
                            <option value="<?= $i ?>" <?= ($postData['age_group_65'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Aantal personen 13-64 jaar</label>
            <div class="control">
                <div class="select">
                    <select name="age_group_13_64">
                        <?php for ($i = 0 ; $i <= 20; $i++): ?>
                            <option value="<?= $i ?>" <?= ($postData['age_group_13_64'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Aantal kinderen 0-12 jaar</label>
            <div class="control">
                <div class="select">
                    <select name="age_group_0_12">
                        <?php for ($i = 0 ; $i <= 20; $i++): ?>
                            <option value="<?= $i ?>" <?= ($postData['age_group_0_12'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <p class="errors">
                <?= isset($errors['age_groups']) ? htmlspecialchars($errors['age_groups']) : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Datum*</label>
            <div class="control">
                <input class="input" type="date" name="reservation_date" value="<?= htmlspecialchars($postData['reservation_date']) ?>">
            </div>
            <p class="errors">
                <?= isset($errors['reservation_date']) ? htmlspecialchars($errors['reservation_date']) : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Gewenste tijd*</label>
            <div class="control">
                <div class="select">
                    <select name="desired_time">
                        <?php foreach (['--:--', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00'] as $time): ?>
                            <option value="<?= $time ?>" <?= ($postData['desired_time'] == $time) ? 'selected' : '' ?>><?= $time ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="errors">
                    <?= isset($errors['desired_time']) ? htmlspecialchars($errors['desired_time']) : '' ?>
                </p>
            </div>
        </div>

        <div class="field">
            <div class="button">
                <button class="button is-primary" type="submit" name="submit">Ga verder met reserveren</button>
            </div>
        </div>
    </form>
    <div>
        <a class="reserveer" href="../index.php">Go back to the list </a>
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
    </div>
</footer>
</body>
</html>