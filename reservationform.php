<?php

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
            <label class="label">Naam*</label>
            <div class="control">
                <input class="input" type="text" name="name" value="<?= $valName ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['name']) ? $errors['name'] : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Email*</label>
            <div class="control">
                <input class="input" type="email" name="email" value="<?= $valEmail ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['email']) ? $errors['email'] : '' ?>
            </p>
        </div>
        <div class="field">
            <label class="label">Telefoonnummer*</label>
            <div class="control">
                <input class="input" type="text" name="phone" value="<?= $valPhone ?>">
            </div>
            <p class="help is-danger">
                <?= isset($errors['phone']) ? $errors['phone'] : '' ?>
            </p>
        </div>

        <div class="field">
            <label class="label">Opmerkingen</label>
            <div class="control">
                <textarea class="textarea" name="comment" value="<?= $valName ?>" ?> </textarea>
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
        <a class="button" href="index.php">Terug naar reserveren</a>
    </div>
</div>
</body>
</html>
