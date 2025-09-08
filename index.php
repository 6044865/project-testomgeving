
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

        <section>
            <article>
               
                 <img src="img/homeFoto.png" id="homeFoto" alt=""chinese muur in zonlicht"">
            </article>
        </section>

        <!-- <section id="slide_show">

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
        </section> -->
       
        <section id="welkom_text">
            <article>
                <p>Mijn naam is Aurelius van Oort. Mijn verhaal begint niet bij mij, maar drie generaties terug. 
Mijn overgrootvader, Cornelis van Oort, was een man van feiten en vuur. Hij doceerde 
geschiedenis op een kille kostschool aan de rand van Gouda, waar zijn stem net zo luid klonk 
als de klokken van de kerk naast het gebouw. Zijn lessen gingen zelden over jaartallen, ze 
gingen over verhalen. Over macht, mystiek en beschavingen die rezen als zonnen en 
ondergingen als sterren. 
</p>
<p>
Zijn zoon, mijn grootvader Theodoor van Oort, reisde de wereld rond. In zijn klaslokaal 
hingen tapijten uit Babylon, stenen uit Rome, en een stuk marmer waarvan hij zweerde dat 
het ooit deel uitmaakte van de tempel van Artemis. Hij sprak zes talen, schreef in zeven, en 
zweeg in acht. Volgens familieverhalen vond hij ooit een kaartfragment in een oud boek in 
Alexandrië, een kaart die geen land weergaf, maar wonderen. 
Mijn vader, Matthias van Oort, was stiller. Hij hield vast aan zijn krijtbord en zijn boeken. Maar 
achter zijn bril schuilde een vurige passie voor wat hij “de echo’s van beschavingen” noemde. 
Hij verzamelde alles over de zeven klassieke wereldwonderen, de zeven nieuwe en de zeven 
natuurlijke wereldwonderen vond hij prachtig. Ook beweerde hij dat er overeenkomsten in 
en patronen verborgen lagen in hun verhalen. Patronen die iets groters, iets gevaarlijkers 
blootlegden.
</p> 
<p>
En dan ben ik er. Aurelius. Ik ben geen leraar. Geen avonturier. Geen archeoloog. 
Ik ben een barista bij Starbucks. Maar net als hen ben ik opgegroeid met een fascinatie voor 
het ontstaan en de ondergang van beschavingen. En al sinds mijn tiende verjaardag toen ik 
van mijn vader een oud, leren boek kreeg over de 21 wereldwonderen, voel ik een 
onverklaarbare drang om hun geheimen te ontrafelen. 
</p>
<p>
Het Digitale Archief 
</p>
<p>
De wereld verandert, en kennis moet mee veranderen. Terwijl de boeken van mijn 
voorouders langzaam vergaan in dozen op zolder, besef ik: het is tijd om de erfenis te 
digitaliseren. Te beveiligen. Te delen.
</p>
                    
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