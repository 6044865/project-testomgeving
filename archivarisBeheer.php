<?php
require_once "./includes/auth.php";  
require_once "./classWereldwonder.php";

// Alleen archivarissen mogen deze pagina gebruiken
if ($rol !== 'archivaris') {
    die("❌ Toegang geweigerd. Alleen voor archivisten.");
}

$ww = new Wereldwonder();
$wonderen = $ww->getAlleWonderen();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Archivaris Beheer</title>
<link rel="stylesheet" href="./css/stylesheet.css">
<style>
    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .card {
        background: #fff;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .card h3 {
        margin-top: 0;
    }
    .actions {
        margin-top: 1rem;
    }
    .actions .bewerken {
        display: inline-block;
        padding: 0.5rem 1rem;
        background:  #8d735dff; /* groen */
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: background 0.3s;
    }
    .actions .bewerken:hover {
        background: #8d735dff; /* donkerder groen bij hover */
    }
</style>
</head>
<body>

<?php include "./includes/header.php"; ?>

<main>
    <h2>Wereldwonderen – Archivaris Beheer</h2>
    <p>Kies een wonder om te bewerken:</p>

    <div class="card-container">
        <?php if ($wonderen): ?>
            <?php foreach ($wonderen as $w): ?>
                <div class="card">
                    <h3><?= htmlspecialchars($w['naam']); ?></h3>
              
                    <div class="actions">
                        <a href="bewerkWonder.php?id=<?= $w['wonder_id']; ?>" class="bewerken">✏️ Bewerken</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Geen wereldwonderen gevonden.</p>
        <?php endif; ?>
    </div>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
