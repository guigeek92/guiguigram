<!DOCTYPE html>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER'])) {
    require_once(__DIR__ . '/login.php'); 
    exit();
}

$query = "SELECT * FROM publication WHERE is_enabled = 0 ORDER BY publi_id DESC";

// Traitement du formulaire de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'], $_POST['publi_id'])) {
    $commentText = $_POST['comment_text'];
    $publiId = $_POST['publi_id'];
    $userId = getid($_SESSION['LOGGED_USER']['email'], $users);
    $notifsql = 'INSERT INTO notif (type, text, user_id) VALUES (:type, :text,:user_id)';
    $notifstmt = $mysqlClient->prepare($notifsql);
    $pseudo=getname2($userId,$users);
    $text2= $pseudo." vient de commenter ta publication!";
    $destinataire=getid2($publiId,$publi);
    $notifstmt->execute(array(
        'user_id' => $destinataire,
        'type' =>"commentaire",
        'text' => $text2,
    ));

    $sqlInsert = "INSERT INTO commentaire (user_id, publi_id, text) VALUES (:user_id, :publi_id, :text)";
    $stmtInsert = $mysqlClient->prepare($sqlInsert);
    $stmtInsert->execute([
        'user_id' => $userId,
        'publi_id' => $publiId,
        'text' => $commentText
    ]);
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            visibility: hidden;
        }
        body, .container {
            overflow-x: hidden;
        }
        .container {
            margin-top: 15%; /* Marge de 200px en haut */
            overflow-y: auto;
        }
        .btn-like{
  
        }

        .post-container {
            padding: 20px;
            border-radius: 10px;
           
            background-color: black;
        }
        .post-image {
            max-width: 100%;
            max-height: 600px;
            width: auto;
            height: auto;
            border-radius: 0px;
        }
        .post-media img, .post-media video {
            max-width: 100%;
            height: auto;
            display: block;
            margin-bottom: 10px;
        }
        .post-username {
            margin-top: 10px;
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .post-title {
            color: white;
            font-size: 1.5em;
            margin-bottom: 10px;
            margin-left: 10px;
            
        }
        .post-text {
            color: white;
            font-size: 1em;
            margin-bottom: 10px;
            margin-left: 10px;
        }
        .center {
            margin-top: 210px;
            text-align: center;
            color: white;
        }
        .like-container {
        
    display: flex; /* Utiliser Flexbox pour aligner les éléments horizontalement */
    justify-content: center; /* Aligner les éléments horizontalement au centre */
    align-items: center; /* Aligner les éléments verticalement au centre */
}
.like-count {
    margin-left: 10px;
    text-decoration: none;
    margin-right: 10px; /* Ajoute une marge de 10 pixels à droite du nombre de likes */
}
.comment-username{
    font-size: 12px;
    margin-left: 10px;
    text-decoration: none;
    color: black!important;
    font-weight: bold;
}
.comments{
    font-size: 12px;
    color:black;
    margin-left: 10px;
    border-radius: 5px;
    margin-top: -10px;
    margin-bottom: 30px;
   background-color: #636363;
}
.titrc{
    color:white;
}
.result-item {
            display: flex;
            align-items: center;
            padding: 0px;
       
            border-radius: 0px;
            margin-bottom: 10px;
            background-color: black;
           
            transition: background-color 0.3s ease;
        }
.profile-picture {
    margin-top: 10px;
    margin-left: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover; /* Recadrer l'image pour remplir le conteneur */
            margin-right: 15px;
        }

        .profile-name {
            margin-top: 10px;
            margin-left: 10px;
            font-size: 1.2em;
            font-weight: 600;
            color: white;
            text-decoration: none;
        }

.form-control{
}
.envo{
    margin-left: 5px;
}
.imglike{

}
.comment-text{
    margin-left: 20px;
}
@media (max-width: 768px) {
    .post-container {
        width: 100% !important;;
            padding: 0px;
            border-radius: 0px;
          
          
        }
}
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<script>
    window.addEventListener('beforeunload', function() {
        localStorage.setItem('scrollPosition', window.scrollY);
    });

    window.addEventListener('load', function() {
        var scrollPosition = localStorage.getItem('scrollPosition');
        if (scrollPosition !== null) {
            window.scrollTo(0, scrollPosition);
        }
        document.body.style.visibility = 'visible';
    });
</script>

<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>

    <h1 class="my-4 center">Publications</h1>

    <div class="row">
        <?php
        $connectedUserId = getid($_SESSION['LOGGED_USER']['email'], $users);
        try {
            $stmt = $mysqlClient->prepare($query);
            $stmt->execute();
            $image_folder = "images/";
            $position = 0; // Initialisez un compteur pour la position
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $username = getname2($row['user_id'], $users);
                $profilepath=getimage($row['user_id'], $users);
                $pseudo=getname2($row['user_id'], $users);
                echo "<div class='col-12 col-md-6 col-lg-4 mb-4'>";
                echo "<div class='post-container'>";
                echo "<li class='result-item'>";
                echo "<img src='" . htmlspecialchars($profilepath) . "' alt='Photo de profil' class='profile-picture'>";
                echo "<a href='profil_affichage.php?user_id=" . htmlspecialchars($row['user_id']) . "' class='profile-name'>";
                echo htmlspecialchars($pseudo);
                echo "</a>";
                echo "</li>";
          $image_folder = "images/";
                echo "<div class='post-title'>" . htmlspecialchars($row['name']) . "</div>";
                echo "<div class='post-text'>" . htmlspecialchars($row['text'], ENT_QUOTES, 'UTF-8') . "</div>";

                // Traitement des médias
                if (!empty($row['media_path'])) {
                    $mediaPath = 'publication/' . basename($row['media_path']);
                    if (file_exists($mediaPath)) {
                        $mediaType = mime_content_type($mediaPath);
                        echo "<div class='post-media'>";
                        if (strpos($mediaType, 'image/') === 0) {
                            echo "<img src='" . htmlspecialchars($mediaPath) . "' alt='Image' class='post-image'>";
                        } elseif (strpos($mediaType, 'video/') === 0) {
                            echo "<video controls class='post-image'>
                                    <source src='" . htmlspecialchars($mediaPath) . "' type='" . htmlspecialchars($mediaType) . "'>
                                    Votre navigateur ne supporte pas la balise vidéo.
                                  </video>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='post-media-error'>Le fichier média n'existe pas.</div>";
                    }
                }
                $userId = $row['user_id'];
                $publiId = $row['publi_id'];
                      // Vérifier si l'utilisateur connecté a liké cette publication
        $sqlCheckLike = "SELECT * FROM `like` WHERE `publi_id` = :publi_id AND `user_id` = :user_id";
        $stmtCheckLike = $mysqlClient->prepare($sqlCheckLike);
        $stmtCheckLike->execute(['publi_id' => $publiId, 'user_id' => $connectedUserId]);
        $existingLike = $stmtCheckLike->fetch(PDO::FETCH_ASSOC);

        // Compter le nombre de likes pour cette publication
        $sqlNbrLike = "SELECT COUNT(*) AS like_count FROM `like` WHERE publi_id = :publi_id";
        $stmtNbrLike = $mysqlClient->prepare($sqlNbrLike);
        $stmtNbrLike->execute(['publi_id' => $publiId]);
        $likeData = $stmtNbrLike->fetch(PDO::FETCH_ASSOC);
        $likenbr = $likeData['like_count'];

        echo "<div class='like-conteneur'>";
        echo "<a href='liste_like.php?publi_id=" . htmlspecialchars($publiId) . "' class='like-count'>Il y a " . $likenbr . " like(s)</a>";
        if ($existingLike) {
            echo "<a href='dislike.php?publi_id=" . htmlspecialchars($publiId) . "' class='btn-like '><img class='imglike' src='like.png' width='auto' height='30'></a>";
        } else {
            echo "<a href='like.php?publi_id=" . htmlspecialchars($publiId) . "' class='btn-like '><img class='imglike' src='likevide3.png' width='40' height='auto'></a>";
        }
        echo "</div>";

                // Traitement des commentaires
                $sql2 = "SELECT * FROM commentaire WHERE publi_id = :publi_id ORDER BY commentaire_id DESC";
                $stmt2 = $mysqlClient->prepare($sql2);
                $stmt2->execute(['publi_id' => $publiId]);
                echo "  <p class='titrc' style='margin-left:10px',>Commentaire(s):</p>";
                echo "<div class='comments'>";
                while ($comment = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                    $commentUsername = getname2($comment['user_id'], $users);
                    echo "<div class='comment-username'>
                            <a href='profil_affichage.php?user_id=" . htmlspecialchars($comment['user_id']) . "' class='profile-name'>" . htmlspecialchars($commentUsername) . "</a>
                          </div>";
                    if (!empty($comment['text'])) {
                        echo "<div class='comment-text'>" . htmlspecialchars($comment['text'], ENT_QUOTES, 'UTF-8') . "</div>";
                    }
                }
                echo "</div>"; // Fin des commentaires

                // Formulaire de commentaire
                echo "<div class='comment-form'>";
                echo "<form method='POST' action=''>";
                echo "<input type='hidden' name='publi_id' value='" . htmlspecialchars($publiId) . "'>";
                echo "<textarea name='comment_text' class='form-control' rows='' placeholder='Écrire un commentaire...'></textarea>";
                echo "<button type='submit' class='btn btn-primary mt-2 envo'>Commenter</button>";
                echo "</form>";
                echo "</div>"; // Fin du formulaire de commentaire

                echo "</div>"; // Fin du post-container
                echo "</div>"; // Fin de la colonne
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
        }
        ?>
    </div>
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js" crossorigin="anonymous"></script>

<script>
    // Initialise Twemoji
    twemoji.parse(document.body);
</script>
</body>
</html>









