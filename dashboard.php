
<?php
require_once "./includes/auth.php";

$rol  = $_SESSION['user_role'] ?? 'bezoeker';
$naam = $_SESSION['username'] ?? 'Gast';
?>

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

<body class="dahshborad_pagina">
    
<?php include "./includes/header.php"; ?>
<main>
    


<h1>Welkom op je dashboard, <?php echo ucfirst($naam); ?>! (<?php echo ucfirst($rol); ?>)</h1>

<?php if ($rol === "onderzoeker"): ?>
    <p>Je kunt nieuwe wereldwonderen toevoegen.</p>
    <a href="toevoegen.php">➕  wereldwonderen Toevoegen</a>
     <a href="wereldwonderaanpassen.php"> Eigen wereldwonderen aanpassen/auteur</a>
<?php endif; ?>

<?php if ($rol === "redacteur"): ?>
    <p>Hier zie je de bijdragen die je moet goedkeuren.</p>
    <a href="">✅ aanpassingen Goedkeuren</a>
    <a href="">meldingen krijgen van de nieuwste wijzigingen</a>
    <a href="">Tags toevoegen</a>
    
<?php endif; ?>

<?php if ($rol === "beheerder"): ?>
    <p>Beheerdersopties:</p>
    <ul>
        <h1>Gebuikers beheer</h1>
         <li><a href="">👤 Gebruikers list</a></li>
          <li><a href="registreren.php"> Gebruikers Toeveogen</a></li>
        <li><a href="gebuikersBeheren.php"> Gebruikers verwijderen</a></li>
          <li><a href="gebruiker_bewerken.php"> Gebruikers aanpassen</a></li>
          <h1>wereldwonderen beheren</h1>
           <li><a href="toevoegenWereldwonder.php"> wereld wonderen Toeveogen</a></li>
        <li><a href="gebruikers.php">wereld verwijderen</a></li>
          <li><a href="wereldwonderBewerken.php"> wereldwonderen aanpassen</a></li>
          <!-- eigen filter -->
        
         
          <!-- <a href="registreren.php">Heb je nog geen account? Klik hier om te registreren.</a><br><br> -->
        <li><a href="logs.php">📜 Logbestanden</a></li>
    </ul>
<?php endif; ?>
</main>
<footer>
        <?php include "./includes/footer.php"; ?>

</footer>
</body>

