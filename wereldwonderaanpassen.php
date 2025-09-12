<?php 

require_once "classDatabase.php";
require_once "classWereldwonder.php";

// rechten per rol
$rechten = [
    "onderzoeker" => ["naam", "beschrijving"],
    "archivist"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
    "beheerder"   => ["naam", "beschrijving", "bouwjaar", "bestaat_nog", "status", "tags", "wereldeel", "locatie", "latitude", "longitude"]
];

$toegestaan = $rechten[$rol] ?? [];

$velden = [];
$waardes = [];

// loop door alle velden uit het formulier
foreach($_POST as $veld => $waarde){
    if($veld === "wonder_id") continue; // id mag nooit aangepast worden
    if($rol === "beheerder" || in_array($veld, $toegestaan)){
        // deze gebruiker mag dit veld aanpassen → toevoegen aan query
        $velden[] = "$veld = ?";
        $waardes[] = $waarde;
    }
}


session_start();

// check login
if(!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true){
    header("location: login.php");
    exit();
}

$rol = $_SESSION['rol'];
$wonder_id = $_POST['wonder_id'] ?? null;

if(!$wonder_id){
    echo "Geen wereldwonder geselecteerd.";
    exit();
}

// rechten per rol
$rechten = [
    "onderzoeker" => ["naam", "beschrijving"],
    "archivist"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
    "beheerder"   => ["naam", "beschrijving", "bouwjaar", "bestaat_nog", "status", "tags", "wereldeel", "locatie", "latitude", "longitude"]
];

$toegestaan = $rechten[$rol] ?? [];

$velden = [];
$waardes = [];

// loop door POST-waarden
foreach($_POST as $veld => $waarde){
    if($veld === "wonder_id") continue; // id niet aanpassen
    if($rol === "beheerder" || in_array($veld, $toegestaan)){
        $velden[] = "$veld = ?";
        $waardes[] = $waarde;
    }
}

// niets toegestaan?
if(empty($velden)){
    echo "U heeft geen rechten om dit wereldwonder aan te passen.";
    exit();
}

// query bouwen
$sql = "UPDATE wereldwonderen SET ".implode(", ", $velden)." WHERE id = ?";
$waardes[] = $wonder_id;

// db uitvoeren
try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->prepare($sql);
    $stmt->execute($waardes);

    header("location: wereldwonderOverzicht.php?success=1");
    exit();
} catch (PDOException $e) {
    echo "Fout bij opslaan: " . $e->getMessage();
}









?>

















<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mbo_cinemas/dashboard medewerker/ film aanpassen</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <meta name="description" content="mbocinemas is een bioscoop die veel films biedt, je kan een zaal huren of gewoon van een film kan genieten">
    <meta name="keywords" content="mbocinemas, film, movies, bioscoop, bioscoopzaal huren, ">
    <meta name="author" content="A.Alhaji, G.Verpaalen">
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>

</head>
 <?php
    require_once "./includes/header.php";
    require_once "classWereldwonder.php"; ?>
    <body class="filmAanpassen">
       
        <main>
      <section class="form_box">
            <h1><?php echo htmlspecialchars($wonder['naam']); ?> aanpassen</h1>
            <form action="wereldwonderOpslaan.php" method="post">
                <input type="hidden" name="wonder_id" value="<?php echo $wonder['id']; ?>">

                <label>Naam:</label>
                <?php echo veldInput($rol, "naam", $wonder['naam'], $toegestaan); ?>

                <label>Beschrijving:</label>
                <?php echo textAreaInput($rol, "beschrijving", $wonder['beschrijving'], $toegestaan); ?>

                <label>Bouwjaar:</label>
                <?php echo veldInput($rol, "bouwjaar", $wonder['bouwjaar'], $toegestaan); ?>

                <label>Werelddeel:</label>
                <p><?php echo htmlspecialchars($wonder['wereldeel']); ?></p>

                <label>Bestaat nog:</label>
                <?php echo veldInput($rol, "bestaat_nog", $wonder['bestaat_nog'], $toegestaan); ?>

                <label>Status:</label>
                <?php echo veldInput($rol, "status", $wonder['status'], $toegestaan); ?>

                <label>Tags:</label>
                <?php echo veldInput($rol, "tags", $wonder['tags'], $toegestaan); ?>

                <label>Locatie:</label>
                <p><?php echo htmlspecialchars($wonder['locatie']); ?></p>

                <label>Latitude:</label>
                <p><?php echo htmlspecialchars($wonder['latitude']); ?></p>

                <label>Longitude:</label>
                <p><?php echo htmlspecialchars($wonder['longitude']); ?></p>

                <label>Toegevoegd door:</label>
                <p><?php echo htmlspecialchars($wonder['toegevoegd_door']); ?></p>

                <label>Aangemaakt op:</label>
                <p><?php echo htmlspecialchars($wonder['aangemaakt_op']); ?></p>

                <?php if($rol === "beheerder" || !empty($toegestaan)): ?>
                    <input type="submit" value="Opslaan">
                <?php endif; ?>
            </form>
        </section>

        </main>
    
    </body>
</html>