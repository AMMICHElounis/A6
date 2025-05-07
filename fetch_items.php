<?php
$pdo = new PDO('mysql:host=localhost;dbname=mon_site;charset=utf8', 'root', '');

function getItems($table) {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY created_at DESC LIMIT 6");
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($items as $item) {
        $id = $item['id'];
        $title = htmlspecialchars($item['title']);
        $description = htmlspecialchars(mb_strimwidth($item['description'], 0, 100, "..."));
        $date = isset($item['date_event']) ? date('d/m/Y', strtotime($item['date_event'])) : '';

        // Encodage de l'image blob en base64
        $imgSrc = 'img/placeholder.png'; // Par défaut
        if (!empty($item['image'])) {
            $imgBase64 = base64_encode($item['image']);
            $imgSrc = 'data:image/jpeg;base64,' . $imgBase64;
        }

        $type = $table === 'projects' ? 'project' : 'event';

        echo "
        <a href='details.php?type=$type&id=$id' class='project-card'>
            <img src='$imgSrc' alt='$title'>
            <div class='card-body'>
                <h3>$title</h3>";
        if ($date) echo "<p class='date'>$date</p>";
        echo "<p>$description</p>
            </div>
        </a>";
    }
}

getItems("projects");
getItems("events");
?>
