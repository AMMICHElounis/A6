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

if ($adherent) {
    $adherent_info = $adherent;
    $charges_stmt = $pdo->prepare("SELECT * FROM charges WHERE adherent_id = ?");
    $charges_stmt->execute([$adherent['id']]);
    $charges = $charges_stmt->fetchAll();
    $nb_charges = count($charges); // ➕ Nombre de personnes à charge
} else {
    die("Adhérent introuvable.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Informations Personnelles</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header>
    <?php include "includes/header.php"; ?>
  </header>

  <main class="container">
    <h1>Mes Informations Personnelles</h1>

    <!-- Bouton Retour aligné à gauche -->
    <a href="adherent_dashboard.php" class="return-button">
      <button class="btn-primary">Retour</button>
    </a>

    <!-- Onglets -->
    <div class="tabs">
      <button class="btn-primary" onclick="showTab('infos-personnelles')">Informations Personnelles</button>
      <button class="btn-primary" onclick="showTab('modifier-infos')">Modifier Informations</button>
      <button class="btn-primary" onclick="showTab('personnes-charge')">Gérer Personnes à Charge</button>
    </div>

    <!-- Contenu Onglets -->
    <div id="infos-personnelles" class="tab-content" style="display: block;">
      <h2>Informations Personnelles</h2>
      <p><strong>Nom :</strong> <?php echo htmlspecialchars($adherent_info['nom']); ?></p>
      <p><strong>Prénom :</strong> <?php echo htmlspecialchars($adherent_info['prenom']); ?></p>
      <p><strong>Date de Naissance :</strong> <?php echo htmlspecialchars($adherent_info['date_naissance']); ?></p>
      <p><strong>Email :</strong> <?php echo htmlspecialchars($adherent_info['email']); ?></p>
      <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($adherent_info['telephone']); ?></p>
      <p><strong>Genre :</strong> <?php echo htmlspecialchars($adherent_info['genre']); ?></p>
      <p><strong>Nombre de personnes à charge :</strong> <?php echo $nb_charges; ?></p> <!-- ➕ Ajout ici -->

      <?php if (!empty($charges)) : ?>
        <h3>Personnes à Charge</h3>
        <ul>
          <?php foreach ($charges as $charge) : ?>
            <li>
              <?php echo htmlspecialchars($charge['prenom']) . ' ' . htmlspecialchars($charge['nom']); ?>
              — Né(e) le <?php echo htmlspecialchars($charge['date_naissance']); ?>
              — Genre : <?php echo htmlspecialchars($charge['genre']); ?>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else : ?>
        <p><em>Aucune personne à charge enregistrée.</em></p>
      <?php endif; ?>
    </div>

    <div id="modifier-infos" class="tab-content" style="display: none;">
      <h2>Modifier Informations</h2>
      <form action="modifier_informations.php" method="post">
        <label>Téléphone : <input type="text" name="telephone" value="<?php echo htmlspecialchars($adherent_info['telephone']); ?>"></label><br>
        <label>Email : <input type="email" name="email" value="<?php echo htmlspecialchars($adherent_info['email']); ?>"></label><br>
        <label>Genre :
          <select name="genre">
            <option value="Homme" <?php echo $adherent_info['genre'] === 'Homme' ? 'selected' : ''; ?>>Homme</option>
            <option value="Femme" <?php echo $adherent_info['genre'] === 'Femme' ? 'selected' : ''; ?>>Femme</option>
            <option value="Autre" <?php echo $adherent_info['genre'] === 'Autre' ? 'selected' : ''; ?>>Autre</option>
          </select>
        </label><br>
        <button type="submit" class="btn-primary">Mettre à jour</button>
      </form>
    </div>

    <div id="personnes-charge" class="tab-content" style="display: none;">
      <h2>Personnes à Charge</h2>
      <h3>Liste des personnes à charge :</h3>
      <ul>
        <?php foreach ($charges as $charge) : ?>
          <li>
            <?php echo htmlspecialchars($charge['nom']) . ' ' . htmlspecialchars($charge['prenom']); ?> (<?php echo htmlspecialchars($charge['genre']); ?>, <?php echo htmlspecialchars($charge['date_naissance']); ?>)
            <form action="php/supprimer_personne_charge.php" method="post" style="display:inline;">
              <input type="hidden" name="charge_id" value="<?php echo $charge['id']; ?>">
              <button type="submit" class="btn-danger">Supprimer</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>

      <h3>Ajouter une personne à charge :</h3>
      <form action="php/ajouter_personne_charge.php" method="post">
        <label>Nom : <input type="text" name="charge_nom" required></label><br>
        <label>Prénom : <input type="text" name="charge_prenom" required></label><br>
        <label>Date de Naissance : <input type="date" name="charge_date_naissance" required></label><br>
        <label>Genre :
          <select name="charge_genre" required>
            <option value="Homme">Homme</option>
            <option value="Femme">Femme</option>
            <option value="Autre">Autre</option>
          </select>
        </label><br>
        <input type="hidden" name="adherent_id" value="<?php echo $adherent_info['id']; ?>">
        <button type="submit" class="btn-primary">Ajouter</button>
      </form>
    </div>
  </main>

  <footer>
    <?php include "includes/footer.html"; ?>
  </footer>

  <script>
    function showTab(tabName) {
      var tabs = document.querySelectorAll('.tab-content');
      tabs.forEach(function(tab) {
        tab.style.display = 'none';
      });
      var tabToShow = document.getElementById(tabName);
      if (tabToShow) {
        tabToShow.style.display = 'block';
      }
    }
  </script>
</body>
</html>
