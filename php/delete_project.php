<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: ../admin_delete.php?message=Projet supprimé avec succès");
    exit();
}
?>