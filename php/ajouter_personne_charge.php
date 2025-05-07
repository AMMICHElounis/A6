<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle 'adherent'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'adherent') {
    header('Location: login.php');
    exit();
}

// Vérifier si les informations nécessaires sont fournies
if (isset($_POST['charge_nom'], $_POST['charge_prenom'], $_POST['charge_date_naissance'], $_POST['charge_genre'], $_POST['adherent_id'])) {
    $charge_nom = $_POST['charge_nom'];
    $charge_prenom = $_POST['charge_prenom'];
    $charge_date_naissance = $_POST['charge_date_naissance'];
    $charge_genre = $_POST['charge_genre'];
    $adherent_id = $_POST['adherent_id'];

    // Connexion à la base de données
    require_once 'config.php';

    try {
        // Insertion d'une nouvelle personne à charge
        $stmt = $pdo->prepare("INSERT INTO charges (nom, prenom, date_naissance, genre, adherent_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$charge_nom, $charge_prenom, $charge_date_naissance, $charge_genre, $adherent_id]);

        // Redirection avec un message de succès
        header('Location: ../infos_personnelles.php');
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, redirection avec un message d'erreur
        header('Location: ../infos_personnelles.php');
        exit();
    }
} else {
    // Si des informations sont manquantes, redirection avec un message d'erreur
    header('Location: ../infos_personnelles.php');
    exit();
}
