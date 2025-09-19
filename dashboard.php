<?php
require_once "./includes/auth.php";

$rol  = $_SESSION['user_role'] ?? 'bezoeker';
$naam = $_SESSION['username'] ?? 'Gast';
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wereldwonderen Dashboard</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">

    <meta name="description" 
      content="Codex Mundi is een digitaal archief van de 21 wereldwonderen. Ontdek informatie, foto's, verhalen en geschiedenis van de klassieke, nieuwe en natuurlijke wereldwonderen.">
    <meta name="keywords" 
      content="wereldwonderen, 7 wereldwonderen, nieuwe wereldwonderen, klassieke wereldwonderen, geschiedenis, cultuur, Codex Mundi, digitaal archief, erfgoed">
    <meta name="author" content="A.Alhaji, G.Verpaalen">

    <style>
        body.dashboard_pagina {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .cards {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            width: 250px;
            text-align: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        }

        .card h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color:   #D9A299;
        }

        .card a {
            display: inline-block;
            margin: 8px 0;
            padding: 10px 15px;
            background-color: #D9A299;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.2s;
        }

        .card a:hover {
            background-color:#D9A299;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #eee;
            margin-top: 50px;
        }
    </style>
</head>

<body class="dashboard_pagina">

<?php include "./includes/header.php"; ?>

<main>
    <h1>Welkom!</h1> <strong><p>Jouw naam is:  <?php echo ucfirst($naam); ?></p><p>  Jouw rol is:  <?php echo ucfirst($rol); ?></p></strong>

    <div class="cards">
        <?php if ($rol === "onderzoeker"): ?>
                  <div class="card">
              
                  <a href="onderzoekerBeheer.php">Eigen wereldwonderen beheren</a>
                  </div>
            <div class="card">
              
                  <a href="toevoegenWonderOnderzoeker.php">Wereldwonderen toevoegen</a>
                  </div>
         
        <?php endif; ?>

        <?php if ($rol === "redacteur"): ?>
            
            <div class="card">
             
                <a href="redacteurGoedkeuren.php">🔔/ik wil hier aantal meldingen per nog niet gekurde wijziginen hier getoond wordt/ Meldingen van wijzigingen en  Aanpassingen goedkeure</a></div>
                        <div class="card">
                <a href="redaceurWonderLijst.php">Tags en locatie controleren</a>
            </div>
        <?php endif; ?>

         <?php if ($rol === "archivaris"): ?>
            
            <div class="card">
             
            <p> Je mag alleen bepaalde info wijzigen</p>
                <a href="archivarisBeheer.php">Wonder bewerken</a></div>
               
            </div>
        <?php endif; ?>

        <?php if ($rol === "beheerder"): ?>
            <div class="card">
             
                <a href="gebuikersBeheren.php">Gebruikers beheren</a>
            </div>
            <div class="card">
              
                <a href="wonderBeheer.php"> Wereldwonderen beheren</a>
            </div>
            <div class="card">
            
                <a href="logs.php"> Logbestanden bekijken</a>
            </div>
        <?php endif; ?>
    </div>
</main>

<footer>
    <?php include "./includes/footer.php"; ?>
</footer>

</body>
</html>
