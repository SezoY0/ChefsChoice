<?php
include 'config.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    if (!isset($_SESSION['winkelwagen'])) {
        $_SESSION['winkelwagen'] = [];
    }

    if (isset($_SESSION['winkelwagen'][$id])) {
        $_SESSION['winkelwagen'][$id]++;
    } else {
        $_SESSION['winkelwagen'][$id] = 1;
    }
}

header('Location: winkelwagen.php');
exit;
?>
