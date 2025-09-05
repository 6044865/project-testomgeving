<?php
// includes/auth.php
session_start();

// ik wil hier controleren of de gebuiker is ingelogd anderes wordt hij doorgestuurd naar inlog pagina
if (!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true) {
    header("Location: ../login.php");
    exit;
}
?>