<?php
require_once "./includes/auth.php";
require_once "./classWereldwonder.php";

// Alleen beheerders
if ($rol !== 'beheerder') {
    die("❌ Toegang geweigerd. Alleen voor beheerders.");
}

$ww = new Wereldwonder();

// Wonder ID ophalen
$wonderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Direct verwijderen als parameter direct=true
if ($wonderId > 0) {
    $deleted = $ww->wonderVerwijderen($wonderId);

    if ($deleted) {
        // Terug naar beheerpagina
        header("Location: wonderBeheer.php?message=verwijderd");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Fout bij verwijderen van wereldwonder.</p>";
    }
} else {
    echo "<p style='color:red;'>❌ Geen geldig wereldwonder geselecteerd.</p>";
}
?>
