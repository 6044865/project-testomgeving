<?php
require_once "./includes/auth.php";
require_once "./classWereldwonder.php";
require_once "./FotoClass.php";
require_once "./DocumentClass.php";

if ($rol !== "redacteur") {
    die("❌ Toegang geweigerd.");
}

$ww   = new Wereldwonder();
$foto = new Foto();
$doc  = new Document();

// Haal alle meldingen op
$openWonderen  = $ww->getOngekeurdeWonderen();   // methode in classWereldwonder
$openFotos     = $foto->getOngekeurdeFotos();    // methode in FotoClass
$openDocs      = $doc->getOngekeurdeDocs();      // methode in DocumentClass

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $wonderId = (int)$_POST['wonder_id'];
//     $actie = $_POST['actie'];

//     if ($actie === 'goedkeuren') {
//         $ww->updateStatus($wonderId, 1); // 1 = goedgekeurd
//         $message = "✅ Wereldwonder is goedgekeurd!";
//     } elseif ($actie === 'afkeuren') {
//         $ww->updateStatus($wonderId, 2); // 2 = afgekeurd
//         $message = "❌ Wereldwonder is afgekeurd!";
//     }
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['wonder_id'], $_POST['actie'])) {
    $wonderId = (int)$_POST['wonder_id'];
    $actie = $_POST['actie'];

    if ($actie === 'goedkeuren') {
        $ww->updateStatus($wonderId, 1); // 1 = goedgekeurd
        $message = "✅ Wereldwonder is goedgekeurd!";
    } elseif ($actie === 'afkeuren') {
        $ww->updateStatus($wonderId, 2); // 2 = afgekeurd
        $message = "❌ Wereldwonder is afgekeurd!";
    }

    // herlaad de lijst met open wonderen
    $openWonderen = $ww->getOngekeurdeWonderen();
}


?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Meldingen van wijzigingen</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>
<main>
    <h1> Goedkeuren van wijzigingen</h1><br><br>

    <!-- Wereldwonderen -->
    <h2>🏛️ Wereldwonderen</h2>
    <?php if ($openWonderen): ?>
        <div class="cards">
        <?php foreach ($openWonderen as $w): ?>
            <div class="card">
                <h3><?= htmlspecialchars($w['naam']) ?></h3>
                <p>Toegevoegd door gebruiker <?= $w['toegevoegd_door'] ?></p>

                <form method="post" style="display:inline;" onsubmit="return confirm('Weet je zeker dat je dit wonder wilt goedkeuren?');">
                    <input type="hidden" name="wonder_id" value="<?= $w['wonder_id'] ?>">
                    <input type="hidden" name="actie" value="goedkeuren">
                    <button type="submit">✅ Goedkeuren</button>
                </form>

                <form method="post" style="display:inline;" onsubmit="return confirm('Weet je zeker dat je dit wonder wilt afkeuren?');">
                    <input type="hidden" name="wonder_id" value="<?= $w['wonder_id'] ?>">
                    <input type="hidden" name="actie" value="afkeuren">
                    <button type="submit">❌ Afkeuren</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Geen nieuwe wereldwonderen.</p>
    <?php endif; ?>

    <!-- Foto's -->
    <h2>📸 Foto’s</h2>
    <?php if ($openFotos): ?>
        <div class="cards">
        <?php foreach ($openFotos as $f): ?>
            <div class="card">
                <img src="<?= htmlspecialchars($f['bestandspad']) ?>" style="max-width:200px;">
                <p>Toegevoegd door gebruiker <?= $f['toegevoegd_door'] ?></p>
                <form method="post" action="keuren.php" style="display:inline;">
                    <input type="hidden" name="type" value="foto">
                    <input type="hidden" name="id" value="<?= $f['foto_id'] ?>">
                    <button type="submit" name="approve">✅ Goedkeuren</button>
                    <button type="submit" name="reject">❌ Afkeuren</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Geen nieuwe foto’s.</p>
    <?php endif; ?>

    <!-- Documenten -->
    <h2>📄 Documenten</h2>
    <?php if ($openDocs): ?>
        <div class="cards">
        <?php foreach ($openDocs as $d): ?>
            <div class="card">
                <a href="<?= htmlspecialchars($d['bestandspad']) ?>" target="_blank">
                    <?= htmlspecialchars(basename($d['bestandspad'])) ?>
                </a>
                <p>Toegevoegd door gebruiker <?= $d['toegevoegd_door'] ?></p>
                <form method="post" action="keuren.php" style="display:inline;">
                    <input type="hidden" name="type" value="document">
                    <input type="hidden" name="id" value="<?= $d['document_id'] ?>">
                    <button type="submit" name="approve">✅ Goedkeuren</button>
                    <button type="submit" name="reject">❌ Afkeuren</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Geen nieuwe documenten.</p>
    <?php endif; ?>
</main>

<!-- CSS toevoegen in <head> of je stylesheet -->
<style>
.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}
.card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 15px;
    width: 250px;
    text-align: center;
}
.card img {
    border-radius: 5px;
    margin-bottom: 10px;
}
.card button {
    margin: 5px;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.card button[name="approve"] {
    background-color: #fff;
    color: black;
}
.card button[name="reject"] {
    background-color: #fff;
    color: black;
}
</style>
