<?php
include 'config.php';

if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];
    $email = $_POST['email'];

    $stmt = $conn->prepare('UPDATE gebruikers SET naam = ?, email = ? WHERE id = ?');
    $stmt->bind_param('ssi', $naam, $email, $_SESSION['gebruiker_id']);
    $stmt->execute();

    $_SESSION['flashMessage'] = "gelukt";

    header('Location: account.php');
    exit;
}

?>