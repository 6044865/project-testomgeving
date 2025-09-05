<?php
require_once "classDatabase.php";

$db = new Database();
$conn = $db->getConnection();

// Parameters ophalen uit GET
$zoekterm   = $_GET['zoekterm'] ?? '';
$type       = $_GET['type'] ?? '';
$werelddeel = $_GET['werelddeel'] ?? '';
$status     = $_GET['status'] ?? '';
$sorteren   = $_GET['sorteren'] ?? 'naam';

// Basisquery
$query = "SELECT w.*, 
                 MIN(f.bestandspad) AS foto 
          FROM wereldwonderen w
          LEFT JOIN fotos f ON f.wonder_id = w.wonder_id AND f.goedgekeurd = 1
          WHERE 1=1";

// Zoekterm
if (!empty($zoekterm)) {
    $query .= " AND w.naam LIKE :zoekterm";
}
// Filters
if (!empty($type))       $query .= " AND w.type = :type";
if (!empty($werelddeel)) $query .= " AND w.werelddeel = :werelddeel";
if (!empty($status))     $query .= " AND w.status = :status";

// Sorteren (alleen veilige kolommen)
$toegestaneSorts = ['naam','bouwjaar','aangemaakt_op'];
if (!in_array($sorteren, $toegestaneSorts)) $sorteren = 'naam';
$query .= " GROUP BY w.wonder_id ORDER BY w.$sorteren ASC";

$stmt = $conn->prepare($query);

// Parameters binden
if (!empty($zoekterm))   $stmt->bindValue(':zoekterm', "%$zoekterm%");
if (!empty($type))       $stmt->bindValue(':type', $type);
if (!empty($werelddeel)) $stmt->bindValue(':werelddeel', $werelddeel);
if (!empty($status))     $stmt->bindValue(':status', $status);

$stmt->execute();
$wonderen = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Wereldwonderen</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <style>
        .filter_form {
            margin: 20px auto;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }
        .filter_form input, .filter_form select, .filter_form button {
            padding: 6px;
        }
        .wonderen_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .wonder_card {
            border: 1px solid #ccc;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background: #fafafa;
            transition: 0.2s;
        }
        .wonder_card:hover {
            transform: scale(1.03);
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
        }
        .wonder_card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .wonder_card h2 {
            margin: 10px 0;
            font-size: 18px;
        }
        .no-results {
            text-align: center;
            color: #777;
            margin-top: 40px;
        }
    </style>
</head>
<body>
<?php include "./includes/header.php"; ?>

<main class="pagina">
    <h1 style="text-align:center;">Wereldwonderen</h1>

    <!-- Zoek- en filterformulier -->
    <form method="get" action="wereldwonderen.php" class="filter_form">
        <input type="text" name="zoekterm" placeholder="Zoek op naam..." value="<?= htmlspecialchars($zoekterm) ?>">

        <select name="type">
            <option value="">-- Type --</option>
            <option value="klassiek"   <?= $type==='klassiek'?'selected':'' ?>>Klassiek</option>
            <option value="modern"     <?= $type==='modern'?'selected':'' ?>>Modern</option>
            <option value="natuurlijk" <?= $type==='natuurlijk'?'selected':'' ?>>Natuurlijk</option>
        </select>

        <select name="werelddeel">
            <option value="">-- Werelddeel --</option>
            <option value="Afrika" <?= $werelddeel==='Afrika'?'selected':'' ?>>Afrika</option>
            <option value="Azië" <?= $werelddeel==='Azië'?'selected':'' ?>>Azië</option>
            <option value="Europa" <?= $werelddeel==='Europa'?'selected':'' ?>>Europa</option>
            <option value="Noord-Amerika" <?= $werelddeel==='Noord-Amerika'?'selected':'' ?>>Noord-Amerika</option>
            <option value="Zuid-Amerika" <?= $werelddeel==='Zuid-Amerika'?'selected':'' ?>>Zuid-Amerika</option>
            <option value="Oceanië" <?= $werelddeel==='Oceanië'?'selected':'' ?>>Oceanië</option>
        </select>

        <select name="status">
            <option value="">-- Status --</option>
            <option value="UNESCO Werelderfgoed" <?= $status==='UNESCO Werelderfgoed'?'selected':'' ?>>UNESCO</option>
            <option value="Herbouwd" <?= $status==='Herbouwd'?'selected':'' ?>>Herbouwd</option>
            <option value="Mythisch" <?= $status==='Mythisch'?'selected':'' ?>>Mythisch</option>
        </select>

        <select name="sorteren">
            <option value="naam" <?= $sorteren==='naam'?'selected':'' ?>>Naam</option>
            <option value="bouwjaar" <?= $sorteren==='bouwjaar'?'selected':'' ?>>Bouwjaar</option>
            <option value="aangemaakt_op" <?= $sorteren==='aangemaakt_op'?'selected':'' ?>>Toegevoegd</option>
        </select>

        <button type="submit">Filter</button>
    </form>

    <!-- Resultaten -->
    <section class="wonderen_grid">
        <?php if ($wonderen): ?>
            <?php foreach ($wonderen as $w): ?>
                <article class="wonder_card">
                    <a href="wonder.php?id=<?= $w['wonder_id'] ?>">
                        <?php if (!empty($w['foto'])): ?>
                            <img src="uploads/<?= htmlspecialchars($w['foto']) ?>" alt="<?= htmlspecialchars($w['naam']) ?>">
                        <?php else: ?>
                            <img src="img/no-image.png" alt="Geen afbeelding">
                        <?php endif; ?>
                        <h2><?= htmlspecialchars($w['naam']) ?></h2>
                    </a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-results">Geen wereldwonderen gevonden.</p>
        <?php endif; ?>
    </section>
</main>

<?php include "./includes/footer.php"; ?>
</body>
</html>
