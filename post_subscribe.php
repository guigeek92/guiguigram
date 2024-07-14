<?php
session_start();

require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Données du formulaire
$postData = $_POST;

// Vérifier si tous les champs requis sont remplis
if (empty($postData['email']) || empty($postData['password']) || empty($postData['age']) || empty($postData['name'])) {
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs du formulaire.';
   // redirectToUrl('subscribe.php');
}

// Vérifier si un fichier a été téléchargé
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    // Nom du fichier temporaire
    $tmpFile = $_FILES['profile_image']['tmp_name'];
    // Nom du fichier sur le serveur
    $fileName = $_FILES['profile_image']['name'];
    // Chemin du dossier de destination
    $destination = __DIR__ . '/images/' . $fileName;

    // Déplacer le fichier téléchargé vers le dossier "images"
    if (move_uploaded_file($tmpFile, $destination)) {
        // Le fichier a été téléchargé avec succès

        // Données du formulaire
        $email = $postData['email'];
        $age = (int)$postData['age'];
        $name = $postData['name'];
        $password = $postData['password'];
        $pseudo = $postData['pseudo'];
        $bio = $postData['bio'];


        // Requête SQL pour insérer les données dans la base de données
        $sql = 'INSERT INTO users (full_name, email, password, age, admin, image,pseudo,bio) VALUES (:full_name, :email, :password, :age, 0, :image,:pseudo,:bio)';
        $sub = $mysqlClient->prepare($sql);

        // Exécution de la requête préparée en liant les valeurs aux marqueurs de paramètres
        $sub->execute(array(
            'full_name' => $name,
            'email' => $email,
            'password' => $password,
            'age' => $age,
            'bio'=> $bio,
            'pseudo'=> $pseudo,
            'image' => $fileName // Nom de l'image
        ));
        $sql2 = 'INSERT INTO notif (type, text, user_id) VALUES (:type, :text,:user_id)';
        $stmt2 = $mysqlClient->prepare($sql2);
        $text2= $pseudo." vient d'arriver sur GuiGuiGram!";
        $userId=getid($email,$users);
        $stmt2->execute(array(
            'user_id' => $userId,
            'type' =>"inscription",
            'text' => $text2,
        ));
        // Redirection vers une page de succès
       // redirectToUrl('success.php');
    } else {
        // Une erreur s'est produite lors du téléchargement du fichier
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Une erreur s\'est produite lors du téléchargement du fichier.';
        redirectToUrl('subscribe.php');
    }
} else {
    // Aucun fichier téléchargé ou une erreur s'est produite lors du téléchargement
    $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez télécharger une image de profil.';
    redirectToUrl('subscribe.php');
}
?>
<html>
 <?php $_SESSION['LOGGED_USER']['email']=$email; $_SESSION['LOGGED_USER']['password']=$password; $_SESSION['LOGGED_USER']['name']=$name; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Création de commentaire</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
    <style>
        #recept{
            margin-top: 300px; /* Ajustez la marge supérieure selon vos besoins */
      
        left:500px;
        margin-right: -700px; /* Ajouter une marge à droite */
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

    <?php require_once(__DIR__ . '/header.php'); ?>
    <div id="recept">
        <h1>Nouveau compte créé!</h1>

        <div class="card">
            <div class="card-body">
                <p class="card-text"><b>Bienvenu</b> </p>
            </div>
        </div>
    </div>
    </div>
 <?php   require_once(__DIR__ . '/footer.php'); ?>
</body>

</html>
