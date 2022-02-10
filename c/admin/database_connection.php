<?php

//database_connection.php
$host = "localhost";
$db   = "johancardb";
$user = "root";
$pass = "";

//FOR MYSQLi
$dbCon = mysqli_connect($host,$user,$pass,$db);

//FOR PDO USE
$connect = new PDO("mysql:host=".$host.";dbname=".$db."", "".$user."", "".$pass."");



?>