<?php
require_once "./includes/auth.php";
require_once "./classWereldwonder.php";

if ($rol !== "redacteur") die("❌ Geen toegang.");

$ww = new Wereldwonder();
$wonderId = (int)($_GET['id'] ?? 0);
if (!$wonderId) die("❌ Ongeldig wonder ID.");

$selectedWonder = $ww->getWonderMetDetails($wonderId); // Bestaande methode gebruiken

$message = "";

// Formulier verwerking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $locatie = $_POST['locatie'] ?? '';
    $tags = $_POST['tags'] ?? '';

    $result = $ww->updateWonderRedacteur($wonderId,  $locatie, $tags);
    if ($result) {
        $message = "<p style='color:green;'>✅ Wereldwonder succesvol bijgewerkt!</p>";
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    } else {
        $message = "<p style='color:red;'>❌ Fout bij bijwerken.</p>";
    }
}

?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Bewerk Wereldwonder</title>
<link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>Bewerk Wereldwonder: <?= htmlspecialchars($selectedWonder['naam']) ?></h1>
<?= $message ?>
<form method="post">
 
    


    <label>Locatie:</label>
    <input type="text" name="locatie" value="<?= htmlspecialchars($selectedWonder['locatie']) ?>">

    <label>Tags:</label>
    <input type="text" name="tags" value="<?= htmlspecialchars($selectedWonder['tags']) ?>">

    <button type="submit">Opslaan</button>
</form>
</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>