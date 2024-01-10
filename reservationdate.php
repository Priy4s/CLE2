<?php

$errors = [];

if (isset($_POST['submit'])) {
    if (empty($_POST['age_group_65']) && empty($_POST['age_group_13_64']) && empty($_POST['age_group_0_12'])) {
        $errors['age_groups'] = "Selecteer minimaal één leeftijdsgroep.";
    }

    if ($_POST['reservation_date'] == '') {
        $errors['reservation_date'] = "Datum kan niet leeg zijn.";
    }


    if (empty($errors)) {
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
                            <option value="<?= $i ?>"><?= $i ?></option>
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
                            <option value="<?= $i ?>"><?= $i ?></option>
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
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
            <p class="help is-danger">
                <?= isset($errors['age_groups']) ? $errors['age_groups'] : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Datum</label>
            <div class="control">
                <input class="input" type="date" name="reservation_date" value="">
            </div>
            <p class="help is-danger">
                <?= isset($errors['reservation_date']) ? $errors['reservation_date'] : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Tijd (hardcode)</label>
            <div class="control">
                <input class="input" type="date" name="reservation_date" value="">
            </div>
            <p class="help is-danger">
                <?= isset($errors['time']) ? $errors['time'] : '' ?>
            </p>
        </div>


        <div class="field">
            <div class="control">
                <button class="button is-primary" type="submit" name="submit">Ga verder met reserveren</button>
            </div>
        </div>
    </form>
    <div>
        <a class="button" href="index.php">Go back to the list </a>
    </div>
</div>
</body>
</html>
