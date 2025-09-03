<?php


require_once "classDatabase.php";
require_once "classGebruiker.php";
require_once "classKlant.php";

session_start();
if (isset($_POST["submit"])) {
    if (!empty($_POST["username"])  && !empty($_POST["wachtwoord"])) {
        // $username = $_POST["username"];
        // $wachtwoord_geb = $_POST["wachtwoord"];

        $kalnt = new  Klant();
        $goedOfFout = $kalnt->inloggen($_POST["username"], $_POST["wachtwoord"]);

        if (!$goedOfFout) {
            ?>
            <!-- kleurijke popup onjuiste wachtwoord of username -->

                    <section id="popup_box">
                        <p id="popup_bericht">wachtwoord of gebruiker naam zijn onjuist.</p>

                    </section>
                    <style>
                        #popup_box {
                            background-color: red;
                            width: 300px;

                            display: none;
                            position: absolute;
                            bottom: 10%;


                        }

                        #popup_box p {
                            color: white;
                            font-size: 1.5rem;
                            padding: 10px;

                        }
                    </style>

                    <script>
                        let popupBox = document.getElementById('popup_box');
                        popupBox.style.display = 'block';
                        setTimeout(function() {
                            popupBox.style.display = 'none';

                        }, 4000);
                    </script>
                    <!-- TOT HIER POPUP -->
                     <?php
        }
    } else {
        // echo "Je moet alle velden invullen!!";
        echo "<script>alert('Je moet alle velden invullen!!');</script>";
        // header("Location: medewerkerRol.html");
        // exit;
    }
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mbo_cinemas/home</title>
    <script src="https://kit.fontawesome.com/0c7c27ff53.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./css/stylesheet.css">


</head>

<body class="inlog_pagina">
    <header>
        <article id="logo">
            <a href="index.html"><img src="./img/logombocinemas.png" alt=""></a>
        </article>



        <article class="search-container">
            <form action="#" method="post">
                <input type="text" placeholder="Search.." name="search">
                <button type="submit"><i class="fa fa-search"></i></button>
            </form>
        </article>
        <article id="icon_menu">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </article>
        <nav id="sub_nav">
            <a href="index.html">Home</a>
            <a href="films.php">Films</a>

            <a href="huren.html">Zaal Huren</a>
            <a href="aboutus.html">over ons</a>
            <a href="contact.html">Contact</a>
        </nav>
        <article id="icon_login">
            <a href="inlogGebruiker.php"><img src="img/user.png"></i></a>
        </article>

    </header>
    <main>
        <section class="form_box">
            <h1>Log in </h1>
            <form id="inlogform" action="inlogGebruiker.php" method="post">
                <label for="username"><span id="asterisk">*</span>User name: </label>
                <p id="warning13"></p>
                <input type="text" name="username" id="van" required>



                <label for="wachtwoord"><span id="asterisk">*</span>wachtwoord: </label>
                <p id="warning14"></p>
                <input type="text" name="wachtwoord" id="wachtwoord" required>


                <input type="submit" value="Log in" name="submit" id="submit">

            </form>
            <article>
                <a href="accountmaken.html">Hebt u nog geen account? klik hier om account te maken.</a> <br><br>
                <a href="#">Bent u wachtwoord vergeten? klik hier.</a> <br><br>
                <a href="medewerkerRol.html">Bent u een medewerker. Klikt hier om in te loggen als medewerker.</a> <br><br>

            </article>
        </section>



    </main>

    <footer>
        <article class="footerarticle">
            <img class="logoimgfooter" src="img/logombocinemas.png" alt="logo van mbocinemas">
        </article>
        <article class="footerarticle">
            <h2>Contact gegevens:</h2>
            <h3>telefoonnummer</h3>
            <p>06 123 45692</p>
            <h3>emailadress:</h3>
            <p>contact@mbocinemas.nl</p>
        </article>
        <article class="footerarticle">
            <h2>Postadress:</h2>
            <p>Betaplein 18</p>
            <p>2321 KS Leiden</p>
        </article>

    </footer>
    <script src="js/inlogGebruiker.js"></script>
    <style>
        .inlog_pagina main a {
            color: white;
            text-decoration: underline;


        }

        .inlog_pagina main a:hover {
            color: aqua;

        }
    </style>


</body>

</html>