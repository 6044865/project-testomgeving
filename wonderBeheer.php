<?php
require_once "./includes/auth.php";  
require_once "./classWereldwonder.php";

// Alleen beheerders mogen beheren
if ($rol !== 'beheerder') {
    die("❌ Toegang geweigerd. Alleen voor beheerders.");
}

$ww = new Wereldwonder();
$wonderen = $ww->getAlleWonderen();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Wereldwonderen Beheer</title>
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
<script>
function confirmVerwijder(wonderId) {
    if (confirm("Weet je zeker dat je dit wereldwonder wilt verwijderen?")) {
        window.location.href = "wereldwonderenVerwijderen.php?id=" + wonderId + "&direct=true";
    }
}
</script>
</head>
<body>

<?php include "./includes/header.php"; ?>

<main>
    <h2>Wereldwonderen Beheer</h2>

    <a href="toevoegenWereldwonder.php" class="btn btn-toevoegen">➕ Wereldwonder Toevoegen</a>

    <?php if ($wonderen): ?>
        <?php foreach ($wonderen as $w): ?>
            <div class="card">
                <h3><?= htmlspecialchars($w['naam']); ?></h3>
                <p><strong>ID:</strong> <?= htmlspecialchars($w['wonder_id']); ?></p>
             
                <div class="actions">
                    <a href="bewerkWonder.php?id=<?= $w['wonder_id']; ?>" class="bewerken">✏️ Bewerken</a>
                    <a href="javascript:void(0);" class="verwijderen" onclick="confirmVerwijder(<?= $w['wonder_id']; ?>)">🗑️ Verwijderen</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Geen wereldwonderen gevonden.</p>
    <?php endif; ?>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
