<?php
include '../php/fonctions.php';
session_start();
require_once '../php/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

$id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : null;
if (!$id || $id <= 0) {
    die("ID utilisateur manquant ou invalide.");
}

$admin = $_SESSION['username'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Utilisateur non trouvé.");
}

$stmtCharges = $pdo->prepare("SELECT * FROM charges WHERE adherent_id = ?");
$stmtCharges->execute([$id]);
$charges = $stmtCharges->fetchAll();

// Mise à jour des informations utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $nom       = $_POST['nom'];
    $prenom    = $_POST['prenom'];
    $email     = $_POST['email'];
    $telephone = $_POST['telephone'];
    $genre     = $_POST['genre'];
    $role      = $_POST['role'];

    $stmtUpdate = $pdo->prepare("
        UPDATE users 
        SET nom = ?, prenom = ?, email = ?, telephone = ?, genre = ?, role = ?
        WHERE id = ?
    ");
    $stmtUpdate->execute([$nom, $prenom, $email, $telephone, $genre, $role, $id]);

    $stmtLog = $pdo->prepare("
        INSERT INTO modifications_log (user_id, admin, date_modification) 
        VALUES (?, ?, NOW())
    ");
    $stmtLog->execute([$id, $admin]);

    header("Location: modifier_utilisateur.php?id=$id&modif=1");
    exit();
}

// Ajout de personne à charge
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_charge'])) {
    $charge_nom = $_POST['charge_nom'];
    $charge_prenom = $_POST['charge_prenom'];
    $charge_date_naissance = $_POST['charge_date_naissance'];
    $charge_genre = $_POST['charge_genre'];

    $stmt = $pdo->prepare("
        INSERT INTO charges (adherent_id, nom, prenom, date_naissance, genre) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$id, $charge_nom, $charge_prenom, $charge_date_naissance, $charge_genre]);

    header("Location: modifier_utilisateur.php?id=$id");
    exit();
}

// Suppression de personne à charge
if (isset($_POST['supprimer_charge'])) {
    $charge_id = intval($_POST['charge_id']);
    $stmt = $pdo->prepare("DELETE FROM charges WHERE id = ? AND adherent_id = ?");
    $stmt->execute([$charge_id, $id]);

    header("Location: modifier_utilisateur.php?id=$id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="../css/style.css">
    <script>
        function toggleCharges() {
            const div = document.getElementById('personnes-charge');
            div.style.display = (div.style.display === 'none') ? 'block' : 'none';
        }
    </script>
</head>
<body>
<?php include "../includes/header.php"; ?>

<main class="container" >

    <?php if (isset($_GET['modif'])): ?>
        <p class="success-message">Informations mises à jour avec succès.</p>
    <?php endif; ?>


    <h1>Modifier un utilisateur</h1>

    <form method="post" class="tab-content">
        <input type="hidden" name="update_user" value="1">

        <label>Nom :
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
        </label>

        <label>Prénom :
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
        </label>

        <label>Email :
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </label>

        <label>Téléphone :
            <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone']) ?>" required>
        </label>

        <label>Genre :
            <select name="genre" required>
                <option value="Homme" <?= $user['genre'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                <option value="Femme" <?= $user['genre'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
                <option value="Autre" <?= $user['genre'] === 'Autre' ? 'selected' : '' ?>>Autre</option>
            </select>
        </label>

        <label>Rôle :
            <select name="role" required>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="adherent" <?= $user['role'] === 'adherent' ? 'selected' : '' ?>>Adhérent</option>
                <option value="autre" <?= $user['role'] === 'autre' ? 'selected' : '' ?>>Autre</option>
            </select>
        </label>

        <p><strong>Personnes à charge :</strong> <?= count($charges) ?></p>

        <button type="submit" class="btn-primary">Enregistrer</button>
    </form>

    <hr>

    <button onclick="toggleCharges()" class="btn-primary">Afficher/Masquer les personnes à charge</button>

    <div id="personnes-charge" style="display:none; margin-top:20px;" class="tab-content">
        <h2>Personnes à charge</h2>

        <?php if (count($charges) === 0): ?>
            <p>Aucune personne à charge.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($charges as $charge): ?>
                    <li class="admin-row">
                        <span><?= htmlspecialchars($charge['nom']) ?> <?= htmlspecialchars($charge['prenom']) ?> (<?= htmlspecialchars($charge['genre']) ?>, <?= htmlspecialchars($charge['date_naissance']) ?>)</span>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="charge_id" value="<?= $charge['id'] ?>">
                            <button type="submit" name="supprimer_charge" class="btn-danger">Supprimer</button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <h3>Ajouter une personne à charge :</h3>
        <form method="post">
            <input type="hidden" name="ajouter_charge" value="1">
            <label>Nom : <input type="text" name="charge_nom" required></label>
            <label>Prénom : <input type="text" name="charge_prenom" required></label>
            <label>Date de Naissance : <input type="date" name="charge_date_naissance" required></label>
            <label>Genre :
                <select name="charge_genre" required>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                    <option value="Autre">Autre</option>
                </select>
            </label>
            <button type="submit" class="btn-primary">Ajouter</button>
        </form>
    </div>

    <p class="return-button"><a href="admin_dashboard.php" class="btn-primary">← Retour au tableau de bord</a></p>
</main>
</body>
</html>
