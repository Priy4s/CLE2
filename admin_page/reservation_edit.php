<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cle_2';

$db = mysqli_connect($host, $username, $password, $database) or die('Error: ' . mysqli_connect_error());

$reservationId = mysqli_real_escape_string($db, $_GET['id']);

// Edit Reservation
if (isset($_POST['edit'])) {
    $newName = isset($_POST['new_name']) ? mysqli_real_escape_string($db, $_POST['new_name']) : '';
    $newDate = isset($_POST['new_date']) ? mysqli_real_escape_string($db, $_POST['new_date']) : '';
    $newTime = isset($_POST['new_time']) ? mysqli_real_escape_string($db, $_POST['new_time']) : '';
    $newEmail = isset($_POST['new_email']) ? mysqli_real_escape_string($db, $_POST['new_email']) : '';
    $newPhoneNr = isset($_POST['new_phoneNr']) ? mysqli_real_escape_string($db, $_POST['new_phoneNr']) : '';
    $newComment = isset($_POST['new_comment']) ? mysqli_real_escape_string($db, $_POST['new_comment']) : '';

    // Perform the update query
    $updateQuery = "UPDATE reservations SET 
                    name = '$newName', 
                    date = '$newDate',
                    time = '$newTime',
                    email = '$newEmail', 
                    phonenr = '$newPhoneNr',
                    comment = '$newComment'
                    WHERE id = '$reservationId'";
    mysqli_query($db, $updateQuery);

    // Redirect back to the reservation list after editing
    header('Location: reservations_view.php');
    exit();
}

// Fetch reservation details
if (isset($_GET['id'])) {
    // Deeplinking protection
    $reservationId = mysqli_real_escape_string($db, $_GET['id']);

    $query = "SELECT * FROM reservations WHERE id = '$reservationId'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $reservation = mysqli_fetch_assoc($result);
    } else {
        // Redirects to the reservation list if no valid reservation ID is provided
        header('Location: reservation_view.php');
        exit();
    }
} else {
    // Redirects to the reservation list if no valid reservation ID is provided
    header('Location: reservation_view.php');
    exit();
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Wijzig Reservering</title>
</head>
<body>
<div class="container px-4">

    <section class="columns is-centered">
        <div class="column is-10">
            <h2 class="title mt-4">Edit Reservation</h2>

            <form class="column is-6" action="reservation_edit.php?id=<?= $reservationId ?>" method="post"
                  onsubmit="return confirm('Are you sure you want to save changes?');">

                <!-- Name -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="date">Naam</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="name" type="text" name="new_name"
                                       value="<?php echo isset($inputValues['new_name']) ? $inputValues['new_name'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorName) ? $errorName : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Date -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="date">Datum</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="date" type="date" name="new_date"
                                       value="<?php echo isset($inputValues['new_date']) ? $inputValues['new_date'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorDate) ? $errorDate : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Time -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="date">Tijd</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="time" type="time" name="new_time"
                                       value="<?php echo isset($inputValues['new_time']) ? $inputValues['new_time'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorTime) ? $errorTime : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="capacity">Email</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="email" type="email" name="new_email"
                                       value="<?php echo isset($inputValues['new_email']) ? $inputValues['new_email'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorEmail) ? $errorEmail : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Telefoon Nr. -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="people">Telefoon Nr.</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="phoneNr" type="text" name="new_phoneNr"
                                       value="<?php echo isset($inputValues['new_phoneNr']) ? $inputValues['new_phoneNr'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorPhoneNr) ? $errorPhoneNr : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Comment -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="capacity">Opmerking</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="textarea" id="comment" type="text" name="new_comment"
                                       value="<?php echo isset($inputValues['new_comment']) ? $inputValues['new_comment'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorComment) ? $errorComment : ''; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal"></div>
                    <div class="field-body">
                        <button class="button is-link is-fullwidth" type="submit" name="edit">Sla Verandering Op</button>
                    </div>
                </div>
            </form>

            <hr>

            <form action="reservation_edit.php?id=<?= $reservationId ?>" method="post"
                  onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                <input type="hidden" name="reservation_id" value="<?= $reservationId ?>">
            </form>

            <a class="button mt-4" href="../reservation_create/reservationform.php">&laquo; Go back to form</a>
        </div>
    </section>
</div>
</body>
</html>
