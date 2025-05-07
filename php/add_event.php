<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $date_event = $_POST['date_event'] ?? date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO events (title, description, date_event) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $date_event]);

    header("Location: ../admin_ajout.php?message=Evénement ajouté avec succès");
    exit();
}
?>