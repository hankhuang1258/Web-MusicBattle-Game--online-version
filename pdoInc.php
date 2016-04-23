<?php
$db_server = "localhost";
$db_user = "kenny0429";
$db_passwd = "960288921";
$db_name = "kenny0429_music";
 
$dsn = "mysql:host=$db_server;dbname=$db_name";
$dbh = new PDO($dsn, $db_user, $db_passwd);
 
$dbh->exec("SET NAMES utf8");
?>