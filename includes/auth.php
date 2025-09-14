<?php
// includes/auth.php



// Start sessie als die nog niet gestart is
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Controleren of gebruiker is ingelogd
if (!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true) {
    // Niet ingelogd → terug naar loginpagina
    header("Location: ../login.php");
    exit;
}

// ✅ Gegevens van de ingelogde gebruiker
$gebruiker_id = $_SESSION['gebruiker_id'];
$naam         = $_SESSION['username'];
$rol          = $_SESSION['user_role'];

/**
 * Helperfunctie om ingelogde gebruiker als array op te halen
 */
function getIngelogdeGebruiker(): array {
    return [
        'id'   => $_SESSION['gebruiker_id'] ?? null,
        'naam' => $_SESSION['username']     ?? null,
        'rol'  => $_SESSION['user_role']    ?? null,
    ];
}



?>