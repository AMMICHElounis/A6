<?php
session_start();

// Vérifier si l'utilisateur est connecté et a le rôle 'adherent'
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'adherent') {
    header('Location: login.php');
    exit();
}

// Vérifier si l'ID de la personne à charge est fourni
if (isset($_POST['charge_id'])) {
    $id_charge = $_POST['charge_id'];

    // Connexion à la base de données
    require_once 'config.php';

    try {
        // Suppression de la personne à charge
        $stmt = $pdo->prepare("DELETE FROM charges WHERE id = ?");
        $stmt->execute([$id_charge]);

        // Redirection avec un message de succès
        header('Location: ../infos_personnelles.php');
        exit();
    } catch (PDOException $e) {
        // En cas d'erreur, redirection avec un message d'erreur
        header('Location: ../infos_personnelles.php');
        exit();
    }
} else {
    // Si l'ID est manquant, redirection avec un message d'erreur
    header('Location: ../infos_personnelles.php');
    exit();
}
