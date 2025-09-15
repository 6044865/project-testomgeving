<?php
require_once "classDatabase.php";
session_start();

// check login
if (!isset($_SESSION['isIngelogd']) || $_SESSION['isIngelogd'] !== true) {
    header("location: login.php");
    exit();
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $stmt = $conn->query("SELECT wonder_id, naam FROM wereldwonderen ORDER BY naam ASC");
    $wonderen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij laden: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Wereldwonder selecteren</title>
</head>

<body>
    <h1>Kies een wereldwonder om te bewerken</h1>
<form method="get" action="wereldwonderaanpassen.php">
    <?php
    // query: pak de juiste kolommen uit je tabel
    $stmt = $conn->query("SELECT naam FROM wereldwonderen ORDER BY naam ASC");
    $wonderen = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <select name="wonder_id" id="wonder_id" required>
        <option value="">-- Kies --</option>
        <?php foreach ($wonderen as $wonder): ?>
            <option value="<?= htmlspecialchars($wonder['wonder_id']) ?>">
                <?= htmlspecialchars($wonder['wonder_id']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button onclick="gaNaarAanpassen()" type="submit">Bewerken</button>
    <script>
        function gaNaarAanpassen() {
            const id = document.getElementById('wonder_id').value;
            if (id) {
                window.location.href = 'wereldwonderaanpassen.php?wonder_id=' + id;
            } else {
                alert('Kies eerst een wereldwonder!');
            }
        }
    </script>
    </form>
</body>

</html>