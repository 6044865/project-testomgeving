<?php
require_once "./includes/auth.php";
require_once "classWereldwonder.php";
require_once "./FotoClass.php";
require_once "DocumentClass.php";

// Alleen beheerders
$rol_clean = strtolower(trim($rol));
if ($rol_clean !== 'beheerder') die("❌ Toegang geweigerd.");

$ww = new Wereldwonder();
$foto = new Foto();
$document = new Document();
$message = "";
$wonderId = null; // Voor uploadformulier

$toegevoegd_door_id = $_SESSION['gebruiker_id'];
$toegevoegd_door_naam = $_SESSION['username'];

// -------------------------
// Stap 1: Wereldwonder toevoegen
// -------------------------
if (isset($_POST['add_wonder'])) {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';
    $bouwjaar = !empty($_POST['bouwjaar']) ? (int)$_POST['bouwjaar'] : null;
    $werelddeel = $_POST['werelddeel'] ?? null;
    $type = $_POST['type'] ?? null;
    $bestaat_nog = isset($_POST['bestaat_nog']) ? (int)$_POST['bestaat_nog'] : null;
    $locatie = $_POST['locatie'] ?? null;
    $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;
    $status = $_POST['status'] ?? null;
    $tags = $_POST['tags'] ?? null;

    try {
        $ww->wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door_id, $locatie, $latitude, $longitude, $status, $tags);
        $wonderId = $ww->getConnection()->lastInsertId();
        if ($wonderId > 0) {
            $message .= "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd!</p>";
                // $message .= "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd! ID: $wonderId</p>";
        } else {
            $message .= "<p style='color:red;'>❌ Wereldwonder toevoegen mislukt.</p>";
        }
    } catch (PDOException $e) {
        $message .= "<p style='color:red;'>❌ Fout bij toevoegen: " . $e->getMessage() . "</p>";
    }
}

// -------------------------
// Stap 2: Foto's & Documenten uploaden
// -------------------------
if (isset($_POST['upload_files']) && isset($_POST['wonder_id'])) {
    $wonderId = (int)$_POST['wonder_id'];

    // Foto upload
    if (!empty($_FILES['fotos']['name'][0])) {
        $uploadDir = "uploads/fotos/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
            $originalName = basename($_FILES['fotos']['name'][$key]);
            $filename = time() . "_" . $originalName;
            $target = $uploadDir . $filename;

            if (move_uploaded_file($tmp_name, $target)) {
                if ($foto->fotoToevoegenBeheerder($wonderId, $target, $toegevoegd_door_id)) {
                    $message .= "<p style='color:green;'>✅ Foto toegevoegd: $originalName</p>";
                } else {
                    $message .= "<p style='color:red;'>❌ Fout bij toevoegen foto in database: $originalName</p>";
                }
            } else {
                $message .= "<p style='color:red;'>❌ Fout bij uploaden van: $originalName</p>";
            }
        }
    }

    // Document upload
    if (!empty($_FILES['docs']['name'][0])) {
        $uploadDir = "uploads/docs/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        foreach ($_FILES['docs']['tmp_name'] as $key => $tmp_name) {
            $filename = basename($_FILES['docs']['name'][$key]);
            $target = $uploadDir . $filename;

            if (move_uploaded_file($tmp_name, $target)) {
                $document->documentToevoegen($wonderId, $target, $_FILES['docs']['type'][$key], $_FILES['docs']['size'][$key], $toegevoegd_door_id, $toegevoegd_door_naam);
                $message .= "<p style='color:green;'>✅ Document toegevoegd: $filename</p>";
            } else {
                $message .= "<p style='color:red;'>❌ Fout bij uploaden document: $filename</p>";
            }
        }
    }
    header("location: wonderBeheer.php");
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Nieuw Wereldwonder Toevoegen</title>
<link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>Nieuw Wereldwonder Toevoegen</h1>
<?= $message ?>

<!-- Stap 1: Wereldwonder -->
<form method="post">
    <input type="hidden" name="add_wonder" value="1">
    <label>Naam:</label>
    <input type="text" name="naam" required>
    <label>Beschrijving:</label>
    <textarea name="beschrijving" required></textarea>
    <label>Bouwjaar:</label>
    <input type="number" name="bouwjaar">
    <label>Werelddeel:</label>
    <input type="text" name="werelddeel">
    <label>Type:</label>
    <select name="type">
        <option value="">-- Kies type --</option>
        <option value="klassiek">Klassiek</option>
        <option value="modern">Modern</option>
        <option value="natuurlijk">Natuurlijk</option>
    </select>
    <label>Bestaat nog:</label>
    <select name="bestaat_nog">
        <option value="">-- select --</option>
        <option value="1">Ja</option>
        <option value="0">Nee</option>
    </select>
    <label>Locatie:</label>
    <input type="text" name="locatie">
    <label>Latitude:</label>
    <input type="text" name="latitude">
    <label>Longitude:</label>
    <input type="text" name="longitude">
    <label>Status:</label>
    <input type="text" name="status">
    <label>Tags:</label>
    <input type="text" name="tags">
    <button type="submit">Wereldwonder Toevoegen</button>
</form>

<?php if ($wonderId): ?>
<!-- Stap 2: Upload foto's & documenten -->
<h2>Foto's & Documenten Uploaden</h2>
<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="upload_files" value="1">
    <input type="hidden" name="wonder_id" value="<?= $wonderId ?>">
    <label>Foto's uploaden :</label>
    <input type="file" name="fotos[]" multiple>
    <label>Documenten uploaden:</label>
    <input type="file" name="docs[]" multiple>
    <button type="submit">Uploaden</button>
</form>
<?php endif; ?>

</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>
