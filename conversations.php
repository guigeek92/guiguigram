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

// Récupère l'ID de l'utilisateur avec qui démarrer la conversation
if (!isset($_GET['user_id'])) {
    header('Location: select_user.php'); // Redirige si l'ID de l'utilisateur n'est pas fourni
    exit();
}
$conversationUserId = $_GET['user_id'];

// Requête SQL pour récupérer les informations de l'utilisateur avec qui démarrer la conversation
$userStmt = $mysqlClient->prepare("SELECT * FROM users WHERE user_id = :user_id");
$userStmt->execute(['user_id' => $conversationUserId]);
$conversationUser = $userStmt->fetch(PDO::FETCH_ASSOC);

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['content'])) {
    $content = $_POST['content'];

    // Insérez le message dans la base de données
    $insertStmt = $mysqlClient->prepare("INSERT INTO messages (sender_id, recipient_id, message_text, sent_at) VALUES (:sender_id, :recipient_id, :message_text, NOW())");
    $insertStmt->execute(['sender_id' => $loggedInUserId, 'recipient_id' => $conversationUserId, 'message_text' => $content]);

    // Rediriger pour éviter la soumission multiple du formulaire
    header("Location: conversations.php?user_id=$conversationUserId");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation privée avec <?php echo htmlspecialchars($conversationUser['full_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles pour les bulles de message */
         /* Styles pour les bulles de message */
         body, .container, .sidebar, .formu {
    overflow-x: hidden;
    overflow-y: hidden;
}
        .message-container {
            margin-bottom: 10px;
            display: flex;
            flex-direction: row;
            align-items: baseline;
            overflow-x: hidden;
        }
        .my-message {
            background-color: #007bff;
            color: white;
            border-radius: 10px;
            padding: 10px;
            margin-left: auto;
            max-width: 70%;
            overflow-x: hidden;
            word-wrap: break-word;
        }
        .other-message {
            margin-left: 10px;
            background-color: #f0f0f0;
            color: #333333;
            border-radius: 10px;
            padding: 10px;
            margin-right: auto;
            max-width: 70%;
            word-wrap: break-word;
        }
        .conversation-box {
            position: relative;
            border: 1px solid #cccccc;
            border-radius: 10px;
            padding: 20px;
            background-color: #000000;
            width: 100%;
            color: white;
            max-height: calc(95vh - 320px);
            margin-bottom: 200px;
            overflow-x: hidden;
            overflow-y: auto;
        }
           .profile-image {
            position: relative;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
    margin-top: 0px;
    align-self: baseline; /* Alignez l'image de profil sur la ligne de base de son conteneur */
}
        .place {
            margin-top: 200px; /* Vous pouvez ajuster cette valeur selon vos besoins */
        
        }
        body {
            background-image: url('fond/n2.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .sidebar {
            position: fixed;
            width: 20%;
            top: 200px;
            left: 0;
            bottom: 0;
            padding: 20px;
            background-color: black;
            border-radius: 10px;
            color: white;
        }
        .formu {
            position: fixed;
            bottom: 15%;
            width: calc(100% - 20%);
            height: 150px;
            border: 1px solid #cccccc;
            display: flex;
            align-items: center;
            border-radius: 10px;
            padding: 20px;
            background-color: #000000;
            color: white;
            overflow-y: auto;
         
           
         
        }
       
    
        .cadre {
    margin-left: 20%; /* Utilisation de % pour une mise en page responsive */
    width: 100%; /* Utilisation de % pour une largeur responsive */
    max-width: 1000px; /* Ajout d'une largeur maximale pour éviter un agrandissement excessif */
    height: 60vh; /* Utilisation de vh (viewport height) pour une hauteur responsive */
    background-color: black;
    border-radius: 20px; /* Ajout de bordures arrondies */
}

.d-flex {
    display: flex;
    align-items: center;
    margin-bottom: 10px; /* Espacement entre les messages */
}
        .nav-link.bout {
            display: block;
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .nav-link.bout:hover {
            background-color: #0056b3;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .nav-link.bout:active {
            background-color: #004085;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .envo{ 
            border-radius: 10px;
            margin-top: 14px;
        margin-left:10px;}
        /* Media Queries pour les petits écrans (smartphones) */
        @media (max-width: 768px) {
            .sidebar {
        display: none; /* Masquer la barre de navigation */
    }
            .conversation-box {
             
                width: 90%;
              margin-top: 15%;
              align-self: center;
       
            }
            .form{
                margin-bottom: 10%;
            } 
            .cadre{
                margin-top: -20%;
                margin-left: 0%;
                width: 100%;
                height: 80%;
            }
            .envo{
                background-color: white;
            }

            .formu {
                height: 15%;
                width: 70%;
                margin-right: 50%;
                margin-left: 10%;
           
            
                justify-content: center; /* Pour centrer verticalement les éléments */
            }
            .laa{
                width: 30%;
                margin-right: 30%;
                margin-bottom: 20%;
            }
           
        }
    </style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
    // Fonction pour définir le défilement vers le bas
    function scrollToBottom() {
        var messageContainer = document.querySelector('.conversation-box');
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    // Appeler la fonction lorsque la page se charge
    window.onload = scrollToBottom;
</script>

<?php require_once(__DIR__ . '/header.php'); ?>
<div class="sidebar">
    <h3>Menu</h3>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link bout" href="messagerie.php">Messagerie</a>
        </li>
        <!-- Ajoutez d'autres liens de menu ici -->
    </ul>
</div>
<div class="cadre">
    <div class="container place">
        <div class="conversation-box">
            <div class="messages">
                <?php
                // Requête SQL pour récupérer les messages de la conversation entre l'utilisateur connecté et l'autre utilisateur
                try {
                    $sql = "SELECT * FROM messages WHERE (sender_id = :loggedInUserId AND recipient_id = :conversationUserId) OR (sender_id = :conversationUserId AND recipient_id = :loggedInUserId) ORDER BY sent_at";
                    $stmt = $mysqlClient->prepare($sql);
                    $stmt->execute(['loggedInUserId' => $loggedInUserId, 'conversationUserId' => $conversationUserId]);
                    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($messages as $message) {
                        $imagePath = getimage($message['sender_id'], $users);
                        ?>
                        <div class="d-flex">
                        <a href="profil_affichage.php?user_id=<?php echo htmlspecialchars($message['sender_id']); ?>">
  <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Profile Image" class="profile-image">
</a>

                            <div class="message-container <?php echo ($message['sender_id'] == $loggedInUserId) ? 'my-message' : 'other-message'; ?>">
                                <div class="message-content">
                                    <?php echo htmlspecialchars($message['message_text']); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } catch (Exception $exception) {
                    die('Erreur lors de la récupération des messages : ' . $exception->getMessage());
                }
                ?>
            </div>
        </div>
    </div>
</div>
    <!-- Formulaire pour envoyer un nouveau message -->
 <div class="form">
        <form method="post" class="formu">
            <div class="mb-3 message-input">
                <label for="message" class="form-label font-fam">Message</label>
                <textarea class="form-control" id="message" name="content" rows="1" required></textarea>
            </div>
            <button type="submit" class="envo">  <img src="send.png" alt="Envoyer" width="24" height="24"></button>
        </form>
        </div>
    </div>
  

<!-- Inclure le pied de page -->
<?php require_once(__DIR__ . '/footer.php'); ?>

</body>
</html>

