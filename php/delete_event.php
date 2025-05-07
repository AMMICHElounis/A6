<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../admin_delete.php?message=Événement supprimé avec succès");
    exit();
}
?>