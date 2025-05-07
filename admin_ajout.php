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
  <title>Ajouter Projet / Événement</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include "includes/header.php"; ?>

  <main class="container" style="position: relative;">

    <?php if (isset($_GET['message'])): ?>
      <div class="alert success">
        <?= htmlspecialchars($_GET['message']) ?>
      </div>
    <?php endif; ?>

    <section class="tab-content">
      <h2>Ajouter un Projet</h2>
      <form class="styled-form" action="php/add_project.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="title">Titre du projet</label>
          <input type="text" name="title" id="title" required>
        </div>
        <div class="form-group">
          <label for="description">Description</label>
          <textarea name="description" id="description" required></textarea>
        </div>
        <div class="form-group">
          <label for="image">Image</label>
          <input type="file" name="image" id="image" accept="image/*">
        </div>
        <button type="submit" class="btn-primary">Ajouter le projet</button>
      </form>
    </section>

    <section class="tab-content">
      <h2>Ajouter un Événement</h2>
      <form class="styled-form" action="php/add_event.php" method="POST">
        <div class="form-group">
          <label for="title_event">Titre de l'événement</label>
          <input type="text" name="title" id="title_event" required>
        </div>
        <div class="form-group">
          <label for="desc_event">Description</label>
          <textarea name="description" id="desc_event" required></textarea>
        </div>
        <div class="form-group">
          <label for="date_event">Date</label>
          <input type="date" name="date_event" id="date_event" required>
        </div>
        <button type="submit" class="btn-primary">Ajouter l'événement</button>
      </form>
    </section>
    
    <p class="return-button"><a href="admin_dashboard.php" class="btn-primary">← Retour au tableau de bord</a></p>

  </main>
</body>
</html>
