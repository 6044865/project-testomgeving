<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wereldwonder detail</title>

    <!-- FontAwesome -->
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="../project-testomgeving/js/index.js" defer></script>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <meta name="description"
        content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
    <meta name="keywords"
        content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">
    <meta name="author" content="A.Alhaji, G.Verpaalen">
</head>
<body>
<?php
require_once "classWereldwonder.php";
require_once "./includes/header.php";

// 1. ID ophalen uit URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "<p>Geen geldig wereldwonder geselecteerd.</p>";
    require_once "./includes/footer.php";
    exit;
}

// 2. Wereldwonder object maken
$wereldwonderObj = new Wereldwonder();

// 3. Info tonen (inclusief foto's & documenten)
$wereldwonderObj->toonInfoPerWonder($id);

// 4. Voor kaart: losse array ophalen
$wonder = $wereldwonderObj->getWonderMetDetails($id);

if (!$wonder) {
    echo "<p>Geen wonder gevonden!</p>";
    require_once "./includes/footer.php";
    exit;
}
?>

<!-- Kaart -->
<div id="kaart" style="height: 400px; width: 100%; margin-top: 20px;"></div>

<?php require_once "./includes/footer.php"; ?>

<script>
var lat = <?= $wonder['latitude'] ?? 0 ?>;
var lng = <?= $wonder['longitude'] ?? 0 ?>;

if (lat && lng) {
    var map = L.map('kaart').setView([lat, lng], 5);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup("<?= htmlspecialchars($wonder['naam']) ?>")
        .openPopup();
} else {
    document.getElementById('kaart').innerHTML = "<p>Geen locatie beschikbaar voor dit wereldwonder.</p>";
}
</script>

<style>
/* Container voor detailpagina */
#wonder_box {
    max-width: 1000px;
    margin: 40px auto;
    padding: 25px;
    background: #F5F2EF;
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    color: #333;
    line-height: 1.6;
}

/* Titel */
#wonder_box h1 {
    font-size: 2rem;
    color: #8C4A3A;
    margin-bottom: 20px;
    text-align: center;
}

/* Algemene info */
#wonder_box p {
    margin: 8px 0;
    font-size: 1rem;
    color: #444;
}

/* Labels vetgedrukt */
#wonder_box p strong {
    color: #222;
}

/* Foto's sectie */
.wonder_fotos {
    margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px;
}

.wonder_fotos h2 {
    grid-column: 1/-1;
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #8C4A3A;
}

.wonder_fotos img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 10px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.wonder_fotos img:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}

/* Documenten sectie */
.wonder_docs {
    margin-top: 30px;
    padding: 15px;
    background: #DCC5B2;
    border-radius: 8px;
}

.wonder_docs h2 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #fff;
}

.wonder_docs ul {
    list-style: none;
    padding: 0;
}

.wonder_docs li {
    margin: 8px 0;
}

.wonder_docs a {
    color: #fff;
    text-decoration: none;
    background: #8C4A3A;
    padding: 8px 12px;
    border-radius: 6px;
    transition: background 0.3s ease;
}

.wonder_docs a:hover {
    background: #6b3629;
}

/* Responsive */
@media (max-width: 768px) {
    #wonder_box {
        padding: 15px;
    }
    .wonder_fotos {
        grid-template-columns: 1fr;
    }
    .wonder_fotos img {
        height: 180px;
    }
}
</style>
</body>
</html>
