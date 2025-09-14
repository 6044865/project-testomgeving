<?php

// require_once "classDatabase.php";
// require_once "classWereldwonder.php";

// session_start();

// // check login
// if(!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true){
//     header("location: login.php");
//     exit();
// }

// $rol = $_SESSION['rol'];
// $wonder_id = $_POST['wonder_id'] ?? null;

// if(!$wonder_id){
//     echo "Geen wereldwonder geselecteerd.";
//     exit();
// }

// // rechten per rol
// $rechten = [
//     "onderzoeker" => ["naam", "beschrijving"],
//     "archivist"   => ["bouwjaar", "bestaat_nog", "status", "tags"],
//     "beheerder"   => ["naam", "beschrijving", "bouwjaar", "bestaat_nog", "status", "tags", "wereldeel", "locatie", "latitude", "longitude"]
// ];

// $toegestaan = $rechten[$rol] ?? [];

// $velden = [];
// $waardes = [];

// // loop door POST-waarden
// foreach($_POST as $veld => $waarde){
//     if($veld === "wonder_id") continue; // id niet aanpassen
//     if($rol === "beheerder" || in_array($veld, $toegestaan)){
//         $velden[] = "$veld = ?";
//         $waardes[] = $waarde;
//     }
// }

// // niets toegestaan?
// if(empty($velden)){
//     echo "U heeft geen rechten om dit wereldwonder aan te passen.";
//     exit();
// }

// // query bouwen
// $sql = "UPDATE wereldwonderen SET ".implode(", ", $velden)." WHERE id = ?";
// $waardes[] = $wonder_id;

// // db uitvoeren
// try {
//     $db = new Database();
//     $conn = $db->getConnection();

//     $stmt = $conn->prepare($sql);
//     $stmt->execute($waardes);

//     header("location: wereldwonderOverzicht.php?success=1");
//     exit();
// } catch (PDOException $e) {
//     echo "Fout bij opslaan: " . $e->getMessage();
// }













// ?>

// <!DOCTYPE html>
// <html lang="nl">
// <head>
//     <meta charset="UTF-8">
//     <title>Nieuw Wereldwonder Toevoegen</title>
//     <style>
//         form { max-width: 600px; margin: 20px auto; display: flex; flex-direction: column; gap: 10px; }
//         input, textarea, select, button { padding: 8px; font-size: 16px; }
//         button { cursor: pointer; }
//     </style>
// </head>
// <body>
// <h1>Nieuw Wereldwonder Toevoegen</h1>

// <?php if($message) echo "<p>$message</p>"; ?>

// <form method="post">
//     <label>Naam: <input type="text" name="naam" required></label>
//     <label>Beschrijving: <textarea name="beschrijving" required></textarea></label>
//     <label>Bouwjaar: <input type="number" name="bouwjaar"></label>
//     <label>Werelddeel: <input type="text" name="werelddeel"></label>
//     <label>Type:
//         <select name="type" required>
//             <option value="">-- Kies type --</option>
//             <option value="klassiek">Klassiek</option>
//             <option value="modern">Modern</option>
//         </select>
//     </label>
//     <label>Bestaat nog:
//         <select name="bestaat_nog">
//             <option value="">-- select --</option>
//             <option value="1">Ja</option>
//             <option value="0">Nee</option>
//         </select>
//     </label>
//     <label>Locatie: <input type="text" name="locatie"></label>
//     <label>Latitude: <input type="text" name="latitude"></label>
//     <label>Longitude: <input type="text" name="longitude"></label>
//     <label>Status: <input type="text" name="status"></label>
//     <label>Tags: <input type="text" name="tags"></label>

//     <button type="submit">Toevoegen</button>
// </form>
// </body>
// </html>
