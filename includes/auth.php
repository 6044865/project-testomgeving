<?php
// includes/auth.php

// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// controleren of gebruiker is ingelogd
if (!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true) {
    header("Location: ../login.php");
    exit;
}

?>