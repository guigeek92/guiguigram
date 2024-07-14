<!DOCTYPE html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$admin = 0;
// Inclure les fichiers nécessaires
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
?>
<html lang="fr">
<head>
    <script>
      if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
          console.log('Service Worker registered with scope:', registration.scope);
        }).catch(function(error) {
          console.log('Service Worker registration failed:', error);
        });
      }

      // Désactiver le défilement de la page
      function disableScroll() {
          document.body.style.overflow = 'hidden';
          document.documentElement.style.overflow = 'hidden';
      }

      // Activer le défilement uniquement dans le conteneur #publi
      function enableScroll() {
          document.body.style.overflow = '';
          document.documentElement.style.overflow = 'auto';
      }
    </script>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GUIGUIGRAM</title>
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="/icon/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comic+Neue&display=swap" rel="stylesheet">
    <style>

.body{
    overflow-x: hidden;
}
        .login {
            margin-top: 20px; /* ajustez la valeur selon la hauteur de votre barre de navigation */
            margin-left: 248px;
        }
.head{
    position: fixed;
    top: 0; /* Positionner le header au sommet de la fenêtre */
            left: 0; /* Aligner le header sur la gauche */
            width: 100%; /* Prendre toute la largeur de la fenêtre */
            z-index: 1000; /* Assurez-vous que le header est au-dessus des autres éléments */
}
        .publi {
      
            overflow-y: auto;
            overflow-x: hidden;
        }

        .back {
            background-color: #F4CCCC;
            background-image: url('fond/n5.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* Fixe seulement l'image de fond */
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100 back" >
<div class="head">
    <?php require_once(__DIR__ . '/header.php'); ?>
    </div>
    <div class="login">
        <?php require_once(__DIR__ . '/login.php'); ?>
    </div>

    <div class="publi">
        <?php require_once(__DIR__ . '/affichage_publication.php'); ?>

        </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
    
    <script>
 
    </script>
</body>
</html>




