<?php
require_once "./includes/auth.php";  
require_once "./classWereldwonder.php";
require_once "./FotoClass.php";

// Alleen onderzoekers mogen deze pagina gebruiken
if ($rol !== 'onderzoeker') {
    die("❌ Toegang geweigerd. Alleen voor onderzoekers.");
}

$ww = new Wereldwonder();
$foto = new Foto();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam         = $_POST['naam'] ?? '';
    $beschrijving = $_POST['beschrijving'] ?? '';

    $bouwjaar     = !empty($_POST['bouwjaar']) ? (int)$_POST['bouwjaar'] : null;
    $werelddeel   = !empty($_POST['werelddeel']) ? $_POST['werelddeel'] : null;
    $type         = !empty($_POST['type']) ? $_POST['type'] : null;
    $bestaat_nog  = ($_POST['bestaat_nog'] !== '') ? (int)$_POST['bestaat_nog'] : null;
    $locatie      = !empty($_POST['locatie']) ? $_POST['locatie'] : null;
    $latitude     = !empty($_POST['latitude']) ? (float)$_POST['latitude'] : null;
    $longitude    = !empty($_POST['longitude']) ? (float)$_POST['longitude'] : null;

    // automatisch ingevuld
    $toegevoegd_door = $_SESSION['gebruiker_id']; 

    try {
        // Wonder toevoegen (onderzoeker mag geen status/tags meegeven)
        $ww->wonderToevoegenOnderzoeker(
            $naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog,
            $toegevoegd_door, $locatie, $latitude, $longitude, null, null
        );

        $wonderId = $ww->getConnection()->lastInsertId();

        if ($wonderId > 0) {
            $message .= "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd! ID: $wonderId</p>";

            // Foto’s uploaden
            if (!empty($_FILES['fotos']['name'][0])) {
                foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                    $filename = time() . "_" . basename($_FILES['fotos']['name'][$key]);
                    $target = "uploads/fotos/" . $filename;

                    if (!is_dir("uploads/fotos")) mkdir("uploads/fotos", 0755, true);

                    if (move_uploaded_file($tmp_name, $target)) {
                        // Foto wordt toegevoegd door onderzoeker → standaard niet goedgekeurd (0)
                        $foto->fotoToevoegen($wonderId, $target, 0); 
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
<title>Nieuw Wereldwonder Toevoegen (Onderzoeker)</title>
<link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>

<main>
<h1>Nieuw Wereldwonder Toevoegen (Onderzoeker)</h1>

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
    <select name="type" required>
        <option value="">-- Kies type --</option>
        <option value="klassiek">Klassiek</option>
        <option value="modern">Modern</option>
    </select>

    <label>Bestaat nog:</label>
    <select name="bestaat_nog">
        <option value="">-- selecteer --</option>
        <option value="1">Ja</option>
        <option value="0">Nee</option>
    </select>

    <label>Locatie:</label>
    <input type="text" name="locatie">

    <label>Latitude:</label>
    <input type="text" name="latitude">

    <label>Longitude:</label>
    <input type="text" name="longitude">

    <label>Foto's uploaden (meerdere mogelijk):</label>
    <input type="file" name="fotos[]" multiple>

    <button type="submit">Toevoegen</button>
</form>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
