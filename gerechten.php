<?php
include 'config.php';
include 'header.php';

$zoek = $_GET['zoek'] ?? '';
$categorie = $_GET['categorie'] ?? '';

$sql = "SELECT * FROM producten WHERE 1";
$params = [];
$types = '';

if ($zoek !== '') {
    $sql .= " AND naam LIKE ?";
    $params[] = "%$zoek%";
    $types .= 's';
}

if ($categorie !== '') {
    $sql .= " AND categorie = ?";
    $params[] = $categorie;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$gerechten = $stmt->get_result();
$categorieen = $conn->query("SELECT DISTINCT categorie FROM producten ORDER BY categorie");
?>

<h1>Producten</h1>
<p>Kies een product en voeg deze toe aan je winkelwagen.</p>

<form class="filter-box" method="GET">
    <input type="text" name="zoek" placeholder="Zoek Product..." value="<?= htmlspecialchars($zoek) ?>">
    <select name="categorie">
        <option value="">Alle categorieën</option>
        <?php while ($cat = $categorieen->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($cat['categorie']) ?>" <?= $categorie === $cat['categorie'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['categorie']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button class="button" type="submit">Zoeken</button>
</form>

<div class="product-grid">
    <?php while ($gerecht = $gerechten->fetch_assoc()): ?>
        <article class="product-card">
            <div class="food-image"><img 
               alt="<?= htmlspecialchars($gerecht['naam']) ?>"
               src="<?= htmlspecialchars($gerecht['afbeelding'])?>"></div>
               

            <h2><?= htmlspecialchars($gerecht['naam']) ?></h2>
            <p><?= htmlspecialchars($gerecht['beschrijving']) ?></p>
            <p class="price">€ <?= number_format($gerecht['prijs'], 2, ',', '.') ?></p>
            <p class="category"><?= htmlspecialchars($gerecht['categorie']) ?></p>
            <a class="button secondary" href="gerecht-detail.php?id=<?= $gerecht['id'] ?>">Details</a>
            <a class="button" href="toevoegen.php?id=<?= $gerecht['id'] ?>">Toevoegen</a>
        </article>
    <?php endwhile; ?>
</div>

<?php include 'footer.php'; ?>
