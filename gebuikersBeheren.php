<?php
require_once __DIR__ . "/includes/auth.php";  
require_once __DIR__ . "/classDatabase.php";

$db = new Database();
$pdo = $db->getConnection();

// ✅ Alleen beheerders mogen deze pagina zien
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

// Alle gebruikers ophalen
$stmt = $pdo->query("SELECT gebruiker_id, gebruikersnaam, email, rol, aangemaakt_op 
                     FROM gebruikers ORDER BY gebruiker_id ASC");
$gebruikers = $stmt->fetchAll();
?>

<?php include "./includes/header.php"; ?>

<main style="width:80%;margin:30px auto;">
    <h2>Gebruikersbeheer</h2>

    <a href="registreren.php" 
       style="display:inline-block;padding:8px 12px;background:#28a745;color:#fff;text-decoration:none;border-radius:5px;margin-bottom:15px;"> 
       ➕ Nieuwe gebruiker
    </a>

    <table border="1" cellspacing="0" cellpadding="8" width="100%">
        <thead style="background:#f4f4f4;">
            <tr>
                <th>ID</th>
                <th>Gebruikersnaam</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Aangemaakt op</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($gebruikers): ?>
                <?php foreach ($gebruikers as $g): ?>
                    <tr>
                        <td><?= htmlspecialchars($g['gebruiker_id']); ?></td>
                        <td><?= htmlspecialchars($g['gebruikersnaam']); ?></td>
                        <td><?= htmlspecialchars($g['email']); ?></td>
                        <td><?= htmlspecialchars($g['rol']); ?></td>
                        <td><?= htmlspecialchars($g['aangemaakt_op']); ?></td>
                        <td>
                            <a href="gebruiker_bewerken.php?id=<?= $g['gebruiker_id']; ?>">✏️ Bewerken</a> | 
                            <a href="gebruiker_verwijderen.php?id=<?= $g['gebruiker_id']; ?>" 
                               onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">🗑️ Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6">Geen gebruikers gevonden.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include "./includes/footer.php"; ?>
