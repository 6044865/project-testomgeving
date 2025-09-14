<?php
session_start();
require_once "classDatabase.php";

$db = new Database();
$pdo = $db->getConnection(); // PDO object

// Login functie
function login($username, $wachtwoord) {
    global $pdo;

    // Zoek gebruiker
    $stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruikersnaam = :username LIMIT 1");
    $stmt->execute([':username' => $username]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($gebruiker && password_verify($wachtwoord, $gebruiker['wachtwoord'])) {
        // Sla sessiegegevens op
        $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
        $_SESSION['username'] = $gebruiker['gebruikersnaam'];
        $_SESSION['user_role'] = $gebruiker['rol'];
        $_SESSION['isIngelogd'] = true;

        // Haal rechten op uit rollen_rechten
        $rechtenStmt = $pdo->prepare("SELECT * FROM rollen_rechten WHERE rol_naam = :rol LIMIT 1");
        $rechtenStmt->execute([':rol' => $gebruiker['rol']]);
        $rechten = $rechtenStmt->fetch(PDO::FETCH_ASSOC);

        if ($rechten) {
            $_SESSION['rechten'] = $rechten;
        } else {
            $_SESSION['rechten'] = []; // fallback
        }

        return true;
    }

    return false;
}

// Controleer of gebruiker een actie mag uitvoeren
function mag($actie) {
    if (!isset($_SESSION['rechten'])) return false;
    return !empty($_SESSION['rechten'][$actie]) && $_SESSION['rechten'][$actie] == 1;
}

// Controleer of gebruiker ingelogd is
function ingelogd() {
    return isset($_SESSION['gebruiker_id']);
}

// Logout functie
function logout() {
    session_destroy();
}
?>
