<?php
include 'config.php';

$id = (int)($_GET['id'] ?? 0);
$actie = $_GET['actie'] ?? '';

if (isset($_SESSION['winkelwagen'][$id])) {
    if ($actie === 'plus') {
        $_SESSION['winkelwagen'][$id]++;
    }

    if ($actie === 'min') {
        $_SESSION['winkelwagen'][$id]--;
        if ($_SESSION['winkelwagen'][$id] <= 0) {
            unset($_SESSION['winkelwagen'][$id]);
        }
    }
}

header('Location: winkelwagen.php');
exit;
?>
