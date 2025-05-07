<?php
session_start();
require_once 'php/config.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: php/login.php');
    exit();
}

$projets = $pdo->query("SELECT * FROM projects ORDER BY created_at DESC")->fetchAll();
$events = $pdo->query("SELECT * FROM events ORDER BY date_event DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Supprimer Projet / Événement</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include "includes/header.php"; ?>
  <main class="container">

    <?php if (isset($_GET['message'])): ?>
      <div class="alert success">
        <?= htmlspecialchars($_GET['message']) ?>
      </div>
    <?php endif; ?>

    <section class="tab-content">
      <h2>Projets existants</h2>
      <?php if (count($projets) === 0): ?>
        <p>Aucun projet trouvé.</p>
      <?php else: ?>
        <?php foreach ($projets as $projet): ?>
          <div class="admin-row">
            <span><?= htmlspecialchars($projet['title']) ?></span>
            <a href="php/delete_project.php?id=<?= $projet['id'] ?>" class="btn-danger" onclick="return confirm('Supprimer ce projet ?');">Supprimer</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <section class="tab-content">
      <h2>Événements existants</h2>
      <?php if (count($events) === 0): ?>
        <p>Aucun événement trouvé.</p>
      <?php else: ?>
        <?php foreach ($events as $event): ?>
          <div class="admin-row">
            <span><?= htmlspecialchars($event['title']) ?> – <?= htmlspecialchars($event['date_event']) ?></span>
            <a href="php/delete_event.php?id=<?= $event['id'] ?>" class="btn-danger" onclick="return confirm('Supprimer cet événement ?');">Supprimer</a>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

  <p class="return-button"><a href="admin_dashboard.php" class="btn-primary">← Retour au tableau de bord</a></p>

  </main>
</body>
</html>
