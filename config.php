<?php
session_start();

$host = 'localhost';
$dbnaam = 'chefs_choice';
$gebruiker = 'root';
$wachtwoord = '';

$conn = new mysqli($host, $gebruiker, $wachtwoord, $dbnaam);

if ($conn->connect_error) {
    die('Database verbinding mislukt: ' . $conn->connect_error);
}
?>
