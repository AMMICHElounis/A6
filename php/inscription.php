<?php
// Activation du mode debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'config.php';

// Vérification de la présence des champs indispensables
$requiredFields = ['username', 'password', 'role', 'nom', 'prenom', 'email', 'date_naissance', 'genre', 'nombre_charges'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field])) {
        die("Erreur : le champ '$field' est manquant.");
    }
}

$username       = trim($_POST['username']);
$password_plain = trim($_POST['password']);
$role           = $_POST['role'];
$nom            = trim($_POST['nom']);
$prenom         = trim($_POST['prenom']);
$email          = trim($_POST['email']);
$date_naissance = $_POST['date_naissance'];
$genre          = $_POST['genre'];
$nombre_charges = (int) $_POST['nombre_charges'];
$telephone      = isset($_POST['telephone']) ? trim($_POST['telephone']) : '';

// Vérification que le username et l'email soient uniques
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
$stmt->execute([$username, $email]);
if ($stmt->fetch()) {
    die("Nom d'utilisateur ou email déjà utilisé.");
}

// Hachage du mot de passe
$password = password_hash($password_plain, PASSWORD_DEFAULT);

// Insertion dans la table users
$stmt = $pdo->prepare("INSERT INTO users (username, password, role, nom, prenom, email, date_naissance, genre, telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$username, $password, $role, $nom, $prenom, $email, $date_naissance, $genre, $telephone]);

$user_id = $pdo->lastInsertId();

// Insertion des personnes à charge, le cas échéant
if ($nombre_charges > 0) {
    $stmt_dep = $pdo->prepare("INSERT INTO charges (adherent_id, nom, prenom, date_naissance, genre) VALUES (?, ?, ?, ?, ?)");
    for ($i = 0; $i < $nombre_charges; $i++) {
        if (!isset($_POST["charge_nom_$i"], $_POST["charge_prenom_$i"], $_POST["charge_date_naissance_$i"], $_POST["charge_genre_$i"])) {
            die("Erreur : données manquantes pour la personne à charge numéro " . ($i + 1));
        }
        $dep_nom            = trim($_POST["charge_nom_$i"]);
        $dep_prenom         = trim($_POST["charge_prenom_$i"]);
        $dep_date_naissance = $_POST["charge_date_naissance_$i"];
        $dep_genre          = $_POST["charge_genre_$i"];

        $stmt_dep->execute([$user_id, $dep_nom, $dep_prenom, $dep_date_naissance, $dep_genre]);
    }
}

echo "Inscription réussie.";
?>
