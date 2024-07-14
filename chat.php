<?php
session_start();

require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');


// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postData = $_POST;

    // Vérifier si le champ "message" n'est pas vide
    if (!empty($postData['message'])) {
        // Récupérer le message et l'ID de l'utilisateur
        $message = $postData['message'];
        $userId = getid($_SESSION['LOGGED_USER']['email'], $users);

        // Requête SQL pour insérer les données dans la base de données en utilisant une requête préparée
        $sql = 'INSERT INTO chat (user_id, message) VALUES (:user_id, :message)';
        $stmt = $mysqlClient->prepare($sql);

        // Exécuter la requête préparée en liant les valeurs aux marqueurs de paramètres
        $stmt->execute(array(
            ':user_id' => $userId,
            ':message' => $message,
        ));
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <style>
        .back {
            background-color: #F4CCCC;
            background-image: url('fond/n4.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            position: relative; /* Ajout d'une position relative pour le positionnement absolu du cadre */
        }
        #inscrip{
            margin-left: auto; /* Alignement à droite */
            margin-right: auto; /* Alignement à gauche */
            bottom: -50px;
            position:fixed;
            max-width: 800px; /* Largeur maximale du formulaire */
            margin-left: 400px;
        }
        .message-form {
            display: flex;
            align-items: center;
        }
        .message-input {
            flex: 1;
            margin-right: 10px; /* Espacement entre la zone de texte et l'icône */
        }
        .envo{
            border-radius: 10px;
            margin-top: 8px;
        }
        @media (max-width: 768px) {
            #inscrip{
                margin-left: 20%;
            }
            .envo{
                background-color: white;
            }
            .port {
                width: 90%; /* Ajustez la largeur selon vos besoins */
                margin: auto; /* Centrez le formulaire */
            }
            .message-form {
                justify-content: center; /* Pour centrer verticalement les éléments */
            }
            .message-input {
                margin-right: 0; /* Supprimer l'espacement entre la zone de texte et l'icône */
                margin-bottom: 10px; /* Ajouter une marge en bas */
            }
        }
        /* Cadre noir en arrière-plan */
        #message-container::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Couleur noire semi-transparente */
            z-index: -1; /* Pour placer en arrière-plan */
        }
    </style>
</head>
<script>
function scrollToBottom() {
        var messageContainer = document.querySelector('#message-container');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
    window.onload = scrollToBottom;
</script>
<body class="d-flex flex-column min-vh-100 back">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <div id="inscrip" class="p-5 rounded-3 shadow-lg port">
            <h1 class="text-center mb-4 font-fam"></h1>
            <form action="" method="POST" enctype="multipart/form-data" class="message-form">
                <div class="mb-3 message-input">
                    <label for="email" class="form-label font-fam"></label>
                    <input type="text" class="form-control" id="email" name="message" aria-describedby="email-help">
                </div>
                <button type="submit" class="envo">
                    <img src="send.png" alt="Envoyer" width="24" height="24">
                </button>
            </form>
        </div>
        <section id="message" class="mt-5" id="message-container">
            <script>
                // Définition de la fonction loadmessage pour charger les messages en temps réel
                function loadmessage() {
                    $('#message').load('post_chat.php');
                }

                setInterval(loadmessage, 500);
            </script>
        </section>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Ajout du script jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
</body>
</html>
