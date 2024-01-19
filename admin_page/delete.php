<?php
//connect to database
/** @var mysqli $db */
require_once "../includes/database.php";

//GET id
$id = $_GET['id'];

$query = "DELETE FROM reservations WHERE id = $id";
mysqli_query($db, $query);
mysqli_close($db);
//Redirect to homepage after deletion & exit script
header('Location: reservations_view.php');
?>


