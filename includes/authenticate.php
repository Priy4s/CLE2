<?php

//May I visit this page?
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");}
//Make the variable "id" the current user id
$id = $_SESSION['user_id']['id'];

?>