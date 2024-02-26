<?php
// Votre code de connexion à la DB
$dns = 'mysql:dbname=exam2602;host=localhost';
$user = 'root';
$password = 'root';

$dbh = new PDO($dns,$user,$password);
?>