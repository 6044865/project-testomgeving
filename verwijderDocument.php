<?php
require_once "DocumentClass.php";
$doc = new Document();
if (isset($_GET['doc_id'], $_GET['wonder_id'])) {
    $doc->documentVerwijderen((int)$_GET['doc_id']);
    header("Location: wereldwonderBewerken?wonder_id=" . (int)$_GET['wonder_id']);
    exit();
}
