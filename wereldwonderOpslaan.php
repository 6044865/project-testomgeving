<?php
require_once "classDatabase.php";
require_once "classWereldwonder.php";
session_start();

if(!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true){
    header("location: login.php");
    exit();
}

$rol = strtolower($_SESSION['rol'] ?? '');
$wonder_id = $_POST['wonder_id'] ?? null;

if(!$wonder_id){
    die("Geen wereldwonder geselecteerd.");
}

// rechten per rol
$rechten = [
    "onderzoeker" => ["naam", "beschrijving"],
    "archivaris"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
    "beheerder"   => ["naam","beschrijving","bouwjaar","bestaat_nog","status","tags","wereldeel","locatie","latitude","longitude"]
];

if(!array_key_exists($rol, $rechten)){
    die("Onbekende rol: ".htmlspecialchars($rol));
}
$toegestaan = $rechten[$rol];

// query voorbereiden
$velden = [];
$waardes = [];

foreach($_POST as $veld => $waarde){
    if($veld === "wonder_id") continue;
    if($rol === "beheerder" || in_array($veld, $toegestaan)){
        $velden[] = "$veld = ?";
        $waardes[] = $waarde;
    }
}

if(empty($velden)){
    die("U heeft geen rechten om dit wereldwonder aan te passen.");
}

$sql = "UPDATE wereldwonderen SET ".implode(", ", $velden)." WHERE wonder_id = ?";
$waardes[] = $wonder_id;

try {
    $db = new Database();
    $conn = $db->getConnection();
    $stmt = $conn->prepare($sql);
    $stmt->execute($waardes);

    header("location: wereldwonderenOverzicht.php?success=1");
    exit();
} catch (PDOException $e) {
    die("Fout bij opslaan: " . $e->getMessage());
}
