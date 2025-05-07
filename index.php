<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accueil - Association Ait Moussa-Ou-Brahem</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!-- En-tête avec Hero -->
  <header>
    <div class="hero">
      <div class="hero-overlay">
        <h1 class="hero-title">Bienvenue à l'Association Ait Moussa-Ou-Brahem</h1>
        <p class="hero-subtitle">Au service des familles et du village dès le premier besoin</p>
        <div class="hero-buttons">
          <a href="adherent.html" class="btn">Espace Adhérent</a>
          <a href="admin.html" class="btn admin">Espace Administrateur</a>
        </div>
      </div>
    </div>
  </header>

  <!-- Contenu principal -->
  <main class="main-content container">
    <section class="comment-faire">
      <h2>Comment ça fonctionne ?</h2>
      <p>Découvrez comment payer votre cotisation et accéder à votre espace personnel en quelques étapes simples.</p>
      <a href="comment-faire.html" class="btn">Guide d'utilisation</a>
    </section>

    <section class="projects-section">
      <h2>Nos projets & évènements</h2>
      <div id="project-container" class="project-grid">
        <?php include "fetch_items.php"; ?>
      </div>
    </section>
  </main>

  <!-- Pied de page -->
  <footer>
    <?php include "includes/footer.html"; ?>
  </footer>

  <script src="js/main.js"></script>
</body>
</html>
