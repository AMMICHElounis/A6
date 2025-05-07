<?php
session_start();
include('php/login.php');
include('includes/header.php');

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $update = $pdo->prepare("UPDATE users SET nom = ?, email = ?, role = ? WHERE id = ?");
    $update->execute([$nom, $email, $role, $id]);

    // Historique de modification
    $admin = $_SESSION['username'];
    $log = $pdo->prepare("INSERT INTO modifications_log (user_id, admin, date_modification) VALUES (?, ?, NOW())");
    $log->execute([$id, $admin]);

    echo "<p>Données mises à jour.</p>";
}
?>

<h2>Modifier les informations</h2>
<form method="post">
    Nom: <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>"><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"><br>
    Rôle:
    <select name="role">
        <option value="adherent" <?= $user['role'] == 'adherent' ? 'selected' : '' ?>>Adhérent</option>
        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
    </select><br>
    <button type="submit">Enregistrer</button>
</form>
