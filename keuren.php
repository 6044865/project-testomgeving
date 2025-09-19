<?php
require_once "./includes/auth.php";
require_once "./classWereldwonder.php";
require_once "./FotoClass.php";
require_once "./DocumentClass.php";

if ($rol !== "redacteur") die("❌ Geen toegang.");

$type = $_POST['type'] ?? '';
$id   = (int)($_POST['id'] ?? 0);

if (!$type || !$id) die("❌ Ongeldige gegevens.");

$status = isset($_POST['approve']) ? 1 : -1; // 1 = goedgekeurd, -1 = afgekeurd

switch ($type) {
    case "wonder":
        $ww = new Wereldwonder();
        $ww->updateStatus($id, $status);
        break;
    case "foto":
        $foto = new Foto();
        $foto->updateStatus($id, $status);
        break;
    case "document":
        $doc = new Document();
        $doc->updateStatus($id, $status);
        break;
}

header("Location: redacteurGoedkeuren.php");
exit;
