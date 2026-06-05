<?php
include 'config.php';

if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

$winkelwagen = $_SESSION['winkelwagen'] ?? [];

if (empty($winkelwagen)) {
    header('Location: winkelwagen.php');
    exit;
}

$totaal = $_SESSION['totaal'] ?? 0;
$korting = $_SESSION['korting'] ?? 0;
$kortingscode = $_SESSION['kortingscode'] ?? null;
$gebruikerId = $_SESSION['gebruiker_id'];

$stmt = $conn->prepare("INSERT INTO bestellingen (gebruiker_id, totaal, kortingscode, korting) VALUES (?, ?, ?, ?)");
$stmt->bind_param('idsd', $gebruikerId, $totaal, $kortingscode, $korting);
$stmt->execute();
$bestellingId = $conn->insert_id;

foreach ($winkelwagen as $gerechtId => $aantal) {
    $stmt = $conn->prepare("SELECT prijs FROM gerechten WHERE id = ?");
    $stmt->bind_param('i', $gerechtId);
    $stmt->execute();
    $gerecht = $stmt->get_result()->fetch_assoc();

    if ($gerecht) {
        $prijs = $gerecht['prijs'];
        $stmt = $conn->prepare("INSERT INTO bestelling_regels (bestelling_id, gerecht_id, aantal, prijs) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('iiid', $bestellingId, $gerechtId, $aantal, $prijs);
        $stmt->execute();
    }
}

unset($_SESSION['winkelwagen'], $_SESSION['kortingscode'], $_SESSION['totaal'], $_SESSION['korting']);
header('Location: bevestiging.php');
exit;
?>
