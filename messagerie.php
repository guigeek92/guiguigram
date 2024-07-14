<?php
session_start();
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: login.php');
    exit();
}

// Récupère l'ID de l'utilisateur connecté
$loggedInUserId = $_SESSION['LOGGED_USER']['user_id'];

// Requête SQL pour récupérer les dernières conversations
$sql = "
SELECT u.user_id, u.full_name, u.image, m.message_text, m.sent_at
FROM users u
JOIN (
    SELECT
        CASE
            WHEN sender_id = :user_id THEN recipient_id
            ELSE sender_id
        END AS conversation_user_id,
        MAX(sent_at) AS last_message_time
    FROM messages
    WHERE sender_id = :user_id OR recipient_id = :user_id
    GROUP BY conversation_user_id
) c ON u.user_id = c.conversation_user_id
JOIN messages m ON (
    (m.sender_id = :user_id AND m.recipient_id = u.user_id) OR
    (m.sender_id = u.user_id AND m.recipient_id = :user_id)
) AND m.sent_at = c.last_message_time
ORDER BY c.last_message_time DESC";

$stmt = $mysqlClient->prepare($sql);
$stmt->execute(['user_id' => $loggedInUserId]);
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
           .body,aff {
    overflow-x: hidden;
    overflow-y: hidden;
}
        body {
            overflow-x: hidden; /* Empêche le défilement horizontal */
            background: linear-gradient(to right, #ffffff, #add8e6);
        }
        .aff{
            margin-top: 150px;
            overflow-y: hidden ;
           
        }
        .conversation-container {
            overflow-y:auto
            margin: 20px auto;
            width: 80%;
            max-width: 800px;
        }
        .conversation {

            display: flex;
            align-items: center;
            border: 1px solid #cccccc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }
        .profile-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .conversation-info {
            flex-grow: 1;
        }
        .conversation-info h5 {
            margin: 0;
        }
        .conversation-info p {
            margin: 0;
            color: #666666;
        }
        .titr {
            background-color:blue;
           font-family:' Marker Felt, fantasy';
            margin-bottom: 100px;

        }
    </style>
</head>
<body>
<?php require_once(__DIR__ . '/header.php'); 
$image_folder = "images/"; ?>

<div class="aff">
<h1 class="titr">Vos conversations</h1>
    <div class="container conversation-container">

 
        <?php foreach ($conversations as $conversation): ?>
            <a href="conversations.php?user_id=<?php echo htmlspecialchars($conversation['user_id']); ?>" class="conversation-link">
                <div class="conversation">
                    <img src="<?php echo $image_folder.htmlspecialchars($conversation['image']); ?>" alt="Profile Image" class="profile-image">
                    <div class="conversation-info">
                        <h5><?php echo htmlspecialchars($conversation['full_name']); ?></h5>
                        <p><?php echo htmlspecialchars($conversation['message_text']); ?></p>
                        <small><?php echo htmlspecialchars($conversation['sent_at']); ?></small>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>



