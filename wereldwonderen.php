<?php
require_once "classDatabase.php";
$db = new Database();
$conn = $db->getConnection();

// Zoek, filter en sorteer parameters ophalen
$zoekterm = $_GET['zoekterm'] ?? '';
$type     = $_GET['type'] ?? '';
$werelddeel = $_GET['werelddeel'] ?? '';
$sorteren = $_GET['sorteren'] ?? 'naam';

// Basis query
$query = "SELECT * FROM wereldwonderen WHERE 1=1";

// Zoekterm
if (!empty($zoekterm)) {
    $query .= " AND naam LIKE :zoekterm";
}

// Filter op type
if (!empty($type)) {
    $query .= " AND type = :type";
}

// Filter op werelddeel
if (!empty($werelddeel)) {
    $query .= " AND werelddeel = :werelddeel";
}

// Sorteren
$toegestaneSorts = ['naam','bouwjaar','aangemaakt_op'];
if (!in_array($sorteren, $toegestaneSorts)) {
    $sorteren = 'naam';
}
$query .= " ORDER BY $sorteren ASC";

// Statement voorbereiden
$stmt = $conn->prepare($query);

// Parameters binden
if (!empty($zoekterm)) $stmt->bindValue(':zoekterm', "%$zoekterm%");
if (!empty($type)) $stmt->bindValue(':type', $type);
if (!empty($werelddeel)) $stmt->bindValue(':werelddeel', $werelddeel);

$stmt->execute();
$wonderen = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wereldwonderen Overzicht</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
<?php include "./includes/header.php"; ?>

<main>
    <h1>Overzicht Wereldwonderen</h1>

    <!-- Zoek- en filterformulier -->
    <form method="get" action="wereldwonderen.php" class="filter_form">
        <input type="text" name="zoekterm" placeholder="Zoek op naam..." value="<?= htmlspecialchars($zoekterm) ?>">

        <select name="type">
            <option value="">-- Type --</option>
            <option value="klassiek" <?= $type==='klassiek'?'selected':'' ?>>Klassiek</option>
            <option value="nieuw" <?= $type==='nieuw'?'selected':'' ?>>Nieuw</option>
            <option value="natuurlijk" <?= $type==='natuurlijk'?'selected':'' ?>>Natuurlijk</option>
        </select>

        <select name="werelddeel">
            <option value="">-- Werelddeel --</option>
            <option value="Europa" <?= $werelddeel==='Europa'?'selected':'' ?>>Europa</option>
            <option value="Azië" <?= $werelddeel==='Azië'?'selected':'' ?>>Azië</option>
            <option value="Afrika" <?= $werelddeel==='Afrika'?'selected':'' ?>>Afrika</option>
            <option value="Zuid-Amerika" <?= $werelddeel==='Zuid-Amerika'?'selected':'' ?>>Zuid-Amerika</option>
            <option value="Noord-Amerika" <?= $werelddeel==='Noord-Amerika'?'selected':'' ?>>Noord-Amerika</option>
        </select>

        <select name="sorteren">
            <option value="naam" <?= $sorteren==='naam'?'selected':'' ?>>Sorteer op naam</option>
            <option value="bouwjaar" <?= $sorteren==='bouwjaar'?'selected':'' ?>>Sorteer op bouwjaar</option>
            <option value="aangemaakt_op" <?= $sorteren==='aangemaakt_op'?'selected':'' ?>>Sorteer op datum toegevoegd</option>
        </select>

        <button type="submit">Zoeken</button>
    </form>

    <!-- Resultaten tonen -->
    <section class="wonderen_list">
        <?php if (count($wonderen) > 0): ?>
            <?php foreach ($wonderen as $w): ?>
                <article class="wonder_card">
                    <?php if (!empty($w['afbeelding'])): ?>
                        <img src="uploads/<?= htmlspecialchars($w['afbeelding']) ?>" alt="<?= htmlspecialchars($w['naam']) ?>">
                    <?php endif; ?>
                    <h2><?= htmlspecialchars($w['naam']) ?></h2>
                    <p><strong>Type:</strong> <?= ucfirst($w['type']) ?></p>
                    <p><strong>Werelddeel:</strong> <?= htmlspecialchars($w['werelddeel']) ?></p>
                    <p><strong>Bouwjaar:</strong> <?= $w['bouwjaar'] ?? 'Onbekend' ?></p>
                    <p><?= substr(htmlspecialchars($w['beschrijving']), 0, 150) ?>...</p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Geen wereldwonderen gevonden.</p>
        <?php endif; ?>
    </section>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
