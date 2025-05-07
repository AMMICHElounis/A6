<?php
include 'php/fonctions.php';
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle 'adherent'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'adherent') {
    header('Location: php/login.php');
    exit();
}

// Connexion à la base de données
require_once 'php/config.php';

// Récupérer le nom d'utilisateur de la session
$username = $_SESSION['username'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$adherent = $stmt->fetch();

if (!$adherent) {
    die("Utilisateur introuvable.");
}

// Traitement du formulaire de mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telephone = $_POST['telephone'] ?? $adherent['telephone'];
    $email = $_POST['email'] ?? $adherent['email'];
    $genre = $_POST['genre'] ?? $adherent['genre'];

    // Mise à jour des informations
    $update_stmt = $pdo->prepare("UPDATE users SET telephone = ?, email = ?, genre = ? WHERE id = ?");
    $update_stmt->execute([$telephone, $email, $genre, $adherent['id']]);

    // Redirection après mise à jour
    header('Location: infos_personnelles.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier Informations</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <?php include "includes/header.php"; ?>
  </header>

  <main class="container">
    <h1>Modifier Mes Informations</h1>

    <form action="modifier_informations.php" method="POST">
      <label>Téléphone : 
        <input type="text" name="telephone" value="<?php echo htmlspecialchars($adherent['telephone']); ?>" required>
      </label><br>

      <label>Email : 
        <input type="email" name="email" value="<?php echo htmlspecialchars($adherent['email']); ?>" required>
      </label><br>

      <label>Genre : 
        <select name="genre" required>
          <option value="Homme" <?php echo $adherent['genre'] === 'Homme' ? 'selected' : ''; ?>>Homme</option>
          <option value="Femme" <?php echo $adherent['genre'] === 'Femme' ? 'selected' : ''; ?>>Femme</option>
          <option value="Autre" <?php echo $adherent['genre'] === 'Autre' ? 'selected' : ''; ?>>Autre</option>
        </select>
      </label><br>

      <button type="submit">Mettre à jour</button>
    </form>
  </main>
</body>
</html>
