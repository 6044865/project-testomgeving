<?php
require_once __DIR__ . "/includes/auth.php";  
require_once __DIR__ . "/GebuikerClass.php";

// Alleen beheerders mogen deze pagina zien
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

$gebruikerClass = new Gebruiker();
$gebruikers = $gebruikerClass->getAlleGebruikers();
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <style>
        main { width: 90%; margin: 30px auto; }
        h2 { color: #4B2E2E; margin-bottom: 20px; }
        .btn { display:inline-block; padding:8px 12px; text-decoration:none; border-radius:5px; color:#fff; margin-bottom:15px; }
        .btn-toevoegen { background:#8B5E3C; }
        .card { background:#F5F0E6; border:1px solid #D2B48C; border-radius:8px; padding:15px; margin-bottom:15px; box-shadow:2px 2px 6px rgba(0,0,0,0.1); }
        .card h3 { margin:0 0 10px 0; color:#5A3E36; }
        .card p { margin:3px 0; }
        .actions { margin-top:10px; }
        .actions a { display:inline-block; margin-right:10px; padding:5px 10px; border-radius:4px; text-decoration:none; color:#fff; }
        .actions .bewerken { background:#4CAF50; }
        .actions .verwijderen { background:#f44336; }
    </style>
</head>

<body>
<?php include "./includes/header.php"; ?>

<main>
    <h2>Gebruikersbeheer</h2>

    <a href="registreren.php" class="btn btn-toevoegen">➕ Nieuwe gebruiker</a>

    <?php if ($gebruikers): ?>
        <?php foreach ($gebruikers as $g): ?>
            <div class="card">
                <h3><?= htmlspecialchars($g['gebruikersnaam']); ?> </h3>
                 <p><strong>Id:</strong> <?= htmlspecialchars($g['gebruiker_id']); ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($g['email']); ?></p>
                <p><strong>Rol:</strong> <?= htmlspecialchars($g['rol']); ?></p>
             
                <div class="actions">
                    <a href="gebruiker_bewerken.php?id=<?= $g['gebruiker_id']; ?>" class="bewerken">✏️ Bewerken</a>
                    <a href="gebruiker_verwijderen.php?id=<?= $g['gebruiker_id']; ?>" class="verwijderen" 
                       onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">🗑️ Verwijderen</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Geen gebruikers gevonden.</p>
    <?php endif; ?>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
