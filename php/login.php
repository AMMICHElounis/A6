<?php
session_start();
require_once 'config.php'; // Inclusion de la connexion PDO

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et nettoyage des données du formulaire
    $login_input = trim($_POST['login'] ?? '');
    $password    = trim($_POST['password'] ?? '');
    $user_type   = $_POST['user_type'] ?? '';

    if (empty($login_input) || empty($password)) {
        die("Identifiant et mot de passe requis.");
    }

    // Requête SQL pour rechercher l'utilisateur par username OU par email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) LIMIT 1");
    $stmt->execute([$login_input, $login_input]);
    $user = $stmt->fetch();

    // Vérifie l'existence de l'utilisateur et la validité du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Contrôle du rôle de l'utilisateur
        if ($user_type === 'admin') {
            if ($user['role'] !== 'admin') {
                die("Ce compte n'est pas autorisé à se connecter en tant qu'administrateur.");
            }
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: ../admin_dashboard.php');
            exit();
        } elseif ($user_type === 'adherent') {
            if ($user['role'] !== 'adherent') {
                die("Ce compte n'est pas autorisé à se connecter en tant qu'adhérent.");
            }
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];
            header('Location: ../adherent_dashboard.php');
            exit();
        } else {
            die("Type d'utilisateur inconnu.");
        }
    } else {
        echo "Identifiants incorrects.";
    }
} else {
    echo "Méthode non autorisée.";
}
?>
