<?php
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/GebuikerClass.php";

// ✅ Alleen beheerder toegang
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $gebruikerClass = new Gebruiker();

    if ($gebruikerClass->verwijderen($id)) {
        header("Location: gebuikersBeheren.php?msg=deleted");
        exit;
    } else {
        echo "<p style='color:red;'>❌ Verwijderen is mislukt.</p>";
    }
} else {
    header("Location: gebuikersBeheren.php");
    exit;
}
