

<?php
require_once "./includes/auth.php";

$rol  = $_SESSION['user_role'] ?? 'bezoeker';
$naam = $_SESSION['username'] ?? 'Gast';
?>

<h1>Welkom op je dashboard, <?php echo ucfirst($naam); ?>! (<?php echo ucfirst($rol); ?>)</h1>

<?php if ($rol === "onderzoeker"): ?>
    <p>Je kunt nieuwe wereldwonderen toevoegen.</p>
    <a href="toevoegen.php">➕ Toevoegen</a>
<?php endif; ?>

<?php if ($rol === "redacteur"): ?>
    <p>Hier zie je de bijdragen die je moet goedkeuren.</p>
    <a href="goedkeuren.php">✅ Goedkeuren</a>
<?php endif; ?>

<?php if ($rol === "beheerder"): ?>
    <p>Beheerdersopties:</p>
    <ul>
        <li><a href="gebruikers.php">👤 Gebruikersbeheer</a></li>
        <li><a href="logs.php">📜 Logbestanden</a></li>
    </ul>
<?php endif; ?>

