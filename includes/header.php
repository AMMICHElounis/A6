<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header>
  <div class="container header-container">
    <div class="logo-section">
      <a href="index.html"><img src="images/logo.png" alt="Logo de l'association" class="logo"></a>
    </div>

    <nav class="nav-menu">
      <ul>
        <li><a href="index.html" class="btn">Accueil</a></li>
        <li><a href="comment-faire.html" class="btn">Comment faire ?</a></li>
      </ul>
    </nav>

    <?php if (isset($_SESSION['username'])): ?>
      <div class="user-info">
        <p>Connecté en tant que <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
        <a href="php/logout.php" class="btn">Déconnexion</a>
      </div>
    <?php endif; ?>
  </div>
</header>
