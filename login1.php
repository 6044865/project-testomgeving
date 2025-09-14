<?php
require_once './includes/auth.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['wachtwoord'] ?? '';

    if (login($username, $password)) {
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "❌ Gebruikersnaam of wachtwoord onjuist";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Login Codex Mundi</title>
</head>
<body>
<h1>Inloggen</h1>
<form method="post">
    <label>Gebruikersnaam: <input type="text" name="username" required></label><br>
    <label>Wachtwoord: <input type="password" name="wachtwoord" required></label><br>
    <button type="submit">Inloggen</button>
</form>
<?php if($error) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
