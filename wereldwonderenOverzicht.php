 
<!-- // wereldwonderenOverzicht.php -->
 
 
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
<?php
require_once "./includes/header.php";
require_once "classWereldwonder.php";
 
// Filters ophalen van GET
$zoekterm   = $_GET['zoekterm'] ?? '';
$werelddeel = $_GET['werelddeel'] ?? '';
$bestaatNog = $_GET['bestaat_nog'] ?? '';
$type       = $_GET['type'] ?? '';
$sorteren   = $_GET['sorteer'] ?? 'naam';
 
// Wereldwonder object
$wereldwonderObj = new Wereldwonder();
?>
 
<h1>Overzicht van Wereldwonderen</h1>
 
<!-- Zoek- en filterformulier -->
<form method="get" action="wereldwonderenOverzicht.php" id="filterForm">
    <input type="text" name="zoekterm" placeholder="Zoekterm..." value="<?= htmlspecialchars($zoekterm) ?>" />
 
    <select name="werelddeel">
        <option value="">Alle werelddelen</option>
        <option value="Afrika" <?= $werelddeel == 'Afrika' ? 'selected' : '' ?>>Afrika</option>
        <option value="Azië" <?= $werelddeel == 'Azië' ? 'selected' : '' ?>>Azië</option>
        <option value="Noord-Amerika" <?= $werelddeel == 'Noord-Amerika' ? 'selected' : '' ?>>Noord-Amerika</option>
        <option value="Zuid-Amerika" <?= $werelddeel == 'Zuid-Amerika' ? 'selected' : '' ?>>Zuid-Amerika</option>
        <option value="Europa" <?= $werelddeel == 'Europa' ? 'selected' : '' ?>>Europa</option>
        <option value="Oceanië" <?= $werelddeel == 'Oceanië' ? 'selected' : '' ?>>Oceanië</option>
    </select>
 
    <select name="bestaat_nog">
        <option value="">Bestaat nog?</option>
        <option value="1" <?= $bestaatNog === '1' ? 'selected' : '' ?>>Ja</option>
        <option value="0" <?= $bestaatNog === '0' ? 'selected' : '' ?>>Nee</option>
    </select>
 
    <select name="type">
        <option value="">Type</option>
        <option value="klassiek" <?= $type == 'klassiek' ? 'selected' : '' ?>>Klassiek</option>
        <option value="modern" <?= $type == 'modern' ? 'selected' : '' ?>>Modern</option>
        <option value="natuurlijk" <?= $type == 'natuurlijk' ? 'selected' : '' ?>>Natuurlijk</option>
    </select>
    <button type="submit">Filter</button>
 
    <select name="sorteer">
        <option value="naam" <?= $sorteren == 'naam' ? 'selected' : '' ?>>Naam</option>
        <option value="bouwjaar" <?= $sorteren == 'bouwjaar' ? 'selected' : '' ?>>Bouwjaar</option>
        <!-- <option value="werelddeel" <?= $sorteren == 'werelddeel' ? 'selected' : '' ?>>Werelddeel</option> -->
    </select>  
   
   
 
 
<select name="sorteer" id="sorteerSelect">
    <option value="naam_asc" <?= $sorteren=='naam_asc'?'selected':'' ?>>Naam A→Z</option>
    <option value="naam_desc" <?= $sorteren=='naam_desc'?'selected':'' ?>>Naam Z→A</option>
    <option value="bouwjaar_asc" <?= $sorteren=='bouwjaar_asc'?'selected':'' ?>>Bouwjaar ↑</option>
    <option value="bouwjaar_desc" <?= $sorteren=='bouwjaar_desc'?'selected':'' ?>>Bouwjaar ↓</option>
</select>
 
 
 
 
    <a href="wereldwonderenOverzicht.php">Reset</a>
</form>
 
<hr>
<script>
    document.getElementById('sorteerSelect').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
</script>
 
</html>
 
<!-- Wereldwonderen overzicht -->
<?php
$wereldwonderObj->haalAlleWonderenOverzicht($zoekterm, $werelddeel, $bestaatNog, $type, $sorteren);
?>
 
<?php
require_once "./includes/footer.php";
?>
 
<style>
    #wonderen_container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px 0;
}
 
.wonder_card {
    background-color: #DCC5B2;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
 
.wonder_card:hover {
    transform: scale(1.05);
    box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
}
 
.wonder_card img.poster {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
}
 
.wonder_card h2 {
    margin: 10px 0;
    color: #D9A299;
}
 
.wonder_card p {
    color: white;
    font-size: 0.9rem;
}
 
/* responsive zoals filmoverzicht */
@media screen and (max-width: 1023px) {
    #wonderen_container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}
/* Wereldwonder overzicht container */
#wonderen_container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    padding: 20px;
}
 
/* Elke kaart */
.wonder_card {
    background-color: #DCC5B2;
    padding: 15px;
    border-radius: 10px;
    text-align: center;
    transition: transform 0.3s, box-shadow 0.3s;
}
 
.wonder_card:hover {
    transform: scale(1.05);
    box-shadow: 0px 5px 15px rgba(0,0,0,0.3);
}
 
.wonder_card img.poster {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: 8px;
    margin-bottom: 10px;
}
 
.wonder_card h2 {
    margin: 10px 0;
    color: #D9A299;
    font-size: 1.2rem;
}
 
.wonder_card p {
    color: white;
    font-size: 0.9rem;
}
 
/* responsive */
@media screen and (max-width: 1023px) {
    #wonderen_container {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
}
 
@media screen and (max-width: 767px) {
    #wonderen_container {
        grid-template-columns: 1fr;
        padding: 10px;
    }
    .wonder_card img.poster {
        height: 180px;
    }
}
 
 
</style>