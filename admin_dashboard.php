<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: php/login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Administrateur</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <?php include "includes/header.php"; ?>

  <main class="container">
    <div class="login-box">
      <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> (Admin)</h2>
      <p>Gérez les projets, événements et adhérents :</p>

      <div class="login-buttons" style="margin-top: 20px; text-align:center;">
        <a href="admin_ajout.php" class="btn">Ajouter Projet / Événement</a>
        <a href="admin_delete.php" class="btn">Supprimer Projet / Événement</a>
        <a href="adherents_list.php" class="btn">Liste des adhérents</a>
      </div>
    </div>
  </main>

  <?php include "includes/footer.html"; ?>
  <script src="js/main.js"></script>
</body>
</html>
