<?php
require_once __DIR__ . "/includes/auth.php";  // check login
require_once __DIR__ . "/classDatabase.php";

$db = new Database();
$pdo = $db->getConnection();

// ✅ alleen beheerder toegang
if ($_SESSION['user_role'] !== 'beheerder') {
    header("Location: index.php");
    exit;
}

// Alle gebruikers ophalen
$stmt = $pdo->query("SELECT id, username, email, rol FROM gebruikers ORDER BY id ASC");
$gebruikers = $stmt->fetchAll();
?>

<?php include "header.php"; ?>

<main style="width:80%;margin:30px auto;">
    <h2>Gebruikersbeheer</h2>

    <a href="gebruiker_toevoegen.php" 
       style="display:inline-block;padding:8px 12px;background:#28a745;color:#fff;text-decoration:none;border-radius:5px;margin-bottom:15px;">
       ➕ Nieuwe gebruiker
    </a>

    <table border="1" cellspacing="0" cellpadding="8" width="100%">
        <thead style="background:#f4f4f4;">
            <tr>
                <th>ID</th>
                <th>Naam</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($gebruikers): ?>
                <?php foreach ($gebruikers as $gebruiker): ?>
                    <tr>
                        <td><?= htmlspecialchars($gebruiker['id']); ?></td>
                        <td><?= htmlspecialchars($gebruiker['username']); ?></td>
                        <td><?= htmlspecialchars($gebruiker['email']); ?></td>
                        <td><?= htmlspecialchars($gebruiker['rol']); ?></td>
                        <td>
                            <a href="gebruiker_bewerken.php?id=<?= $gebruiker['id']; ?>">✏️ Bewerken</a> | 
                            <a href="gebruiker_verwijderen.php?id=<?= $gebruiker['id']; ?>" 
                               onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">🗑️ Verwijderen</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">Geen gebruikers gevonden.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php include "footer.php"; ?>
