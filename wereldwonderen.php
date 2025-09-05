
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
