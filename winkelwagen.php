<?php
include 'config.php';
include 'header.php';

$winkelwagen = $_SESSION['winkelwagen'] ?? [];
$totaal = 0;
$korting = 0;
$melding = '';
$kortingscode = $_POST['kortingscode'] ?? ($_SESSION['kortingscode'] ?? '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['kortingscode'] = $kortingscode;
}
?>

<h1>Winkelwagen</h1>

<?php if (empty($winkelwagen)): ?>
    <p>Je winkelwagen is leeg.</p>
    <a class="button" href="gerechten.php">Bekijk gerechten</a>
<?php else: ?>
    <table class="cart-table">
        <tr>
            <th>Gerecht</th>
            <th>Prijs</th>
            <th>Aantal</th>
            <th>Subtotaal</th>
            <th>Actie</th>
        </tr>
        <?php foreach ($winkelwagen as $id => $aantal): ?>
            <?php
            $stmt = $conn->prepare("SELECT * FROM producten WHERE id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $gerecht = $stmt->get_result()->fetch_assoc();
            if (!$gerecht) { continue; }
            $subtotaal = $gerecht['prijs'] * $aantal;
            $totaal += $subtotaal;
            ?>
            <tr>
                <td><?= htmlspecialchars($gerecht['naam']) ?></td>
                <td>€ <?= number_format($gerecht['prijs'], 2, ',', '.') ?></td>
                <td>
                    <a href="aantal.php?id=<?= $id ?>&actie=min">-</a>
                    <?= $aantal ?>
                    <a href="aantal.php?id=<?= $id ?>&actie=plus">+</a>
                </td>
                <td>€ <?= number_format($subtotaal, 2, ',', '.') ?></td>
                <td><a href="verwijderen.php?id=<?= $id ?>">Verwijderen</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
    if ($kortingscode !== '') {
        $stmt = $conn->prepare("SELECT * FROM kortingscodes WHERE code = ? AND actief = 1");
        $stmt->bind_param('s', $kortingscode);
        $stmt->execute();
        $code = $stmt->get_result()->fetch_assoc();

        if ($code) {
            if ($code['type'] === 'percentage') {
                $korting = $totaal * ($code['waarde'] / 100);
            } else {
                $korting = min($code['waarde'], $totaal);
            }
            $melding = 'Kortingscode is toegepast.';
        } else {
            $melding = 'Kortingscode is ongeldig.';
            $_SESSION['kortingscode'] = '';
            $kortingscode = '';
        }
    }

    $nieuwTotaal = $totaal - $korting;
    $_SESSION['totaal'] = $nieuwTotaal;
    $_SESSION['korting'] = $korting;
    ?>

    <form class="discount-box" method="POST">
        <label>Kortingscode</label>
        <input type="text" name="kortingscode" placeholder="Bijv. CHEF10" value="<?= htmlspecialchars($kortingscode) ?>">
        <button class="button secondary" type="submit">Toepassen</button>
    </form>

    <?php if ($melding): ?>
        <p class="message"><?= htmlspecialchars($melding) ?></p>
    <?php endif; ?>

    <div class="total-box">
        <p>Totaal: € <?= number_format($totaal, 2, ',', '.') ?></p>
        <p>Korting: € <?= number_format($korting, 2, ',', '.') ?></p>
        <h2>Te betalen: € <?= number_format($nieuwTotaal, 2, ',', '.') ?></h2>
    </div>

    <a class="button secondary" href="gerechten.php">Verder winkelen</a>
    <a class="button" href="bestellen.php">Bestelling plaatsen</a>
<?php endif; ?>

<?php include 'footer.php'; ?>
