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
 
<body class="home_pagina">
 
<?php
require_once "./includes/auth.php";
include "./includes/header.php";
require_once "classDatabase.php";
require_once "classWereldwonder.php";
 
 
// $toegevoegd_door = $_SESSION['gebruiker_id']; // numeriek, klopt met database
$ww = new Wereldwonder();
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verplichte velden
    $naam = $_POST['naam'];
    $beschrijving = $_POST['beschrijving'];
 
    // Optionele velden: als leeg, dan null
    $bouwjaar = !empty($_POST['bouwjaar']) ? $_POST['bouwjaar'] : null;
    $werelddeel = !empty($_POST['werelddeel']) ? $_POST['werelddeel'] : null;
    $type = !empty($_POST['type']) ? $_POST['type'] : null;
    $bestaat_nog = $_POST['bestaat_nog'] !== '' ? $_POST['bestaat_nog'] : null;
    $toegevoegd_door =  $_SESSION['gebruiker_id']; // haal later uit sessie
    $locatie = !empty($_POST['locatie']) ? $_POST['locatie'] : null;
    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;
    $status = !empty($_POST['status']) ? $_POST['status'] : null;
    $tags = !empty($_POST['tags']) ? $_POST['tags'] : null;
 
    try {
        // Voeg wereldwonder toe
        $ww->wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door, $locatie, $latitude, $longitude, $status, $tags);
 
       $lastId = $ww->getConnection()->lastInsertId();
if($lastId > 0) {
    echo "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd! ID: $lastId</p>";
} else {
    echo "<p style='color:red;'>❌ Fout: Wereldwonder is niet toegevoegd!</p>";
}
 
 
        echo "<p style='color:green;'>✅ Wereldwonder succesvol toegevoegd! ID: $lastId</p>";
 
        // Optioneel: upload foto's
        if (!empty($_FILES['fotos']['name'][0])) {
            foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
                $filename = basename($_FILES['fotos']['name'][$key]);
                $target = "uploads/fotos/" . $filename;
                if (!is_dir("uploads/fotos")) mkdir("uploads/fotos", 0755, true);
                if (move_uploaded_file($tmp_name, $target)) {
                    $stmt = $ww->getConnection()->prepare(
                        "INSERT INTO fotos (wonder_id, bestandspad, goedgekeurd, toegevoegd_door)
                         VALUES (:wonder_id, :bestandspad, 1, :toegevoegd_door)"
                    );
                    $stmt->execute([
                        ':wonder_id' => $lastId,
                        ':bestandspad' => $target,
                        ':toegevoegd_door' => $toegevoegd_door
                    ]);
                }
            }
        }
 
        // Optioneel: upload documenten
        if (!empty($_FILES['documenten']['name'][0])) {
            foreach ($_FILES['documenten']['tmp_name'] as $key => $tmp_name) {
                $filename = basename($_FILES['documenten']['name'][$key]);
                $type = pathinfo($filename, PATHINFO_EXTENSION);
                $target = "uploads/documenten/" . $filename;
                if (!is_dir("uploads/documenten")) mkdir("uploads/documenten", 0755, true);
                if (move_uploaded_file($tmp_name, $target)) {
                    $stmt = $ww->getConnection()->prepare(
                        "INSERT INTO documenten (wonder_id, bestandspad, type, toegevoegd_door)
                         VALUES (:wonder_id, :bestandspad, :type, :toegevoegd_door)"
                    );
                    $stmt->execute([
                        ':wonder_id' => $lastId,
                        ':bestandspad' => $target,
                        ':type' => $type,
                        ':toegevoegd_door' => $toegevoegd_door
                    ]);
                }
            }
        }
 
    } catch (PDOException $e) {
        echo "<p style='color:red;'>❌ Fout bij toevoegen: " . $e->getMessage() . "</p>";
    }
}
?>
 
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wereldwonder Toevoegen</title>
    <style>
        form { max-width: 600px; margin: 20px auto; display: flex; flex-direction: column; gap: 10px; }
        input, textarea, select { padding: 8px; font-size: 16px; }
        button { padding: 10px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Nieuw Wereldwonder Toevoegen</h1>
  <form method="post" enctype="multipart/form-data">
    <label for="naam">Naam:</label>
    <input type="text" id="naam" name="naam" required>
 
    <label for="beschrijving">Beschrijving:</label>
    <textarea id="beschrijving" name="beschrijving" required></textarea>
 
    <label for="bouwjaar">Bouwjaar:</label>
    <input type="number" id="bouwjaar" name="bouwjaar">
 
    <label for="werelddeel">Werelddeel:</label>
    <input type="text" id="werelddeel" name="werelddeel">
 
    <label for="type">Type:</label>
<select id="type" name="type" required>
    <option value="">-- Kies type --</option>
    <option value="klassiek">Klassiek</option>
    <option value="modern">Modern</option>
</select>
 
    <label for="bestaat_nog">Bestaat nog:</label>
    <select id="bestaat_nog" name="bestaat_nog">
        <option value="">-- select  --</option>
        <option value="1">Ja</option>
        <option value="0">Nee</option>
    </select>
 
    <label for="locatie">Locatie:</label>
    <input type="text" id="locatie" name="locatie">
 
    <label for="latitude">Latitude:</label>
    <input type="text" id="latitude" name="latitude">
 
    <label for="longitude">Longitude:</label>
    <input type="text" id="longitude" name="longitude">
 
    <label for="status">Status:</label>
    <input type="text" id="status" name="status">
 
    <label for="tags">Tags (comma separated):</label>
    <input type="text" id="tags" name="tags">
 
    <label for="fotos">Foto's uploaden (meerdere mogelijk):</label>
    <input type="file" id="fotos" name="fotos[]" multiple>
 
    <label for="documenten">Documenten uploaden (meerdere mogelijk):</label>
    <input type="file" id="documenten" name="documenten[]" multiple>
 
    <button type="submit">Toevoegen</button>
</form>
</body>
</html>
<?php
include "./includes/footer.php"
?>