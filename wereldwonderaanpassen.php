<?php
require_once "classDatabase.php";
session_start();
var_dump($_GET, $_POST);

// check login
if(!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true){
    header("location: login.php");
    exit();
}


$rol = strtolower($_SESSION['rol'] ?? '');
$wonder_id = $_POST['wonder_id'] ?? $_GET['id'] ?? null;

if(!$wonder_id){
    die("Geen wereldwonder geselecteerd.");
}

try {
    $db = new Database();
    $conn = $db->getConnection();
    

    // LET OP: vervang 'wereldwonder_id' door de juiste kolomnaam van je PK
    $stmt = $conn->prepare("SELECT * FROM wereldwonderen WHERE wonder_id = ?");
    $stmt->execute([$wonder_id]);
    $wonder = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$wonder){
        die("Wereldwonder niet gevonden.");
    }
} catch (PDOException $e) {
    die("Fout bij ophalen: " . $e->getMessage());
}

// rechten per rol
$rechten = [
    "onderzoeker" => ["naam", "beschrijving"],
    "archivaris"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
    "beheerder"   => ["naam","beschrijving","bouwjaar","bestaat_nog","status","tags","wereldeel","locatie","latitude","longitude"]
];

$toegestaan = $rechten[$rol] ?? [];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wereldwonderen</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="../project-testomgeving/js/index.js" defer></script>
    <!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

 
    <meta name="description"
      content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
<meta name="keywords"
      content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">
 
 
 
    <meta name="author" content="A.Alhaji, G.Verpaalen">
    <meta charset="UTF-8">
    <title>Wereldwonder Aanpassen</title>
    <style>
        form { max-width: 600px; margin: 20px auto; display: flex; flex-direction: column; gap: 10px; }
        input, textarea, select, button { padding: 8px; font-size: 16px; }
        button { cursor: pointer; }
    </style>
</head>

<?php
require_once "classWereldwonder.php";
require_once "./includes/header.php";
?>
<body>
<h1>Wereldwonder Aanpassen</h1>

<form method="post" action="wereldwonderOpslaan.php">
    <input type="hidden" name="wonder_id" value="<?= (int)$wonder['wonder_id'] ?>">

    <label>Naam:
        <input type="text" name="naam" value="<?= htmlspecialchars($wonder['naam']) ?>" 
               <?= in_array('naam', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Beschrijving:
        <textarea name="beschrijving" <?= in_array('beschrijving', $toegestaan) ? '' : 'disabled' ?>><?= htmlspecialchars($wonder['beschrijving']) ?></textarea>
    </label>

    <label>Bouwjaar:
        <input type="number" name="bouwjaar" value="<?= htmlspecialchars($wonder['bouwjaar']) ?>" 
               <?= in_array('bouwjaar', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Werelddeel:
        <input type="text" name="werelddeel" value="<?= htmlspecialchars($wonder['werelddeel']) ?>" 
               <?= in_array('wereldeel', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Bestaat nog:
        <select name="bestaat_nog" <?= in_array('bestaat_nog', $toegestaan) ? '' : 'disabled' ?>>
            <option value="">-- select --</option>
            <option value="1" <?= $wonder['bestaat_nog'] == 1 ? 'selected' : '' ?>>Ja</option>
            <option value="0" <?= $wonder['bestaat_nog'] == 0 ? 'selected' : '' ?>>Nee</option>
        </select>
    </label>

    <label>Locatie:
        <input type="text" name="locatie" value="<?= htmlspecialchars($wonder['locatie']) ?>" 
               <?= in_array('locatie', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Latitude:
        <input type="text" name="latitude" value="<?= htmlspecialchars($wonder['latitude']) ?>" 
               <?= in_array('latitude', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Longitude:
        <input type="text" name="longitude" value="<?= htmlspecialchars($wonder['longitude']) ?>" 
               <?= in_array('longitude', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Status:
        <input type="text" name="status" value="<?= htmlspecialchars($wonder['status']) ?>" 
               <?= in_array('status', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <label>Tags:
        <input type="text" name="tags" value="<?= htmlspecialchars($wonder['tags']) ?>" 
               <?= in_array('tags', $toegestaan) ? '' : 'disabled' ?>>
    </label>

    <button type="submit">Opslaan</button>
</form>


<?php
require_once "./includes/footer.php";
?>
</body>
</html>
