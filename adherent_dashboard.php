<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'adherent') {
    header('Location: php/login.php');
    exit();
}

require_once 'php/config.php';
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$adherent = $stmt->fetch();

if (!$adherent) {
    die("Adhérent introuvable.");
}

$charges_stmt = $pdo->prepare("SELECT * FROM charges WHERE adherent_id = ?");
$charges_stmt->execute([$adherent['id']]);
$charges = $charges_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - Adhérent</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include "includes/header.php"; ?>

  <main class="container">
    <div class="login-box">
      <h2>Bonjour, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
      <p>Bienvenue sur votre espace personnel.</p>

      <div class="login-buttons" style="margin-top: 20px; text-align:center;">
        <a href="infos_personnelles.php" class="btn">Mes Informations</a>
      </div>
    </div>
  </main>

  <?php include "includes/footer.html"; ?>
  <script src="js/main.js"></script>
</body>
</html>
