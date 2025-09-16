<?php
require_once "./includes/auth.php";
require_once "classWereldwonder.php";
require_once "./FotoClass.php";
require_once "./DocumentClass.php";

// Alleen beheerders mogen toevoegen
$rol_clean = strtolower(trim($rol));
if (!in_array($rol_clean, ['beheerder'])) {
    die("❌ Toegang geweigerd.");
}

$ww = new Wereldwonder();
$foto = new Foto();
$document = new Document();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';

    $bouwjaar = !empty($_POST['bouwjaar']) ? (int)$_POST['bouwjaar'] : null;
    $werelddeel = !empty($_POST['werelddeel']) ? $_POST['werelddeel'] : null;
    $type = !empty($_POST['type']) ? $_POST['type'] : null;
    $bestaat_nog = isset($_POST['bestaat_nog']) && $_POST['bestaat_nog'] !== '' ? (int)$_POST['bestaat_nog'] : null;
    $locatie = !empty($_POST['locatie']) ? $_POST['locatie'] : null;
    $latitude = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

    $status = $_POST['status'] ?? null;
    $tags = $_POST['tags'] ?? null;

    $toegevoegd_door_id = $_SESSION['gebruiker_id'];
    $toegevoegd_door_naam = $_SESSION['username'];

    try {
        // Wereldwonder toevoegen
        $ww->wonderToevoegen(
            $naam,
            $beschrijving,
            $bouwjaar,
            $werelddeel,
            $type,
            $bestaat_nog,
            $toegevoegd_door_id,
            $locatie,
            $latitude,
            $longitude,
            $status,
            $tags
        );
        $wonderId = $ww->getConnection()->lastInsertId();

        if ($wonderId > 0) {
            $message .= "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd! ID: $wonderId</p>";

            // -----------------
            // Foto-upload (alleen beheerders)
            // -----------------
            if (!empty($_FILES['fotos']['name'][0])) {
                foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                    $filename = basename($_FILES['fotos']['name'][$key]);
                    $target = "uploads/fotos/" . $filename;
                    if (!is_dir("uploads/fotos")) mkdir("uploads/fotos", 0755, true);

                    if (move_uploaded_file($tmp_name, $target)) {
                        $foto->fotoToevoegenBeheerder($wonderId, $target, $toegevoegd_door_naam);
                    }
                }
            }

            // -----------------
            // Document-upload
            // -----------------
            if (!empty($_FILES['docs']['name'][0])) {
                foreach ($_FILES['docs']['tmp_name'] as $key => $tmp_name) {
                    $filename = basename($_FILES['docs']['name'][$key]);
                    $target = "uploads/docs/" . $filename;
                    if (!is_dir("uploads/docs")) mkdir("uploads/docs", 0755, true);

                    if (move_uploaded_file($tmp_name, $target)) {
                        $document->documentToevoegen(
                            $wonderId,
                            $target,
                            $_FILES['docs']['type'][$key],
                            $_FILES['docs']['size'][$key],
                            $toegevoegd_door_id,
                            $toegevoegd_door_naam
                        );
                    }
                }
            }

        } else {
            $message .= "<p style='color:red;'>❌ Fout: Wereldwonder is niet toegevoegd!</p>";
        }

    } catch (PDOException $e) {
        $message .= "<p style='color:red;'>❌ Fout bij toevoegen: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nieuw Wereldwonder Toevoegen</title>
<link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
<h1>Nieuw Wereldwonder Toevoegen</h1>

<?= $message ?>

<form method="post" enctype="multipart/form-data">
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

    <label>Documenten uploaden (meerdere):</label>
    <input type="file" name="docs[]" multiple>

    <label>Foto's uploaden (meerdere):</label>
    <input type="file" name="fotos[]" multiple>

    <button type="submit">Toevoegen</button>
</form>
</main>
<?php include "./includes/footer.php"; ?>
</body>
</html>
