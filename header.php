<?php if (!isset($_SESSION)) { session_start(); } ?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chef's Choice</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <a class="logo" href="index.php">Chef's Choice</a>
    <nav>
       
        <a href="index.php">Home</a>
        <a href="gerechten.php">Gerechten</a>
        <a href="winkelwagen.php">Winkelwagen</a>
         <a href="review.php">Reviews</a>
        <?php if (isset($_SESSION['gebruiker_id'])): ?>
            <a href="account.php">Profile</a>
            <a href="logout.php">Uitloggen</a>
        <?php else: ?>
            <a href="login.php">Inloggen</a>
            <a href="registreer.php">Registreren</a>
        <?php endif; ?>
    </nav>
</header>
<main>
