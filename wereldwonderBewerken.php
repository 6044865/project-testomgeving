<?php
require_once "includes/auth.php";
require_once "classWereldwonder.php";

$ww = new Wereldwonder();

// lijst van alle wonderen (alleen ID en naam)
$wonderen = $ww->getAlleWonderen();

$selectedWonder = null;

if (isset($_POST['select_wonder'])) {
    $wonderId = (int)$_POST['wonder_id'];
    if ($wonderId > 0) {
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    }
}

if ($selectedWonder && isset($_POST['submit_form'])) {
    $wonderId = (int)$_POST['wonder_id'];
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

    try {
        $ww->wonderUpdaten(
            $wonderId, $naam, $beschrijving, $bouwjaar, $werelddeel,
            $type, $bestaat_nog, $locatie, $latitude, $longitude, $status, $tags
        );
        echo "<p style='color:green;'>✅ Wereldwonder succesvol bijgewerkt!</p>";
        $selectedWonder = $ww->getWonderMetDetails($wonderId);
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij updaten: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bewerk Wereldwonder</title>
    <style>
        form { max-width: 600px; margin: 20px auto; display: flex; flex-direction: column; gap: 10px; }
        input, textarea, select { padding: 8px; font-size: 16px; }
        button { padding: 10px; font-size: 16px; cursor: pointer; }
        img { max-width: 150px; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
<h1>Bewerk Wereldwonder</h1>

<form method="post">
    <label>Kies een Wereldwonder:</label>
    <select name="wonder_id" onchange="this.form.submit()">
        <option value="">-- Kies --</option>
        <?php foreach ($wonderen as $w): ?>
            <option value="<?= $w['wonder_id'] ?>" <?= ($selectedWonder && $w['wonder_id'] == $selectedWonder['wonder_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($w['naam']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <input type="hidden" name="select_wonder">
</form>

<?php if ($selectedWonder): ?>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="wonder_id" value="<?= $selectedWonder['wonder_id'] ?>">

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

    <h3>Bestaande Foto's:</h3>
    <?php if (!empty($selectedWonder['fotos'])): ?>
        <?php foreach ($selectedWonder['fotos'] as $foto): ?>
            <img src="<?= htmlspecialchars($foto['bestandspad'] ?? '') ?>" alt="Foto">
        <?php endforeach; ?>
    <?php else: ?>
        <p>Geen foto's beschikbaar</p>
    <?php endif; ?>

    <h3>Bestaande Documenten:</h3>
    <?php if (!empty($selectedWonder['docs'])): ?>
        <ul>
            <?php foreach ($selectedWonder['docs'] as $doc): ?>
                <li><a href="<?= htmlspecialchars($doc['bestandspad'] ?? '') ?>" target="_blank">Document (<?= htmlspecialchars($doc['type'] ?? '') ?>)</a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Geen documenten beschikbaar</p>
    <?php endif; ?>

    <button type="submit" name="submit_form">Opslaan</button>
</form>
<?php endif; ?>
</body>
</html>
