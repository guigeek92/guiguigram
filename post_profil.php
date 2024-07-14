<?php
session_start();

// require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// On ne traite pas les super globales provenant de l'utilisateur directement,
// ces données doivent être testées et vérifiées.

$postData = $_POST;

// Vérification des champs obligatoires
if ($postData['email'] === '' || $postData['password'] === '' || $postData['age'] === '' || $postData['name'] === '' || $postData['bio'] === '') {
   // $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Tous les champs doivent être remplis pour soumettre le formulaire.';
   // header('Location: profil.php');
   // exit;
}
if ($postData['email'] === '' ){
   $email=$_SESSION['LOGGED_USER']['email']; 
}
else{
    $email = $postData['email'];
}
if ($postData['age'] === '' ){
    $age=getage($_SESSION['LOGGED_USER']['email'],$users);
 }
 else{
     $age = $postData['age'];
 }

 if ($postData['name'] === ''){
    $name=getname($_SESSION['LOGGED_USER']['email'],$users); 
 }
 else{
     $name = $postData['name'];
 }
 if ($postData['bio'] === '' ){
    $bio=getbio($_SESSION['LOGGED_USER']['email'],$users); 
 }
 else{
     $bio = $postData['bio'];
 }
 if ($postData['password'] === '' ){
    $password=getpass($_SESSION['LOGGED_USER']['email'],$users); 
 }
 else{
    $password = $postData['password'];
 }

$currentEmail = $_SESSION['LOGGED_USER']['email']; // L'email actuel de l'utilisateur

// Préparation de la requête SQL avec des paramètres nommés
$sql = 'UPDATE users SET email = :email, full_name = :full_name, password = :password, age = :age, bio = :bio WHERE email = :current_email';
$sub = $mysqlClient->prepare($sql);

// Exécution de la requête préparée en liant les valeurs aux marqueurs de paramètres
$sub->execute(array(
    'full_name' => $name,
    'email' => $email,
    'password' => $password,
    'age' => $age,
    'bio' => $bio,
    'current_email' => $currentEmail, // Utilisation de l'email actuel pour identifier l'utilisateur
));

// Mise à jour de la session utilisateur
$_SESSION['LOGGED_USER']['email'] = $email;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modif{
            margin-top:200px ;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container modif">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Modification(s) enregistrée(s)!</h1>

        <div class="card">
            <div class="card-body">
                <p class="card-text"><b>Bonne continuation</b></p>
            </div>
        </div>
    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
