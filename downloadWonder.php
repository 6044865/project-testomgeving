<?php
require_once "./includes/auth.php";
require_once "classWereldwonder.php";
require_once "FotoClass.php";
require_once "DocumentClass.php";

// Alleen onderzoekers en beheerders mogen downloaden
if (!in_array($rol, ['onderzoeker', 'beheerder'])) {
    die("❌ Toegang geweigerd.");
}

// Wonder ID ophalen
$wonderId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($wonderId <= 0) {
    die("❌ Ongeldig wonder ID.");
}

$ww = new Wereldwonder();
$foto = new Foto();
$document = new Document();

$selectedWonder = $ww->getWonderMetDetails($wonderId);
if (!$selectedWonder) {
    die("❌ Wereldwonder niet gevonden.");
}

$fotos = $foto->getFotosPerWonder($wonderId);
$documenten = $document->getDocumentenPerWonder($wonderId);

// Bestanden verzamelen
$files = [];
foreach ($fotos as $f) {
    $files[] = $f['bestandspad'];
}
foreach ($documenten as $d) {
    $files[] = $d['bestandspad'];
}

// Controleer of ZipArchive beschikbaar is
if (class_exists('ZipArchive')) {
    $zip = new ZipArchive();
    $zipFileName = "wonder_{$wonderId}.zip";

    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
        foreach ($files as $file) {
            if (file_exists($file)) {
                $zip->addFile($file, basename($file));
            }
        }
        $zip->close();

        // Headers voor download
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($zipFileName) . '"');
        header('Content-Length: ' . filesize($zipFileName));
        readfile($zipFileName);

        // Optioneel: verwijder het zipbestand na download
        unlink($zipFileName);
        exit;
    } else {
        die("❌ Kan ZIP-bestand niet aanmaken.");
    }
} else {
    // Fallback: losse downloadlinks
    echo "<h2>Download bestanden voor: " . htmlspecialchars($selectedWonder['naam']) . "</h2>";
    echo "<p>ZipArchive niet beschikbaar, download bestanden individueel:</p>";
    echo "<ul>";
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo '<li><a href="' . htmlspecialchars($file) . '" download>' . basename($file) . '</a></li>';
        }
    }
    echo "</ul>";
}

