
<?php
session_start();

/** @var mysqli $db */
require_once '../includes/database.php';

//$query = "SELECT * FROM day_capacities";

// Get today's date
$tomorrow = date("Y-m-d");

$query = "
SELECT r.date, c.capacity, SUM(r.people) as total_people
FROM cle_2.reservations r
JOIN cle_2.day_capacities c ON r.date = c.date
WHERE r.date = '$tomorrow'
GROUP BY r.date;
";

$result = mysqli_query($db, $query)
or die('Error '.mysqli_error($db).' with query '.$query);

//         Er wordt een nieuwe array gemaakt waarin alle
//         rijen uit de db komen. In dit geval is een rij een datum.
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

$login = false;
// Is user logged in?
if (isset($_SESSION['user_id'])) {
    $login = true;
}

if (isset($_POST['submit'])) {
    /** @var mysqli $db */
    require_once "../includes/database.php";

    // Get form data
    $username = mysqli_escape_string($db, $_POST['username']);
    $password = $_POST['password'];

    // Server-side validation
    $errors = [];
    if ($username == '') {
        $errors['username'] = 'Missing username';
    }
    if ($password == '') {
        $errors['password'] = 'Missing password';
    }

    // If data valid
    if (empty($errors)) {
        // SELECT the user from the database, based on the username.
        $query = "SELECT * FROM admin_users WHERE admin_username='$username'";
        $result = mysqli_query($db, $query);

        // check if the user exists
        if (mysqli_num_rows($result) == 1) {
            // Get user data from result
            $user = mysqli_fetch_assoc($result);

            // Check if the provided password matches the stored password in the database
            if (password_verify($password, $user['admin_password'])) {
                $login = true;

                // Store the user in the session
                $_SESSION['user_id'] = [
                    'id' => $user['id'],
                    'admin_username' => $user['admin_username'],
                ];

                // Redirect to secure page
            } else {
                //error incorrect log in
                $errors['loginFailed'] = 'Wrong credentials.';
            }
        } else {
            //error incorrect log in
            $errors['loginFailed'] = 'Wrong credentials.';
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
    <title>Sense of China - ADMIN</title>
</head>

<body>
<?php if ($login) { ?>
    <section class="section is-medium">
        <div class="container">
            <section class="section is-medium">
                <div class="box is-max-desktop">
                    <nav class="level">
                        <div class="level-item has-text-centered">
                            <a class="button is-light is-fullwidth" href="reservations_view.php">Reserveringen</a>
                        </div>

                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Welkom Admin</p>
                                <p class="title is-size-1">Sense of China</p>
                            </div>
                        </div>

                        <div class="level-item has-text-centered">
                            <a class="button is-light is-fullwidth" href="capacity_view.php">Capaciteit</a>
                        </div>
                    </nav>

                    <hr>

                    <br>

                    <nav>

                        <div class="level-item has-text-centered">
                            <a class="button is-danger is-fullwidth" href="logout.php">Logout</a>
                        </div>
                        <section>
                                <p>‎ </p>
                        </section>
                        <section class="columns is-centered">
                            <table>
                                <thead>
                                <tr>
                                    <th class="has-text-centered">Personen</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                // $daycapacities wordt doorlopen met een foreach loop en zo kunnen de onderdelen
                                //                        // getoond worden.
                                foreach ($dayCapacities as $dayCapacity) { ?>
                                    <tr>
                                        <td>Vandaag <?= isset($dayCapacity['total_people']) ? $dayCapacity['total_people'] : '' ?>/<?= isset($dayCapacity['capacity']) ? $dayCapacity['capacity'] : '' ?></td>
                                    </tr>
                                    <?php
                                }
                                mysqli_close($db);
                                ?>
                                </tbody>
                            </table>
                        </section>
                    </nav>

                </div>



            </section>
        </div>
    </section>

<?php } else { ?>
    <section class="section is-medium">
        <div class="container is-max-desktop">
            <section class="section is-medium">
                <div class="box is-max-desktop">
                    <h2 class="title">Admin login</h2>
                    <form action="" method="post">
                        <div class="field">
                            <label class="label" for="email">Username</label>
                            <div class="control has-icons-left">
                                <input class="input" id="email" placeholder="username" type="text" name="username" value="<?= $email ?? '' ?>">
                                <span class="icon is-small is-left"><i class="fa-solid fa-user"></i></span>
                                <span class="help is-danger">
                                            <?= $errors['username'] ?? '' ?>
                                        </span>
                            </div>
                        </div>

                        <div class="field">
                            <label class="label" for="password">Password</label>
                            <div class="control has-icons-left">
                                <input class="input" id="password" placeholder="Password" type="password" name="password"/>
                                <span class="icon is-small is-left"><i class="fa-solid fa-lock"></i></i></span>
                                <span class="help is-danger">
                                            <?= $errors['password'] ?? '' ?>
                                        </span>
                                <?php if(isset($errors['loginFailed'])) { ?>
                                    <div class="notification is-danger is-light">
                                        <button class="delete"></button>
                                        <?=$errors['loginFailed']?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div class="field is-grouped">
                            <p class="control">
                                <button class="button is-primary is-fullwidth" type="submit" name="submit">Sign in</button>
                            </p>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </section>

<?php } ?>
</body>
</html>