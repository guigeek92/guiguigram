<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

if(isset($_SESSION['LOGGED_USER'])) {
    // Récupérer l'identifiant de l'utilisateur actuel
    $subscriberId = getid($_SESSION['LOGGED_USER']['email'],$users);

    // Récupérer l'identifiant de l'utilisateur auquel l'utilisateur actuel souhaite s'abonner
    if(isset($_GET['user_id'])) {
        $subscribedToId = $_GET['user_id'];

        // Vérifier si l'utilisateur est déjà abonné
        $checkSubscriptionQuery = "SELECT * FROM subscriptions WHERE subscriber_id = :subscriber_id AND subscribed_to_id = :subscribed_to_id";
        $checkStmt = $mysqlClient->prepare($checkSubscriptionQuery);
        $checkStmt->execute(['subscriber_id' => $subscriberId, 'subscribed_to_id' => $subscribedToId]);
        $existingSubscription = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if(!$existingSubscription) {
            // Insérer un nouvel enregistrement dans la table des abonnements
            $insertSubscriptionQuery = "INSERT INTO subscriptions (subscriber_id, subscribed_to_id) VALUES (:subscriber_id, :subscribed_to_id)";
            $insertStmt = $mysqlClient->prepare($insertSubscriptionQuery);
            $insertStmt->execute(['subscriber_id' => $subscriberId, 'subscribed_to_id' => $subscribedToId]);
            
            // Rediriger l'utilisateur vers la page de profil de l'utilisateur auquel il s'est abonné
            header('Location: profil_affichage.php?user_id=' . $subscribedToId);
            exit(); // Terminer le script après la redirection
        } else {
            // Si l'utilisateur est déjà abonné, rediriger vers la page de profil de l'utilisateur
            header('Location: profil_affichage.php?user_id=' . $subscribedToId);
            exit(); // Terminer le script après la redirection
        }
    } else {
        // Si l'identifiant de l'utilisateur auquel s'abonner est manquant, afficher un message d'erreur
        echo "Identifiant de l'utilisateur manquant.";
    }
} else {
    // Si l'utilisateur n'est pas connecté, rediriger vers une page de connexion
    header('Location: login.php');
    exit(); // Terminer le script après la redirection
}
?>

