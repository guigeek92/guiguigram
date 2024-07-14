<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Récupérer le nombre de notifications non lues
$currentUserId = $_SESSION['LOGGED_USER']['user_id'];
$countSql = $mysqlClient->prepare('SELECT COUNT(*) AS unread_count
FROM notif 
WHERE (type = "inscription" OR type = "publication" OR (type = "commentaire" AND user_id = :user_id))
  AND is_enabled = 1');
$countSql->execute(array(':user_id' => $currentUserId));
$countResult = $countSql->fetch();
$unreadCount = $countResult['unread_count'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styles des boutons */
        .navbar-nav .nav-link {
            padding: 8px 16px;
            margin-right: 10px;
            font-family:Arial, sans-serif;
        }
        .bg-violet {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(to bottom, #000000, #4B0082);
            z-index: 1000;
        }
        .button81 {
            background-color: #fff;
            border: 0 solid #e2e8f0;
            border-radius: 1.5rem;
            color: #0d172a;
            cursor: pointer;
            font-family: "Basier circle", -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1.1rem;
            font-weight: 600;
            line-height: 1;
            padding: 1rem 1.6rem;
            text-align: center;
            text-decoration: none #0d172a solid;
            transition: all .1s cubic-bezier(.4, 0, .2, 1);
            box-shadow: 0px 1px 2px rgba(166, 175, 195, 0.25);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }
        .button81 img {
            max-width: 20px;
            max-height: 20px;
        }
        .search-form {
            display: flex;
            align-items: center;
        }
        .search-form input[type="text"] {
            border-radius: 1.5rem;
            border: 0 solid #e2e8f0;
            padding: 0.5rem 1rem;
            margin-right: 10px;
            font-family: 'Comic Sans MS', 'Comic Sans', cursive;
        }
        .search-form button {
            background-color: #fff;
            border: 0 solid #e2e8f0;
            border-radius: 1.5rem;
            color: #0d172a;
            cursor: pointer;
            font-family: "Basier circle", -apple-system, system-ui, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            font-size: 1.1rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: all .1s cubic-bezier(.4, 0, .2, 1);
        }
        .color {
            color: white;
        }
        @media (max-width: 768px) {
            .navbar-brand img {
                display: none !important;
            }
            .navbar-nav .nav-link {
                padding: 8px 10px;
          
               margin: 5px auto; /* Centrage horizontal des boutons */
                max-width: 50%;
            }
            .search-form input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }
            .search-form button {
                width: 100%;
            }
        }
        .notification-btn {
        width: 20px; /* Largeur de l'image de notification */
        height: auto; /* Hauteur ajustée automatiquement */
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }
        .notification-indicator {
            position: relative;
            display: inline-block;
        }
        .notification-indicator .badge {
            position: relative;
            top: -5px;
            right: -5px;
            padding: 5px 10px;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-size: 12px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-violet">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="icon/iconee.png" width="150" height="auto" class="d-inline-block align-top rounded" alt="Logo">
        </a>
        <h2 class="ml-md-3 color" style="font-family: 'Comic Sans MS', 'Comic Sans', cursive;">G&nbsp;&nbsp;U&nbsp;&nbsp;I&nbsp;&nbsp;G&nbsp;&nbsp;U&nbsp;&nbsp;I&nbsp;&nbsp;&nbsp;G&nbsp;&nbsp;R&nbsp;&nbsp;A&nbsp;&nbsp;M</h2>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link button81" href="index.php">Accueil</a>
                </li>
                <?php if (isset($_SESSION['LOGGED_USER'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link button81" href="publication.php">Publier</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link button81" href="messagerie.php">Messagerie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link button81" href="chat.php">Chat</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link button81" href="profilinit.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link button81" href="logout.php">Déconnexion</a>
                    </li>
                    <li class="nav-item notification-indicator">
                        <a class="nav-link button81" href="notif.php">
                            <img src="notif.png" alt="Notifications">
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link button81" href="subscribe.php">S'inscrire</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <form class="search-form" method="GET" action="recherche_utilisateur.php">
                        <input type="text" name="search" placeholder="Rechercher un utilisateur">
                        <button type="submit" class="button81">Rechercher</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>







