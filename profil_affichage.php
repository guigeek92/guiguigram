<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Vérifiez d'abord si le profil à afficher est spécifié dans l'URL
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Requête SQL pour récupérer les données du profil spécifié
    $sql = "SELECT * FROM users WHERE user_id = :user_id";
    $sql2 = "SELECT * FROM publication WHERE user_id = :user_id AND is_enabled = 0";
    $stmt = $mysqlClient->prepare($sql);
    $stmt2 = $mysqlClient->prepare($sql2);
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt2->execute(['user_id' => $userId]);
    $isSubscribed = false;
    if (isset($_SESSION['LOGGED_USER'])) {
        $loggedInUserId = getid($_SESSION['LOGGED_USER']['email'], $users);
        $query = "SELECT * FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_to_id = :subscribed_to_id";
        $stmt3 = $mysqlClient->prepare($query);
        $stmt3->execute(['subscriber_id' => $loggedInUserId, 'subscribed_to_id' => $userId]);
        $isSubscribed = $stmt3->rowCount() > 0;
    }
} else {
    $user = false; // Définissez l'utilisateur sur false s'il n'est pas spécifié dans l'URL
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage du Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Vos styles CSS */
        .user-publications {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .post-container {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 20px);
            box-sizing: border-box;
        }

        .profil {
            margin-top: 180px;
        }

        .back {
            background-color: #F4CCCC;
        }

        .profile-container {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
            margin-bottom: 20px;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-name-container {
            display: flex;
            align-items: center;
        }

        .profile-name {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 0;
            margin-left: 20px;
        }

        .profile-bio {
            font-size: 1em;
            color: #666;
            margin-top: 10px;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-contact, .btn-subscribe {
            margin-right: 10px;
        }

        .post-container {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .post-username {
            font-weight: bold;
            color: #333;
        }

        .post-title {
            font-size: 1.2em;
            margin-top: 10px;
            color: #555;
        }

        .post-text {
            margin-top: 10px;
            color: #666;
        }

        .post-media {
            margin-top: 15px;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }
        @media (max-width: 768px) {
.profil{
    margin-top:150px;
}
            
        }
    </style>
</head>
<body class="back">
    <!-- Inclusion du header -->
    <?php require_once(__DIR__ . '/header.php'); 
    $image_folder = "images/"; ?>
    <div class="container profil">
        <?php if ($user): ?>
            <div class="profile-container">
                <?php
                $imagePath = !empty($user['image']) ? $image_folder . $user['image'] : 'default_image_path.jpg'; // Ajouter un chemin par défaut si l'image est vide
                ?>
                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Photo de profil" class="profile-picture">
                <div class="profile-info">
                    <div class="profile-name-container">
                        <h1 class="profile-name"><?php echo htmlspecialchars($user['pseudo']); ?></h1>
                    </div>
                    <p class="profile-bio"><?php echo htmlspecialchars($user['bio']); ?></p>
                </div>
                <div class="btn-container">
                    <a href="conversations.php?user_id=<?php echo $userId; ?>" class="btn btn-secondary btn-subscribe">Contacter</a>
                    <!-- Affichage du bouton S'abonner ou Se désabonner en fonction de l'état d'abonnement -->
                    <?php if ($isSubscribed): ?>
                        <a href="desabonnement.php?user_id=<?php echo $userId; ?>" class="btn btn-secondary btn-subscribe">Se désabonner</a>
                    <?php else: ?>
                        <a href="abonnement.php?user_id=<?php echo $userId; ?>" class="btn btn-secondary btn-subscribe">S'abonner</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Affichage des publications de l'utilisateur -->
            <div class="user-publications">
                <?php if ($isSubscribed): ?>
                    <?php while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="post-container col-12 col-md-6 col-lg-4">
                            <div class="post-username"><?php echo htmlspecialchars($user['pseudo']); ?></div>
                            <div class="post-title"><?php echo htmlspecialchars($row['name']); ?></div>
                            <div class="post-text"><?php echo htmlspecialchars($row['text']); ?></div>
                          
                            <?php if (!empty($row['media_path'])): ?>
                                <div class="post-media">
                                    <?php
                                    $mediaPath = 'publication/' . basename($row['media_path']);
                                    
                                    $mediaPath = 'publication/' . basename($row['media_path']);
                                    
                                    // Vérifiez si le fichier existe sur le serveur
                                    if (file_exists($mediaPath)) {
                                        $mediaType = mime_content_type($mediaPath);
                                    
                                        if (strpos($mediaType, 'image/') === 0): ?>
                                            <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="Image" class="post-image">
                                        <?php elseif (strpos($mediaType, 'video/') === 0): ?>
                                            <video controls class="post-image">
                                                <source src="<?php echo htmlspecialchars($mediaPath); ?>" type="<?php echo htmlspecialchars($mediaType); ?>">
                                                Votre navigateur ne supporte pas la balise vidéo.
                                            </video>
                                        <?php endif;
                                    } else {
                                        echo "Le fichier média n'existe pas.";
                                    }
                                    ?>
                                    
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>Profil non trouvé.</p>
        <?php endif; ?>
    </div>

    <!-- Inclusion du footer -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


