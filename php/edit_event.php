<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date_event = $_POST['date_event'];

    $stmt = $pdo->prepare("UPDATE events SET title = ?, description = ?, date_event = ? WHERE id = ?");
    $stmt->execute([$title, $description, $date_event, $id]);

    header("Location: ../admin_dashboard.html");
    exit();
}
?>