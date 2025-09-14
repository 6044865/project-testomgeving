
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wereldwonderen</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="../project-testomgeving/js/index.js" defer></script>
  
    <meta name="description" 
      content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
<meta name="keywords" 
      content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">



    <meta name="author" content="A.Alhaji, G.Verpaalen">

</head>

<?php
require_once "includes/auth.php"; // sessie & login check


// Alleen beheerders mogen aanpassen
if ($rol !== 'beheerder') {
    die("❌ Toegang geweigerd. Alleen voor beheerders.");
}

require_once "./classWereldwonder.php";
$ww = new Wereldwonder();
$message = "";

// ID ophalen uit GET
$wonderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($wonderId <= 0) {
    die("❌ Ongeldig wonder ID.");
}

// Haal de details van het wonder op
$selectedWonder = $ww->getWonderMetDetails($wonderId);
if (!$selectedWonder) {
    die("❌ Wereldwonder niet gevonden.");
}

// Handle form submission
if (isset($_POST['submit_form'])) {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $bouwjaar = isset($_POST['bouwjaar']) && $_POST['bouwjaar'] !== '' ? (int)$_POST['bouwjaar'] : null;
    $werelddeel = $_POST['werelddeel'] ?? null;
    $type = $_POST['type'] ?? null;
    $bestaat_nog = isset($_POST['bestaat_nog']) && $_POST['bestaat_nog'] !== '' ? (int)$_POST['bestaat_nog'] : null;
    $locatie = $_POST['locatie'] ?? null;
    $latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? (float)$_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? (float)$_POST['longitude'] : null;
    $status = $_POST['status'] ?? null;
    $tags = $_POST['tags'] ?? null;

    $result = $ww->wonderUpdaten(
        $wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel,
        $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags
    );

    if ($result) {
        $message = "<p style='color:green;'>✅ Wereldwonder succesvol bijgewerkt!</p>";
        // Herlaad details
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    } else {
        $message = "<p style='color:red;'>❌ Fout bij bijwerken.</p>";
    }
}
?>

<body>
    
<?php
include "./includes/header.php";
?>
<main>
<h1>Bewerk Wereldwonder: <?= htmlspecialchars($selectedWonder['naam']) ?></h1>

<?= $message ?>

<form method="post">
    <label>Naam:</label>
    <input type="text" name="naam" value="<?= htmlspecialchars($selectedWonder['naam'] ?? '') ?>" required>

    <label>Beschrijving:</label>
    <textarea name="beschrijving" required><?= htmlspecialchars($selectedWonder['beschrijving'] ?? '') ?></textarea>

    <label>Bouwjaar:</label>
    <input type="number" name="bouwjaar" value="<?= htmlspecialchars($selectedWonder['bouwjaar'] ?? '') ?>">

    <label>Werelddeel:</label>
    <input type="text" name="werelddeel" value="<?= htmlspecialchars($selectedWonder['werelddeel'] ?? '') ?>">

    <label>Type:</label>
    <select name="type" required>
        <option value="">-- Kies type --</option>
        <option value="klassiek" <?= ($selectedWonder['type'] ?? '') === 'klassiek' ? 'selected' : '' ?>>Klassiek</option>
        <option value="modern" <?= ($selectedWonder['type'] ?? '') === 'modern' ? 'selected' : '' ?>>Modern</option>
        <option value="natuurlijk" <?= ($selectedWonder['type'] ?? '') === 'natuurlijk' ? 'selected' : '' ?>>Natuurlijk</option>
    </select>

    <label>Bestaat nog:</label>
    <select name="bestaat_nog">
        <option value="">-- select --</option>
        <option value="1" <?= ($selectedWonder['bestaat_nog'] ?? '') == 1 ? 'selected' : '' ?>>Ja</option>
        <option value="0" <?= ($selectedWonder['bestaat_nog'] ?? '') == 0 ? 'selected' : '' ?>>Nee</option>
    </select>

    <label>Locatie:</label>
    <input type="text" name="locatie" value="<?= htmlspecialchars($selectedWonder['locatie'] ?? '') ?>">

    <label>Latitude:</label>
    <input type="text" name="latitude" value="<?= htmlspecialchars($selectedWonder['latitude'] ?? '') ?>">

    <label>Longitude:</label>
    <input type="text" name="longitude" value="<?= htmlspecialchars($selectedWonder['longitude'] ?? '') ?>">

    <label>Status:</label>
    <input type="text" name="status" value="<?= htmlspecialchars($selectedWonder['status'] ?? '') ?>">

    <label>Tags:</label>
    <input type="text" name="tags" value="<?= htmlspecialchars($selectedWonder['tags'] ?? '') ?>">

    <button type="submit" name="submit_form">Opslaan</button>
</form>
</main>
<?php
include "./includes/footer.php";
?>
</body>
</html>
