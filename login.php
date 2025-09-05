<?php
session_start();
require_once "classDatabase.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["username"]) && !empty($_POST["wachtwoord"])) {
        $username = trim($_POST["username"]);
        $password = $_POST["wachtwoord"];

        try {
            $db = new Database();

            // Zoek gebruiker in de database
            $query = "SELECT * FROM gebruikers WHERE gebruikersnaam = :username LIMIT 1";
            // $stmt = $db->pdo->prepare($query); dat mag niet pdo te gebuiken van het buiten de class omdat het is protected
            $stmt = $db->getConnection()->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $gebruiker = $stmt->fetch();

            // Bestaat gebruiker en wachtwoord correct?
            if ($gebruiker && password_verify($password, $gebruiker['wachtwoord'])) {
                // ✅ Login gelukt → sessies zetten
                $_SESSION['gebruiker_id'] = $gebruiker['gebruiker_id'];
                $_SESSION['username']     = $gebruiker['gebruikersnaam'];
                $_SESSION['user_role']    = $gebruiker['rol'];
                $_SESSION['isIngelogd']   = true;

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "❌ Wachtwoord of gebruikersnaam is onjuist.";
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
    <title>Codex Mundi | Inloggen</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body class="inlog_pagina">
    <main>
        <section class="form_box">
            <h1>Log in</h1>

            <form id="inlogform" action="login.php" method="post">
                <label for="username"><span id="asterisk">*</span>Gebruikersnaam:</label>
                <input type="text" name="username" id="username" required>

                <label for="wachtwoord"><span id="asterisk">*</span>Wachtwoord:</label>
                <input type="password" name="wachtwoord" id="wachtwoord" required>

                <input type="submit" value="Log in" name="submit" id="submit">
            </form>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?php echo $error; ?></p>
            <?php endif; ?>
<!-- 
            <article>
              
                <a href="#">Wachtwoord vergeten?</a><br><br>
            </article> -->
        </section>
    </main>
</body>
</html>
