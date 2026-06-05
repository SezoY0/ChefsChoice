<?php
include 'config.php';

if (!isset($_SESSION['gebruiker_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare('SELECT naam, email FROM gebruikers WHERE id = ?');
$stmt->bind_param('i', $_SESSION['gebruiker_id']);
$stmt->execute();
$result = $stmt->get_result();
$gebruiker = $result->fetch_assoc();

$flashMessage = $_SESSION['flashMessage'];
unset($_SESSION['flashMessage']);
?>

<?php include 'header.php'?>
<?php if ($flashMessage){?>
<div class="message">
    <p>Account succesvol bij gewerkt</p>
</div>
<?php }?>
<h1>Account</h1>
<form class="form-card" method="POST" action="update-account.php">
    <label>naam</label>
    <input type="text" name="naam" value="<?= htmlspecialchars($gebruiker['naam']) ?>">

    <label>E-mailadres</label>
    <input type="email" name="email" required value="<?= htmlspecialchars($gebruiker['email']) ?>">

    <button class="button" type="submit">Update</button>
</form>

<?php include 'footer.php'?>