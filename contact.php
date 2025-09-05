<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Codex Mundi</title>
    <link rel="stylesheet" href="./css/stylesheet.css">
</head>
<body>
    <?php include "./includes/header.php"; ?>

    <main class="pagina">
        <section class="content_box">
            <h1>Contact</h1>
            <p>Heb je vragen of wil je meer weten over Codex Mundi? Neem gerust contact met ons op.</p>

            <form action="verwerk_contact.php" method="post" class="contact_form">
                <label for="naam">Naam:</label>
                <input type="text" id="naam" name="naam" required>

                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" required placeholder="voorbeeld@gmail.com">

                <label for="bericht">Bericht:</label>
                <textarea id="bericht" name="bericht" rows="6" required></textarea>

                <input type="submit" value="Versturen">
            </form>

            <h2>Onze gegevens</h2>
            <p><strong>Adres:</strong> Betaplein 18, 2321 KS Leiden</p>
            <p><strong>Telefoon:</strong> 06 123 45692</p>
            <p><strong>Email:</strong> contact@mbocinemas.nl</p>
        </section>
    </main>

    <?php include "./includes/footer.php"; ?>
</body>
</html>
