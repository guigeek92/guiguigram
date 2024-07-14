<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: login.php');
    exit();
}

// Vérifier si le paramètre user_id est présent dans l'URL
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];
} else {
    // Rediriger vers une page d'erreur si le paramètre user_id est manquant
    header('Location: erreur.php');
    exit();
}

// Vérifier si l'utilisateur est déjà abonné à ce profil
$loggedInUserId =getid($_SESSION['LOGGED_USER']['email'],$users);
$query = "SELECT * FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_to_id = :subscribed_to_id";
$stmt = $mysqlClient->prepare($query);
$stmt->execute(['subscriber_id' => $loggedInUserId, 'subscribed_to_id' => $userId]);
$isSubscribed = $stmt->rowCount() > 0;

// Si l'utilisateur est abonné, le désabonner
if ($isSubscribed) {
    $query = "DELETE FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_to_id = :subscribed_to_id";
    $stmt = $mysqlClient->prepare($query);
    $stmt->execute(['subscriber_id' => $loggedInUserId, 'subscribed_to_id' => $userId]);
}

// Rediriger l'utilisateur vers la page du profil
header("Location: profil_affichage.php?user_id=$userId");
exit();
?>
