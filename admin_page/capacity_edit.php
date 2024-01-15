<?php
session_start();
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'cle_2';

$db = mysqli_connect($host, $username, $password, $database) or die('Error: ' . mysqli_connect_error());

$daycapacityId = mysqli_real_escape_string($db, $_GET['id']);

// Capacity deletion
if (isset($_POST['delete'])) {
    $daycapacityId = $_POST['daycapacity_id'];

    // Perform the deletion query
    $deleteQuery = "DELETE FROM day_capacities WHERE id = '$daycapacityId'";
    mysqli_query($db, $deleteQuery);

    // Redirect back to the capacity list after deletion
    header('Location: capacity_view.php');
    exit();
}

// Edit Capacity
if (isset($_POST['edit'])) {
    $newDate = isset($_POST['new_date']) ? mysqli_real_escape_string($db, $_POST['new_date']) : '';
    $newCapacity = isset($_POST['new_capacity']) ? mysqli_real_escape_string($db, $_POST['new_capacity']) : '';
    $newPeople = isset($_POST['new_people']) ? mysqli_real_escape_string($db, $_POST['new_people']) : '';

    // Perform the update query
    $updateQuery = "UPDATE day_capacities SET 
                    date = '$newDate', 
                    capacity = '$newCapacity', 
                    people = '$newPeople'
                    WHERE id = '$daycapacityId'";
    mysqli_query($db, $updateQuery);

    // Redirect back to the capacity list after editing
    header('Location: capacity_view.php');
    exit();
}

// Fetch day capacity details
if (isset($_GET['id'])) {
    // Deeplinking protection
    $daycapacityId = mysqli_real_escape_string($db, $_GET['id']);

    $query = "SELECT * FROM day_capacities WHERE id = '$daycapacityId'";
    $result = mysqli_query($db, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $daycapacity = mysqli_fetch_assoc($result);
    } else {
        // Redirects to the capacity list if no valid day capacity ID is provided
        header('Location: capacity_view.php');
        exit();
    }
} else {
    // Redirects to the capacity list if no valid day capacity ID is provided
    header('Location: capacity_view.php');
    exit();
}

// Authentication check
/*if (!isset($_SESSION['user'])) {
    // Redirect to login page if user isn't logged in
    header('Location: login.php');
    exit();
}*/

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
            <h2 class="title mt-4">Edit Capacity</h2>

            <form class="column is-6" action="capacity_edit.php?id=<?= $daycapacityId ?>" method="post"
                  onsubmit="return confirm('Are you sure you want to save changes?');">

                <!-- Capacity -->
                <div class="field is-horizontal">
                    <div class="field-label is-normal">
                        <label class="label" for="capacity">Capaciteit</label>
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <input class="input" id="capacity" type="text" name="new_capacity"
                                       value="<?php echo isset($inputValues['new_capacity']) ? $inputValues['new_capacity'] : ''; ?>"/>
                            </div>
                            <p class="help is-danger">
                                <?php echo isset($errorCapacity) ? $errorCapacity : ''; ?>
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

            <form action="capacity_edit.php?id=<?= $daycapacityId ?>" method="post"
                  onsubmit="return confirm('Are you sure you want to delete this reservation?');">
                <input type="hidden" name="daycapacity_id" value="<?= $daycapacityId ?>">
                <button class="button is-danger" type="submit" name="delete">Verwijder Reservering</button>
            </form>

            <a class="button mt-4" href="capacity_view.php">&laquo; Go back to list</a>
        </div>
    </section>
</div>
</body>
</html>
