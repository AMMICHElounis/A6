<?php
// config.php – Configuration de la connexion à la base de données
$host       = 'localhost';
$dbname     = 'mon_site';
$usernameDB = 'root';
$passwordDB = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $usernameDB,
        $passwordDB,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
    // Vous pouvez afficher ou commenter ce message de debug en développement
    // echo "Connexion réussie à la base de données.<br>";
} catch (PDOException $e) {
    error_log("Erreur de connexion à la base de données : " . $e->getMessage());
    die("Erreur de connexion. Veuillez réessayer plus tard.");
}
?>
