<?php
require_once __DIR__ . "/includes/auth.php";
require_once __DIR__ . "/classDatabase.php";

// ✅ Alleen beheerder toegang
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("DELETE FROM gebruikers WHERE gebruiker_id = :id");
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: gebuikersBeheren.php?msg=deleted");
        exit;
    } else {
        echo "❌ Verwijderen is mislukt.";
    }
} else {
    header("Location: gebuikersBeheren.php");
    exit;
}
