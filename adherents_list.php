<?php 
session_start();
require_once 'php/config.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

try {
    $stmt = $pdo->query("SELECT * FROM users WHERE role = 'adherent' OR role = 'admin'");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des utilisateurs : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        function toggleCharges(id) {
            const row = document.getElementById('charges-' + id);
            if (row) {
                row.style.display = (row.style.display === 'table-row') ? 'none' : 'table-row';
            }
        }
    </script>
</head>
<body>

<header>
    <?php include 'includes/header.php'; ?>
</header>

<main class="container">
    <h1 class="center">Liste des utilisateurs</h1>

    <table>
        <thead>
            <tr>
                <th>Action</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Nb de charges</th>
                <th>Rôle</th>
                <th>Modifier</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <?php
                    $stmt2 = $pdo->prepare("SELECT * FROM charges WHERE adherent_id = ?");
                    $stmt2->execute([$user['id']]);
                    $charges = $stmt2->fetchAll();
                    $count = count($charges);

                    $stmt3 = $pdo->prepare("SELECT * FROM modifications_log WHERE user_id = ? ORDER BY date_modification DESC LIMIT 1");
                    $stmt3->execute([$user['id']]);
                    $modif = $stmt3->fetch();
                ?>
                <tr>
                    <td><span class="toggle-btn" onclick="toggleCharges(<?= $user['id'] ?>)">Voir</span></td>
                    <td><?= htmlspecialchars($user['nom']) ?></td>
                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $count ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><a href="modifier_utilisateur.php?id=<?= $user['id'] ?>" class="btn">Modifier</a></td>
                </tr>
                <tr id="charges-<?= $user['id'] ?>" class="charges">
                    <td colspan="7">
                        <?php if ($count > 0): ?>
                            <ul>
                                <?php foreach ($charges as $charge): ?>
                                    <li><?= htmlspecialchars($charge['prenom']) ?> <?= htmlspecialchars($charge['nom']) ?> — Né(e) le <?= htmlspecialchars($charge['date_naissance']) ?> — Genre : <?= htmlspecialchars($charge['genre']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <em>Aucune personne à charge</em>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($modif): ?>
                    <tr>
                        <td colspan="7" class="modification">
                            Modifié par <?= htmlspecialchars($modif['admin']) ?> le <?= date("d/m/Y à H:i", strtotime($modif['date_modification'])) ?>
                        </td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p class="return-button"><a href="admin_dashboard.php" class="btn-primary">← Retour au tableau de bord</a></p>
</main>

<footer>
    <?php include 'includes/footer.html'; ?>
</footer>

</body>
</html>
