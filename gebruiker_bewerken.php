 
<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wereldwonderen</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="../project-testomgeving/js/index.js" defer></script>
    <!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

 
    <meta name="description"
      content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
<meta name="keywords"
      content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">
 
 
 
    <meta name="author" content="A.Alhaji, G.Verpaalen">
 
</head>
<?php

require_once __DIR__ . "/includes/auth.php";
include "./includes/header.php";

require_once __DIR__ . "/classDatabase.php";

// ✅ Alleen beheerder toegang
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: gebruikers.php");
    exit;
}

// Stap 1: Huidige gebruiker ophalen
$stmt = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruiker_id = :id");
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$gebruiker = $stmt->fetch();

if (!$gebruiker) {
    echo "Gebruiker niet gevonden.";
    exit;
}

$error = "";
$success = "";

// Stap 2: Bewerking opslaan
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gebruikersnaam = trim($_POST['gebruikersnaam']);
    $email          = trim($_POST['email']);
    $rol            = $_POST['rol'];

    // Wachtwoord alleen updaten als er een nieuw is ingevoerd
    $sql = "UPDATE gebruikers SET gebruikersnaam = :gebruikersnaam, email = :email, rol = :rol";
    if (!empty($_POST['wachtwoord'])) {
        $sql .= ", wachtwoord = :wachtwoord";
    }
    $sql .= " WHERE gebruiker_id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":gebruikersnaam", $gebruikersnaam);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":rol", $rol);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);

    if (!empty($_POST['wachtwoord'])) {
        $hashedPassword = password_hash($_POST['wachtwoord'], PASSWORD_BCRYPT);
        $stmt->bindParam(":wachtwoord", $hashedPassword);
    }

    if ($stmt->execute()) {
        // $success = "✅ Gebruiker succesvol bijgewerkt.";
         header("Location: gebuikersBeheren.php");
               
        
        // opnieuw ophalen voor weergave
        $stmt2 = $pdo->prepare("SELECT * FROM gebruikers WHERE gebruiker_id = :id");
        $stmt2->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt2->execute();
        $gebruiker = $stmt2->fetch();
    } else {
        $error = "❌ Updaten mislukt.";
    }
}
?>



<main style="width:60%;margin:30px auto;">
    <h2>Gebruiker bewerken</h2>

    <?php if (!empty($error)): ?><p style="color:red;"><?= $error; ?></p><?php endif; ?>
    <?php if (!empty($success)): ?><p style="color:green;"><?= $success; ?></p><?php endif; ?>

    <form method="post">
        <label >Gebruikersnaam:</label><br>
        <input type="text" name="gebruikersnaam" value="<?= htmlspecialchars($gebruiker['gebruikersnaam']); ?>" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($gebruiker['email']); ?>" required><br><br>

        <label>Rol:</label><br>
        <select name="rol" required>
            <option value="beheerder"  <?= $gebruiker['rol'] === 'beheerder' ? 'selected' : ''; ?>>Beheerder</option>
            <option value="onderzoeker" <?= $gebruiker['rol'] === 'onderzoeker' ? 'selected' : ''; ?>>Onderzoeker</option>
            <option value="bezoeker"   <?= $gebruiker['rol'] === 'bezoeker' ? 'selected' : ''; ?>>Bezoeker</option>
            <option value="archivaris" <?= $gebruiker['rol'] === 'archivaris' ? 'selected' : ''; ?>>Archivaris</option>
            <option value="redacteur"  <?= $gebruiker['rol'] === 'redacteur' ? 'selected' : ''; ?>>Redacteur</option>
        </select><br><br>

        <label >Nieuw wachtwoord (optioneel):</label><br>
        <input type="password" name="wachtwoord"><br><br>

        <input type="submit" value="Opslaan" 
               style="padding:8px 14px;background:#007BFF;color:#fff;border:none;border-radius:5px;cursor:pointer;">
    </form>
</main>

<?php include "./includes/footer.php"; ?>
<style>
  
</style>