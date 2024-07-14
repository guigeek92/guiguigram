<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Récupération des notifications
$currentUserId = $_SESSION['LOGGED_USER']['user_id'];
$notifsql = $mysqlClient->prepare('
SELECT * FROM notif 
WHERE type = "inscription" OR type = "publication" 
   OR (type = "commentaire" AND user_id = :user_id) 
ORDER BY created_at DESC
');
$notifsql->execute(array(':user_id' => $currentUserId));
$notif = $notifsql->fetchAll();

// Marquer une notification comme lue via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $notificationId = intval($_POST['id']);
    
    $updateSql = $mysqlClient->prepare('UPDATE notif SET is_enabled = 0 WHERE notif_id = :notif_id');
    $updateSql->execute(array(':notif_id' => $notificationId));
    
    // Retourner une réponse JSON
    echo json_encode(['success' => true]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage des Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .body,.container{
            overflow-y: hidden;
        }
        .back{
            background-image: url('fond/n6.jpg');
        }
        .defil{
            overflow-y: auto;
        }
        .container{
            margin-top: 200px;
        }
        .inscription-text {
            color: blue;
        }
        .publication-text {
            color: green;
        }
        .commentaire-text {
            color: red;
        }
        .highlight {
            background-color: yellow;
        }
    </style>
    <script>
        function updateNotificationStatus(notificationId) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'notif.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        var notificationElement = document.getElementById('notification-' + notificationId);
                        notificationElement.classList.remove('highlight');
                    }
                }
            };
            xhr.send('id=' + notificationId);
        }
    </script>
</head>
<body class="back">
    <!-- Inclusion du header -->
    <?php require_once(__DIR__ . '/header.php'); ?>
    
    <div class="container">
        <h1 style="color:white">Notifications</h1>
        <div class="defil">
        <ul class="list-group">
            <?php foreach ($notif as $notification): ?>
                <?php
                    $notificationType = strtolower($notification['type']);
                    $cssClass = $notificationType . "-text";
                    $highlightClass = $notification['is_enabled'] == 1 ? 'highlight' : '';
                ?>
                <li class="list-group-item <?= $highlightClass ?>" id="notification-<?= $notification['notif_id'] ?>">
                    <span class="<?= $cssClass ?>"><?= htmlspecialchars($notification['text']) ?></span>
                    <?php if ($notification['is_enabled'] == 1): ?>
                        <button class="btn btn-sm btn-primary float-right" onclick="updateNotificationStatus(<?= $notification['notif_id'] ?>)">Marquer comme lu</button>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        </div>
    </div>

    <!-- Inclusion du footer -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

