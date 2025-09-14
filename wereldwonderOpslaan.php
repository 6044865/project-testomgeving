<?php
require_once "classDatabase.php";
require_once "classWereldwonder.php";

session_start();

// check login
if(!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true){
    header("location: login.php");
    exit();
}

// rol veilig ophalen
$rol = strtolower($_SESSION['rol'] ?? '');

// rechten per rol
$rechten = [
    "onderzoeker" => ["naam", "beschrijving"],
    "archivist"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
    "beheerder"   => ["naam","beschrijving","bouwjaar","bestaat_nog","status","tags","wereldeel","locatie","latitude","longitude"]
];

// check of rol bestaat
if (!array_key_exists($rol, $rechten)) {
    die("Onbekende rol of geen rol ingesteld!");
}

$toegestaan = $rechten[$rol];

// wonder_id ophalen
$wonder_id = $_POST['wonder_id'] ?? null;
if(!$wonder_id){
    die("Geen wereldwonder geselecteerd.");
}

// velden voorbereiden voor update
$velden = [];
$waardes = [];

foreach($_POST as $veld => $waarde){
    if($veld === "wonder_id") continue; // id niet aanpassen
    if($rol === "beheerder" || in_array($veld, $toegestaan)){
        $velden[] = "$veld = ?";
        $waardes[] = $waarde;
    }
}

// niets toegestaan?
if(empty($velden)){
    die("U heeft geen rechten om dit wereldwonder aan te passen.");
}

// query bouwen
$sql = "UPDATE wereldwonderen SET ".implode(", ", $velden)." WHERE id = ?";
$waardes[] = $wonder_id;

// uitvoeren
try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($waardes);

    // terug naar overzicht
    header("location: wereldwonderOverzicht.php?success=1");
    exit();
} catch (PDOException $e) {
    die("Fout bij opslaan: " . $e->getMessage());
}
