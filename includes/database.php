<?php

$host = "localhost";
$user = "prj_2023_2024_ressys_t6";
$password = "diedaego";
$database = "prj_2023_2024_ressys_t6";

$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());;
