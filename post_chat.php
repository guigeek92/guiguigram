<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <style>
        body {
            background-color: #f0f0f0; /* Couleur de fond pour le corps */
            font-family: Arial, sans-serif; /* Police par défaut */
        }

        #chat-container {
            max-width: 800px; /* Largeur maximale du chat */
            margin: 0 auto; /* Centrer le chat horizontalement */
            padding: 20px; /* Espacement intérieur du conteneur du chat */
            background-color: black; /* Couleur de fond pour le chat */
            color: white; /* Couleur du texte dans le chat */
            border-radius: 10px; /* Arrondir les coins du chat */
        }

        .message-container {
            margin-bottom: 10px; /* Espacement entre les messages */
        }

        .message-text {
            font-size: 14px; /* Taille de la police pour les messages */
        }

        .message-user {
            font-weight: bold; /* Mettre en gras le nom d'utilisateur */
        }
    </style>
</head>
<body>
    <div id="chat-container">
        <?php
        session_start();

        require_once(__DIR__ . '/mysql.php');
        require_once(__DIR__ . '/databaseconnect.php');
        require_once(__DIR__ . '/variables.php');
        require_once(__DIR__ . '/functions.php');

        // Vérifier si la couleur de l'utilisateur est déjà définie dans la session
        if (!isset($_SESSION['user_colors'])) {
            $_SESSION['user_colors'] = [];
        }

        // Fonction pour générer une couleur aléatoire
        function randomColor() {
            $r = mt_rand(0, 255);
            $g = mt_rand(0, 255);
            $b = mt_rand(0, 255);
            return "rgb($r, $g, $b)";
        }

        // Récupérer et stocker la couleur de l'utilisateur
        function getUserColor($userId) {
            if (!isset($_SESSION['user_colors'][$userId])) {
                $_SESSION['user_colors'][$userId] = randomColor();
            }
            return $_SESSION['user_colors'][$userId];
        }
        $query = "SELECT * FROM chat ORDER BY id_message DESC"; // Gardez l'ordre de tri actuel pour récupérer les messages du plus récent au plus ancien
        $messages = []; // Initialisation d'un tableau pour stocker les messages
        
        try {
            $stmt = $mysqlClient->prepare($query);
            $stmt->execute();
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $userId = $row['user_id'];
                $message = $row['message'];
                $userColor = getUserColor($userId);
        
                // Stocker le message dans le tableau avec la couleur de l'utilisateur
                $messages[] = "<div class='message-container'><p><strong class='message-user' style='color: $userColor;'>".getname2($userId, $users)." :</strong></p><p class='message-text'>$message</p></div>";
            }
        
            // Afficher les messages dans l'ordre inverse (du plus récent au plus ancien)
            foreach (array_reverse($messages) as $message) {
                echo $message;
            }
        } catch (PDOException $e) {
            die("Erreur lors de l'exécution de la requête : " . $e->getMessage());
        }
        
        
        ?>
    </div>
</body>
</html>


