<?php
require_once "./includes/auth.php";
require_once "./classWereldwonder.php";
require_once "./DocumentClass.php";
require_once "./FotoClass.php";

if ($rol !== 'onderzoeker') {
    die("❌ Toegang geweigerd. Alleen voor onderzoekers.");
}

$ww = new Wereldwonder();
$doc = new Document();
$foto = new Foto();

$onderzoekerId = $_SESSION['gebruiker_id'];

// Haal alle wonderen van deze onderzoeker op
$wonderen = $ww->getWonderenDoorGebruiker($onderzoekerId);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Onderzoeker Beheer</title>
<link rel="stylesheet" href="./css/stylesheet.css">
<style>
body {
    font-family: Arial, sans-serif;
}
main {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}
h1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}
.add-button {
    background-color: #b2a287ff;
    color: white;
    padding: 6px 10px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 0.85em;
}
.wonder-container {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 20px;
}
.wonder-card {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 15px;
    background: #f9f9f9;
    flex: 1 1 calc(33.333% - 20px); /* 3 kaarten per rij */
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
}
.wonder-card h2 {
    margin-top: 0;
}
.wonder-card p {
    margin: 10px 0;
    flex-grow: 1;
}
.actions {
    margin-top: 10px;
}
.actions a {
    display: inline-block;
    margin-right: 10px;
    padding: 6px 10px;
    background-color: #b2a287ff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 0.85em;
}
.actions a.download {
    background-color: #b2a287ff;
}
@media (max-width: 900px) {
    .wonder-card {
        flex: 1 1 calc(50% - 20px); /* 2 kaarten per rij */
    }
}
@media (max-width: 600px) {
    .wonder-card {
        flex: 1 1 100%; /* 1 kaart per rij */
    }
}
</style>
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>
    Mijn Wereldwonderen
    <a href="toevoegenWonderOnderzoeker.php" class="add-button">➕ Voeg Wonder Toe</a>
</h1>

<div class="wonder-container">
<?php if ($wonderen): ?>
    <?php foreach ($wonderen as $w): ?>
        <div class="wonder-card">
            <h2><?= htmlspecialchars($w['naam']) ?></h2>
            <p><?= htmlspecialchars($w['beschrijving']) ?></p>
            <div class="actions">
                <a href="bewerkWonderOnderzoeker.php?id=<?= $w['wonder_id'] ?>">✏️ Bewerken</a>
                <a href="downloadWonder.php?id=<?= $w['wonder_id'] ?>" class="download">⬇️ Download</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Je hebt nog geen wereldwonderen toegevoegd.</p>
<?php endif; ?>
</div>

</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>
