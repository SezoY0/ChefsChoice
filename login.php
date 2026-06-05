<?php
include 'config.php';
$fout = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    $stmt = $conn->prepare("SELECT * FROM gebruikers WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $gebruiker = $stmt->get_result()->fetch_assoc();

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
        $_SESSION['gebruiker_id'] = $gebruiker['id'];
        $_SESSION['naam'] = $gebruiker['naam'];
        header('Location: index.php');
        exit;
    } else {
        $fout = 'E-mailadres of wachtwoord klopt niet.';
    }
}

include 'header.php';
?>

<h1>Inloggen</h1>
<form class="form-card" method="POST">
    <?php if ($fout): ?><p class="error"><?= htmlspecialchars($fout) ?></p><?php endif; ?>
    <label>E-mailadres</label>
    <input type="email" name="email" required>

    <label>Wachtwoord</label>
    <input type="password" name="wachtwoord" required>

    <button class="button" type="submit">Inloggen</button>
</form>

<?php include 'footer.php'; ?>
