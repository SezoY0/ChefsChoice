<?php
include 'config.php';
$fout = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $email = $_POST['email'] ?? '';
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if ($naam === '' || $email === '' || $wachtwoord === '') {
        $fout = 'Vul alle velden in.';
    } else {
        $hash = password_hash($wachtwoord, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO gebruikers (naam, email, wachtwoord) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $naam, $email, $hash);

        if ($stmt->execute()) {
            $_SESSION['gebruiker_id'] = $conn->insert_id;
            $_SESSION['naam'] = $naam;
            header('Location: index.php');
            exit;
        } else {
            $fout = 'Dit e-mailadres bestaat al.';
        }
    }
}

include 'header.php';
?>

<h1>Registreren</h1>
<form class="form-card" method="POST">
    <?php if ($fout): ?><p class="error"><?= htmlspecialchars($fout) ?></p><?php endif; ?>
    <label>Naam</label>
    <input type="text" name="naam" required>

    <label>E-mailadres</label>
    <input type="email" name="email" required>

    <label>Wachtwoord</label>
    <input type="password" name="wachtwoord" required>

    <button class="button" type="submit">Account aanmaken</button>
</form>

<?php include 'footer.php'; ?>
