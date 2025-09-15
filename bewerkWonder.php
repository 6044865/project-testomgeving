<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bewerk Wereldwonder</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
</head>

<?php
require_once "includes/auth.php"; // sessie & login check
require_once "./classWereldwonder.php";
require_once "./DocumentClass.php";

$ww = new Wereldwonder();
$doc = new Document();
$message = "";

// Alleen beheerder en archivaris
if (!in_array($rol, ['beheerder', 'archivaris'])) {
    die("❌ Toegang geweigerd.");
}

// ID ophalen uit GET
$wonderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($wonderId <= 0) die("❌ Ongeldig wonder ID.");

// Haal details van het wonder
$selectedWonder = $ww->getWonderMetDetails($wonderId);
if (!$selectedWonder) die("❌ Wereldwonder niet gevonden.");

// Form submission: wereldwonder updaten
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

    if ($rol === 'beheerder') {
        $result = $ww->wonderUpdaten(
            $wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel,
            $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags
        );
    } elseif ($rol === 'archivaris') {
        $result = $ww->wonderUpdateArchivaris(
            $wonderId, $bouwjaar, $bestaat_nog, $locatie, $latitude, $longitude
        );
    }

    if ($result) {
        $message .= "<p style='color:green;'>✅ Wereldwonder succesvol bijgewerkt!</p>";
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    } else {
        $message .= "<p style='color:red;'>❌ Fout bij bijwerken.</p>";
    }

    // Document upload (voor archivaris & beheerder)
    if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
        $bestand = $_FILES['document'];
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $bestandPad = $uploadDir . time() . "_" . basename($bestand['name']);
        $bestandGrootte = $bestand['size'];
        $bestandType = $bestand['type'];
        $maxGrootte = 5 * 1024 * 1024; // 5MB

        if ($doc->checkBestandsgrootte($bestandGrootte, $maxGrootte)) {
            if (move_uploaded_file($bestand['tmp_name'], $bestandPad)) {
                $toegevoegd_door = $gebruiker_id;
                $toegevoegd_naam = $naam;

                $doc->documentToevoegen(
                    $wonderId,
                    $bestandPad,
                    $bestandType,
                    $bestandGrootte,
                    $toegevoegd_door,
                    $toegevoegd_naam
                );
                $message .= "<p style='color:green;'>✅ Document succesvol toegevoegd!</p>";
            } else {
                $message .= "<p style='color:red;'>❌ Uploaden mislukt.</p>";
            }
        } else {
            $message .= "<p style='color:red;'>❌ Bestand te groot (max 5MB).</p>";
        }
    }
}

// Haal alle documenten van dit wonder
$documenten = $doc->getDocumentenPerWonder($wonderId);
?>

<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>Bewerk Wereldwonder: <?= htmlspecialchars($selectedWonder['naam']) ?></h1>
<?= $message ?>

<form method="post" enctype="multipart/form-data">
    <?php if ($rol === 'beheerder'): ?>
        <label>Naam:</label>
        <input type="text" name="naam" value="<?= htmlspecialchars($selectedWonder['naam'] ?? '') ?>" required>

        <label>Beschrijving:</label>
        <textarea name="beschrijving" required><?= htmlspecialchars($selectedWonder['beschrijving'] ?? '') ?></textarea>

        <label>Werelddeel:</label>
        <input type="text" name="werelddeel" value="<?= htmlspecialchars($selectedWonder['werelddeel'] ?? '') ?>">

        <label>Type:</label>
        <select name="type" required>
            <option value="klassiek" <?= ($selectedWonder['type'] ?? '') === 'klassiek' ? 'selected' : '' ?>>Klassiek</option>
            <option value="modern" <?= ($selectedWonder['type'] ?? '') === 'modern' ? 'selected' : '' ?>>Modern</option>
            <option value="natuurlijk" <?= ($selectedWonder['type'] ?? '') === 'natuurlijk' ? 'selected' : '' ?>>Natuurlijk</option>
        </select>

        <label>Status:</label>
        <input type="text" name="status" value="<?= htmlspecialchars($selectedWonder['status'] ?? '') ?>">

        <label>Tags:</label>
        <input type="text" name="tags" value="<?= htmlspecialchars($selectedWonder['tags'] ?? '') ?>">
    <?php endif; ?>

    <!-- Velden die beide mogen aanpassen -->
    <label>Bouwjaar:</label>
    <input type="number" name="bouwjaar" value="<?= htmlspecialchars($selectedWonder['bouwjaar'] ?? '') ?>">

    <label>Bestaat nog:</label>
    <select name="bestaat_nog">
        <option value="1" <?= ($selectedWonder['bestaat_nog'] ?? '') == 1 ? 'selected' : '' ?>>Ja</option>
        <option value="0" <?= ($selectedWonder['bestaat_nog'] ?? '') == 0 ? 'selected' : '' ?>>Nee</option>
    </select>

    <label>Locatie:</label>
    <input type="text" name="locatie" value="<?= htmlspecialchars($selectedWonder['locatie'] ?? '') ?>">

    <label>Latitude:</label>
    <input type="text" name="latitude" value="<?= htmlspecialchars($selectedWonder['latitude'] ?? '') ?>">

    <label>Longitude:</label>
    <input type="text" name="longitude" value="<?= htmlspecialchars($selectedWonder['longitude'] ?? '') ?>">

    <hr>
    <h2>Document toevoegen</h2>
    <input type="file" name="document">
    <p>Max. bestandsgrootte: 5MB</p>

    <button type="submit" name="submit_form">Opslaan</button>
</form>

<hr>
<h2>Bestaande documenten</h2>
<?php if ($documenten): ?>
    <ul>
    <?php foreach ($documenten as $d): ?>
        <li>
            <a href="<?= htmlspecialchars($d['bestandspad']) ?>" target="_blank">
                <?= htmlspecialchars(basename($d['bestandspad'])) ?> (<?= htmlspecialchars($d['type']) ?>)
            </a>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Geen documenten toegevoegd.</p>
<?php endif; ?>

</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>
