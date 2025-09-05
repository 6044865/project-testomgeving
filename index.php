
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
        <section>
            <article id="logo_main">
                <img src="img/logo2.png" alt="">
            </article>
        </section>

        <section id="slide_show">

            <article>
                <div class="slide">
                    <a href="meerInfoFilm.php?id=5">
                        <img src="./img/dunefilm.jpeg" alt="dune film poster in slideshow">
                    </a>
                </div>
                <div class="slide">
                    <a href="meerInfoFilm.php?id=1">
                        <img src="./img/frozen film.webp" alt="frozen film poster in slideshow">
                    </a>
                </div>
                <div class="slide">
                    <a href="meerInfoFilm.php?id=2">
                        <img src="./img/vaianafilm.webp" alt="vaiana film poster in slideshow">
                    </a>
                </div>

                <div class="slide">
                    <a href="meerInfoFilm.php?id=6">
                        <img src="./img/spidermanfilm.jpg" alt="spiderman film poster in slideshow">
                    </a>
                </div>
            </article>
        </section>
       
        <section id="welkom_text">
            <article>
                <p>
                    
            </article>
        </section>


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