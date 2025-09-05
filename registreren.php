<?php
session_start();
require_once "classDatabase.php";

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["wachtwoord"])) {
        $username = trim($_POST["username"]);
        $email    = trim($_POST["email"]);
        $password = $_POST["wachtwoord"];

        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Check of gebruikersnaam al bestaat
            $checkQuery = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username LIMIT 1";
            $checkStmt  = $conn->prepare($checkQuery);
            $checkStmt->bindParam(":username", $username);
            $checkStmt->execute();

            if ($checkStmt->fetch()) {
                $error = "❌ Gebruikersnaam bestaat al, kies een andere.";
            } else {
                // Hash wachtwoord
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

                // Nieuwe gebruiker invoegen, standaard rol = bezoeker
                $insertQuery = "INSERT INTO gebruikers (gebruikersnaam, email, wachtwoord, rol, aangemaakt_op) 
                                VALUES (:username, :email, :password, :rol, NOW())";
                $insertStmt = $conn->prepare($insertQuery);
                $rol = "bezoeker"; 

                $insertStmt->bindParam(":username", $username);
                $insertStmt->bindParam(":email", $email);
                $insertStmt->bindParam(":password", $hashedPassword);
                $insertStmt->bindParam(":rol", $rol);

                if ($insertStmt->execute()) {
                    $success = "✅ Registratie gelukt! Je kunt nu inloggen.";
                } else {
                    $error = "❌ Er is iets fout gegaan bij het registreren.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database fout: " . $e->getMessage();
        }
    } else {
        $error = "Je moet alle velden invullen!";
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codex Mundi | Registreren</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body class="account_maken_pagina">
    <main>
        <section class="form_box">
            <h1>Registreer</h1>

            <form id="account" action="registreren.php" method="post">
                <label for="username"><span id="asterisk">*</span>Gebruikersnaam:</label>
                <input type="text" name="username" id="username" required>

                <label for="email"><span id="asterisk">*</span>Email adres:</label>
                <input type="email" name="email" id="email" required placeholder="voorbeeld@gmail.com">

                <label for="wachtwoord"><span id="asterisk">*</span>Wachtwoord:</label>
                <input type="password" name="wachtwoord" id="wachtwoord" required>

                <input type="submit" value="Registreren" name="submit" id="submit">
            </form>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <p style="color:green;"><?php echo $success; ?></p>
            <?php endif; ?>

            <article>
                <a href="login.php">Heb je al een account? <h1 style="color:aqua "  >Log in</h1></a><br><br>
            </article>
        </section>
    </main>
</body>
</html>
