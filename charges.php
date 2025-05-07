<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'php/config.php';

if (!isset($_SESSION['id'])) {
    die("Vous devez être connecté pour accéder à cette page.");
}

$adherent_id = $_SESSION['id'];

$stmt = $pdo->prepare("SELECT * FROM charges WHERE adherent_id = ?");
$stmt->execute([$adherent_id]);
$charges = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Personnes à charge</title>
</head>
<body>
    <h1>Mes personnes à charge</h1>

    <?php if (count($charges) > 0): ?>
        <ul>
            <?php foreach ($charges as $charge): ?>
                <li>
                    <?= htmlspecialchars($charge['prenom']) . ' ' . htmlspecialchars($charge['nom']) ?> -
                    Né(e) le <?= htmlspecialchars($charge['date_naissance']) ?> -
                    Genre : <?= htmlspecialchars($charge['genre']) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune personne à charge trouvée.</p>
    <?php endif; ?>

    <p><a href="adherent_dashboard.php">Retour au tableau de bord</a></p>
</body>
</html>
