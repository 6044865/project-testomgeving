<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// $rol  = $_SESSION['user_role'] ?? 'bezoeker';
// $naam = $_SESSION['username'] ?? null;


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$rol  = $_SESSION['user_role'] ?? 'bezoeker';
$naam = $_SESSION['username'] ?? null;
?>


<header>
    <article id="logo">
        <a href="index.php"><img src="./img/wereldwonderen.png" alt="Logo wereldwonderen"></a>
    </article>

    <!-- <article class="search-container">
        <form action="#" method="post">
            <input type="text" placeholder=" Search..." name="search">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
    </article> -->

    <article id="icon_menu">
        <i class="fa fa-bars" aria-hidden="true"></i>
    </article>

    <nav id="sub_nav">
        <a href="index.php" class="nav_item">Home</a>
        <a href="films.php" class="nav_item">Wereldwonderen</a>
        <a href="aboutus.php" class="nav_item">Over ons</a>
        <a href="contact.php" class="nav_item">Contact</a>

        <?php if (in_array($rol, ["onderzoeker","redacteur","archivaris","beheerder"])): ?>
            <a href="toevoegen.php" class="nav_item">Toevoegen</a>
        <?php endif; ?>

        <?php if (in_array($rol, ["redacteur","beheerder"])): ?>
            <a href="goedkeuren.php" class="nav_item">Goedkeuren</a>
        <?php endif; ?>

        <?php if ($rol === "beheerder"): ?>
            <a href="gebuikersBeheren.php" class="nav_item">Gebruikersbeheer</a>
            <a href="dashboard.php" class="nav_item">Dashboard</a>
        <?php endif; ?>
    </nav>

    <article id="icon_login">
        <?php if (!empty($_SESSION['isIngelogd'])): ?>
           <a> <span class="welkom_naam" class="nav_item"> <?php echo ucfirst($naam); ?></span></a>
            <a href="logout.php"><img src="img/logout.png" alt="logout icon"></a>
        <?php else: ?>
            <a href="login.php"><img src="img/inlog.png" alt="login icon"></a>
        <?php endif; ?>
    </article>
</header>
