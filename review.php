<?php
include 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}else{


$db = new mysqli("localhost", "root", "", "chefs_choice");

$melding = '';

if (isset($_GET['success'])) {
    $melding = "Review geplaatst!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $naam = $_POST['naam'];
    $beoordeling = $_POST['beoordeling'];
    $bericht = $_POST['bericht'];

    $stmt = $db->prepare("INSERT INTO reviews (product_id, naam, beoordeling, bericht) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isis", $product_id, $naam, $beoordeling, $bericht);

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
exit;
    } else {
        $melding = "Er ging iets fout.";
    }
}

$producten = $db->query("SELECT id, naam FROM producten ORDER BY naam");

$reviews = $db->query("
    SELECT reviews.*, producten.naam AS product_naam
    FROM reviews
    JOIN producten ON reviews.product_id = producten.id
    ORDER BY reviews.id DESC
");
?>

<h1>Reviews</h1>

<?php if ($melding): ?>
    <p class="message"><?= htmlspecialchars($melding) ?></p>
<?php endif; ?>

<div class="reviews-layout">

    <form method="POST" class="review-form">
        <label>Product</label>
        <select name="product_id" required>
            <?php while ($product = $producten->fetch_assoc()): ?>
                <option value="<?= $product['id'] ?>">
                    <?= htmlspecialchars($product['naam']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Naam</label>
        <input type="text" name="naam" required>

        <label>Beoordeling</label>
        <select name="beoordeling" required>
            <option value="5">5 sterren</option>
            <option value="4">4 sterren</option>
            <option value="3">3 sterren</option>
            <option value="2">2 sterren</option>
            <option value="1">1 ster</option>
        </select>

        <label>Review</label>
        <textarea class="textarea1" name="bericht" required></textarea>

        <button class="button" type="submit">Plaats review</button>
    </form>

    <div class="reviews-side">
        <h2>Alle reviews</h2>

        <div class="review-list">
            <?php while ($review = $reviews->fetch_assoc()): ?>
                <div class="review-card">
                    <h3><?= htmlspecialchars($review['product_naam']) ?></h3>
                    <p><strong>Naam:</strong> <?= htmlspecialchars($review['naam']) ?></p>
                    <p><strong>Beoordeling:</strong> <?= str_repeat("★", $review['beoordeling']) ?></p>
                    <p><?= htmlspecialchars($review['bericht']) ?></p>
                    <small><?= htmlspecialchars($review['datum']) ?></small>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

<?php } include 'footer.php';  ?>