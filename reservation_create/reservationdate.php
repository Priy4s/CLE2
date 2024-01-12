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
        $_SESSION['reservation_date'] = $_POST['reservation_date'];
        $_SESSION['age_group_65'] = $_POST['age_group_65'];
        $_SESSION['age_group_13_64'] = $_POST['age_group_13_64'];
        $_SESSION['age_group_0_12'] = $_POST['age_group_0_12'];
        $amount = $_POST['age_group_65'] + $_POST['age_group_13_64'] + $_POST['age_group_0_12'];
        $_SESSION['amount'] = $amount;
        //format time properly
        $format_time = DateTime::createFromFormat('H:i', $_POST['desired_time']);
        $sqlTime = $format_time->format('H:i:s');
        $_SESSION['desired_time'] = $sqlTime;
        header("Location: reservationform.php");
        exit();
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Resevering</title>
</head>
<body>
<div class="container px-4">
    <h1 class="title mt-4">Reserveer</h1>
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
            <p class="help is-danger">
                <?= isset($errors['age_groups']) ? $errors['age_groups'] : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Datum*</label>
            <div class="control">
                <input class="input" type="date" name="reservation_date" value="<?= $postData['reservation_date'] ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['reservation_date']) ? $errors['reservation_date'] : '' ?>
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
                <p class="help is-danger">
                    <?= isset($errors['desired_time']) ? $errors['desired_time'] : '' ?>
                </p>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit" name="submit">Ga verder met reserveren</button>
            </div>
        </div>
    </form>
    <div>
        <a class="button" href="../index.php">Go back to the list </a>
    </div>
</div>
</body>
</html>