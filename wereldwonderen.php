

<<<<<<< HEAD

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>wereldwonderen</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">
    <script src="../project-testomgeving/js/index.js" defer></script>
  
    <meta name="description" 
      content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
<meta name="keywords" 
      content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">



    <meta name="author" content="A.Alhaji, G.Verpaalen">

</head>

<body class="home_pagina">
   <?php
session_start();

// check rol
$rol = $_SESSION['user_role'] ?? 'bezoeker'; // standaard bezoeker als niemand is ingelogd
?>

<?php include "./includes/header.php"; ?>


    <main>
       
<form method="GET" action="">
    <label for="tag">Filter op tag:</label>
    <select name="tag" id="tag" onchange="this.form.submit()">
    <option value=""></option>
  <option value="Antiek">Antiek</option>
  <option value="Modern">modern</option>
  <option value="UNESCO">UNESCO</option>
  <option value="Bestaat">bestaat</option>
  <option value="Verwoest">Verwoest</option>
  <option value="Wereldeel">Wereldeel</option>
</select>

 </select>
</form>
   

          
       
     


    </main>
    <?php include "./includes/footer.php"; ?>

    <script src="./js/main.js" defer></script>
    <script>
        // slide show

        let slidesArray = document.getElementsByClassName("slide");
        let index = 0;

        setInterval(slideShow, 1800)
        function slideShow() {
            //    veberg de huidige slide
            slidesArray[index].style.display = "none";
            index++;

            if (index >= slidesArray.length) {
                index = 0;
            }
            slidesArray[index].style.display = "block";
        }
    </script>
</body>

</html>

<?php
// Verbinden met database
$host = "localhost";
$user = "root";   // pas aan
$pass = "";       // pas aan
$dbname = "wereldwonderen_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Verbinding mislukt: " . $conn->connect_error);
}

// Ophalen geselecteerde tag
$tag = isset($_GET['tag']) ? $_GET['tag'] : "";

// Query maken
if ($tag) {
    $stmt = $conn->prepare("SELECT * FROM wereldwonderen WHERE tag = ?");
    $stmt->bind_param("s", $tag);
} else {
    $stmt = $conn->prepare("SELECT * FROM wereldwonderen");
}

$stmt->execute();
$result = $stmt->get_result();

// Resultaten tonen
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>" . htmlspecialchars($row['naam']) . " (" . htmlspecialchars($row['tag']) . ")</li>";
}
echo "</ul>";

$stmt->close();
$conn->close();
?>
=======
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