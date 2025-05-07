<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=mon_site;charset=utf8', 'root', '');

// Récupération et validation des paramètres
$type = $_GET['type'] ?? '';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Liste blanche des types autorisés
$allowedTables = ['project' => 'projects', 'event' => 'events'];

if (!$id || !isset($allowedTables[$type])) {
    echo "Paramètres invalides.";
    exit;
}

$table = $allowedTables[$type];

// Requête sécurisée
$stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "Élément introuvable.";
    exit;
}

// Sécurisation des données texte
$title = htmlspecialchars($item['title']);
$description = nl2br(htmlspecialchars($item['description']));
$date = isset($item['date_event']) ? date('d/m/Y', strtotime($item['date_event'])) : '';

// Traitement de l'image stockée en BLOB
$image = 'img/placeholder.png'; // Par défaut
if (!empty($item['image'])) {
    $imgBase64 = base64_encode($item['image']);
    $image = 'data:image/jpeg;base64,' . $imgBase64;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <main class="container">
    <h1><?php echo $title; ?></h1>
    <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="detail-image">
    <?php if ($date): ?>
      <p><strong>Date :</strong> <?php echo $date; ?></p>
    <?php endif; ?>
    <p><?php echo $description; ?></p>
    <a href="index.php" class="btn">Retour</a>
  </main>
</body>
</html>
