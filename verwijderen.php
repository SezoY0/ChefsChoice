<?php
include 'config.php';
$id = (int)($_GET['id'] ?? 0);
unset($_SESSION['winkelwagen'][$id]);
header('Location: winkelwagen.php');
exit;
?>
