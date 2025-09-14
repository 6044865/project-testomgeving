<?php

require_once "classWereldwonder.php";

if (!ingelogd() || !mag('kan_wonder_toevoegen')) {
    die("❌ Je hebt geen toestemming om een wereldwonder toe te voegen.");
}

$ww = new Wereldwonder();
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = $_POST['naam'];
    $beschrijving = $_POST['beschrijving'];
    $bouwjaar = !empty($_POST['bouwjaar']) ? $_POST['bouwjaar'] : null;
    $werelddeel = !empty($_POST['werelddeel']) ? $_POST['werelddeel'] : null;
    $type = !empty($_POST['type']) ? $_POST['type'] : null;
    $bestaat_nog = $_POST['bestaat_nog'] !== '' ? $_POST['bestaat_nog'] : null;
    $toegevoegd_door = $_SESSION['gebruiker_id'];
    $locatie = !empty($_POST['locatie']) ? $_POST['locatie'] : null;
    $latitude = !empty($_POST['latitude']) ? $_POST['latitude'] : null;
    $longitude = !empty($_POST['longitude']) ? $_POST['longitude'] : null;
    $status = !empty($_POST['status']) ? $_POST['status'] : null;
    $tags = !empty($_POST['tags']) ? $_POST['tags'] : null;

    try {
        $ww->wonderToevoegen($naam, $beschrijving, $bouwjaar, $werelddeel, $type, $bestaat_nog, $toegevoegd_door, $locatie, $latitude, $longitude, $status, $tags);

        $lastId = $ww->getConnection()->lastInsertId();
        $message = "✅ Wereldwonder succesvol toegevoegd! ID: $lastId";
    } catch (PDOException $e) {
        $message = "❌ Fout bij toevoegen: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuw Wereldwonder Toevoegen</title>
    <style>
        form { max-width: 600px; margin: 20px auto; display: flex; flex-direction: column; gap: 10px; }
        input, textarea, select, button { padding: 8px; font-size: 16px; }
        button { cursor: pointer; }
    </style>
</head>
<body>
<h1>Nieuw Wereldwonder Toevoegen</h1>

<?php if($message) echo "<p>$message</p>"; ?>

<form method="post">
    <label>Naam: <input type="text" name="naam" required></label>
    <label>Beschrijving: <textarea name="beschrijving" required></textarea></label>
    <label>Bouwjaar: <input type="number" name="bouwjaar"></label>
    <label>Werelddeel: <input type="text" name="werelddeel"></label>
    <label>Type:
        <select name="type" required>
            <option value="">-- Kies type --</option>
            <option value="klassiek">Klassiek</option>
            <option value="modern">Modern</option>
        </select>
    </label>
    <label>Bestaat nog:
        <select name="bestaat_nog">
            <option value="">-- select --</option>
            <option value="1">Ja</option>
            <option value="0">Nee</option>
        </select>
    </label>
    <label>Locatie: <input type="text" name="locatie"></label>
    <label>Latitude: <input type="text" name="latitude"></label>
    <label>Longitude: <input type="text" name="longitude"></label>
    <label>Status: <input type="text" name="status"></label>
    <label>Tags: <input type="text" name="tags"></label>

    <button type="submit">Toevoegen</button>
</form>
</body>
</html>
