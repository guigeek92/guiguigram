<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Vérifiez si une publication doit être supprimée
if (isset($_POST['delete_post_id'])) {
    $deletePostId = $_POST['delete_post_id'];
    $deleteSql = "DELETE FROM publication WHERE user_id = :user_id AND publi_id = :publi_id";
    $deleteStmt = $mysqlClient->prepare($deleteSql);
    $deleteStmt->execute(['publi_id' => $deletePostId, 'user_id' => $_SESSION['LOGGED_USER']['user_id']]);
}

// Vérifiez si une publication doit être archivée
if (isset($_POST['archive_post_id'])) {
    $archivePostId = $_POST['archive_post_id'];
    $archiveSql = "UPDATE publication SET is_enabled = 1 WHERE user_id = :user_id AND publi_id = :publi_id";
    $archiveStmt = $mysqlClient->prepare($archiveSql);
    $archiveStmt->execute(['publi_id' => $archivePostId, 'user_id' => $_SESSION['LOGGED_USER']['user_id']]);
}
if (isset($_POST['rearchive_post_id'])) {
    $archivePostId = $_POST['rearchive_post_id'];
    $archiveSql = "UPDATE publication SET is_enabled = 0 WHERE user_id = :user_id ";
    $archiveStmt = $mysqlClient->prepare($archiveSql);
    $archiveStmt->execute(['user_id' => $_SESSION['LOGGED_USER']['user_id']]);
}

$userId = $_SESSION['LOGGED_USER']['user_id'];
$sql2 = "SELECT * FROM publication WHERE user_id = :user_id AND is_enabled = 0";
$stmt2 = $mysqlClient->prepare($sql2);
$stmt2->execute(['user_id' => $userId]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affichage du Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
            width: 100%;
            max-width: 350px;
            box-sizing: border-box;
        }
        .profil {
            margin-top: 150px;
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
            flex-direction: column;
            align-items: center;
        }
        @media (min-width: 768px) {
            .profile-container {
                flex-direction: row;
            }
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        @media (min-width: 768px) {
            .profile-picture {
                margin-right: 20px;
                margin-bottom: 0;
            }
        }
        .profile-info {
            flex-grow: 1;
            text-align: center;
        }
        @media (min-width: 768px) {
            .profile-info {
                text-align: left;
            }
        }
        .profile-name-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @media (min-width: 768px) {
            .profile-name-container {
                justify-content: flex-start;
            }
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
    </style>
</head>
<body class="back">
    <!-- Inclusion du header -->
    <?php require_once(__DIR__ . '/header.php'); 
    $image_folder = "images/"; ?>
    <div class="container profil">
        <div class="profile-container">
            <?php
            $email = $_SESSION['LOGGED_USER']['email'];
            $pseudo = getpseudo($email, $users);
            $bio = getbio($email, $users);
            $img = getProfileImage($email, $users);
            ?>
            <img src="<?php echo htmlspecialchars($img); ?>" alt="Photo de profil" class="profile-picture">
            <div class="profile-info">
                <div class="profile-name-container">
                    <h1 class="profile-name"><?php echo htmlspecialchars($pseudo); ?></h1>
                </div>
                <p class="profile-bio"><?php echo htmlspecialchars($bio); ?></p>
            </div>
            <div class="btn-container">
                <a href="profil.php?user_id=<?php echo $userId; ?>" class="btn btn-secondary btn-subscribe">Modifier profil</a>
            </div>
            <form method="post" action="">
                <input type="hidden" name="rearchive_post_id" value=0>
                <button type="submit" class="btn btn-container">Activer les Archives</button>
            </form>
        </div>

        <!-- Affichage des publications de l'utilisateur -->
        <div class="user-publications">
            <?php while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="post-container">
                    <div class="post-username"><?php echo htmlspecialchars($pseudo); ?></div>
                    <div class="post-title"><?php echo htmlspecialchars($row['name']); ?></div>
                    <div class="post-text"><?php echo htmlspecialchars($row['text']); ?></div>
                    <?php if (!empty($row['media_path'])): ?>
                        <div class="post-media">
                            <?php
                            $mediaPath = 'publication/' . basename($row['media_path']);
                            $mediaType = mime_content_type('publication/' .$row['media_path']);
                            if (strpos($mediaType, 'image/') === 0): ?>
                                <img src="<?php echo htmlspecialchars($mediaPath); ?>" alt="Image" class="post-image">
                            <?php elseif (strpos($mediaType, 'video/') === 0): ?>
                                <video controls class="post-image">
                                    <source src="<?php echo htmlspecialchars($mediaPath); ?>" type="<?php echo htmlspecialchars($mediaType); ?>">
                                    Votre navigateur ne supporte pas la balise vidéo.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <!-- Boutons Supprimer et Archiver -->
                    <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette publication ?');">
                        <input type="hidden" name="delete_post_id" value="<?php echo htmlspecialchars($row['publi_id']); ?>">
                        <button type="submit" class="btn-container">Supprimer</button>
                    </form>
                    <form method="post" action="" onsubmit="return confirm('Êtes-vous sûr de vouloir archiver cette publication ?');">
                        <input type="hidden" name="archive_post_id" value="<?php echo htmlspecialchars($row['publi_id']); ?>">
                        <button type="submit" class="btn-container">Archiver</button>
                    </form>
                </div>
            <?php endwhile; ?>
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
