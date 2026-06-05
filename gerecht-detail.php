<?php
include 'config.php';
include 'header.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM producten WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$producten = $stmt->get_result()->fetch_assoc();

if (!$producten) {
    echo '<p>Gerecht niet gevonden.</p>';
    include 'footer.php';
    exit;
}
?>

<section class="detail-card">
    <div class="food-image-big"><img src="<?= htmlspecialchars($producten['afbeelding']) ?>"></div>
    <div>
        <h1><?= htmlspecialchars($producten['naam']) ?></h1>
        <p><?= htmlspecialchars($producten['beschrijving']) ?></p>
        <p class="price">€ <?= number_format($producten['prijs'], 2, ',', '.') ?></p>
        <p>Categorie: <?= htmlspecialchars($producten['categorie']) ?></p>
        <a class="button" href="toevoegen.php?id=<?= $gerecht['id'] ?>">Toevoegen aan winkelwagen</a>
    </div>
</section>

<?php include 'footer.php'; ?>
