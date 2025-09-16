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
$message = "";

// Wonder ID ophalen
$wonderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($wonderId <= 0) die("❌ Ongeldig wonder ID.");

// Haal details van het wonder
$selectedWonder = $ww->getWonderMetDetails($wonderId);
if (!$selectedWonder) die("❌ Wereldwonder niet gevonden.");

// Controle: onderzoeker mag alleen eigen wonderen bewerken
if ($selectedWonder['toegevoegd_door'] != $gebruiker_id) {
    die("❌ Je mag alleen je eigen wereldwonderen bewerken.");
}

// Formulier verwerking
if (isset($_POST['submit_form'])) {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $locatie = $_POST['locatie'] ?? '';
    $latitude = isset($_POST['latitude']) && $_POST['latitude'] !== '' ? (float)$_POST['latitude'] : null;
    $longitude = isset($_POST['longitude']) && $_POST['longitude'] !== '' ? (float)$_POST['longitude'] : null;

    $result = $ww->wonderUpdateOnderzoeker($wonderId, $naam, $beschrijving, $locatie, $latitude, $longitude);
    if ($result) {
        $message .= "<p style='color:green;'>✅ Wereldwonder succesvol bijgewerkt!</p>";
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    } else {
        $message .= "<p style='color:red;'>❌ Fout bij bijwerken.</p>";
    }
}

// Foto uploaden
if (isset($_POST['upload_foto']) && isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = "uploads/fotos/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $bestandsnaam = time() . "_" . basename($_FILES['foto']['name']);
    $bestandPad = $uploadDir . $bestandsnaam;

    if (move_uploaded_file($_FILES['foto']['tmp_name'], $bestandPad)) {
        // Voeg foto toe in database, standaard 0 = niet goedgekeurd
        $foto->fotoToevoegen($wonderId, $bestandPad, 0);
        $message .= "<p style='color:green;'>✅ Foto succesvol toegevoegd!</p>";
    } else {
        $message .= "<p style='color:red;'>❌ Fout bij uploaden van de foto.</p>";
    }
}

// Foto verwijderen
if (isset($_POST['delete_foto_id'])) {
    $fotoId = (int)$_POST['delete_foto_id'];
    $foto->fotoVerwijderen($fotoId);
    $message .= "<p style='color:green;'>✅ Foto verwijderd!</p>";
}

// Haal documenten en fotos op
$documenten = $doc->getDocumentenPerWonder($wonderId);
$fotos = $foto->getFotosPerWonder($wonderId);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bewerk Wereldwonder Onderzoeker</title>
<link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>Bewerk Wereldwonder: <?= htmlspecialchars($selectedWonder['naam']) ?></h1>
<?= $message ?>

<!-- Wonder bewerken -->
<form method="post">
    <label>Naam:</label>
    <input type="text" name="naam" value="<?= htmlspecialchars($selectedWonder['naam'] ?? '') ?>" required>

    <label>Beschrijving:</label>
    <textarea name="beschrijving" required><?= htmlspecialchars($selectedWonder['beschrijving'] ?? '') ?></textarea>

    <label>Locatie:</label>
    <input type="text" name="locatie" value="<?= htmlspecialchars($selectedWonder['locatie'] ?? '') ?>">

    <label>Latitude:</label>
    <input type="text" name="latitude" value="<?= htmlspecialchars($selectedWonder['latitude'] ?? '') ?>">

    <label>Longitude:</label>
    <input type="text" name="longitude" value="<?= htmlspecialchars($selectedWonder['longitude'] ?? '') ?>">

    <button type="submit" name="submit_form">Opslaan</button>
</form>

<hr>

<!-- Foto uploaden -->
<h2>Foto toevoegen</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="foto" required>
    <button type="submit" name="upload_foto">Upload Foto</button>
</form>

<hr>

<!-- Bestaande foto's -->
<h2>Bestaande foto's</h2>
<?php if ($fotos): ?>
    <ul>
    <?php foreach ($fotos as $f): ?>
        <li>
            <img src="<?= htmlspecialchars($f['bestandspad']) ?>" alt="" style="max-width:150px;">
            <form method="post" style="display:inline;">
                <input type="hidden" name="delete_foto_id" value="<?= $f['foto_id'] ?>">
                <button type="submit">🗑️ Verwijderen</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Geen foto's toegevoegd.</p>
<?php endif; ?>

</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>
