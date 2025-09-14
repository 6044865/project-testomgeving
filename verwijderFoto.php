<?php
require_once "FotoClass.php";
$foto = new Foto();
if (isset($_GET['foto_id'], $_GET['wonder_id'])) {
    $foto->fotoVerwijderen((int)$_GET['foto_id']);
    header("Location: wereldwonderBewerken?wonder_id=" . (int)$_GET['wonder_id']);
    exit();
}
