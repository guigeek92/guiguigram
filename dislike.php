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

// Récupérer l'ID de l'utilisateur connecté
$userId = getid($_SESSION['LOGGED_USER']['email'], $users);

// Vérifier si le paramètre publi_id est présent dans l'URL
if (isset($_GET['publi_id'])) {
    $publiId = $_GET['publi_id'];
} else {
    // Rediriger vers une page d'erreur si le paramètre est manquant
    header('Location: erreur.php');
    exit();
}

// Supprimer le like de l'utilisateur pour cette publication
$sqlDeleteLike = "DELETE FROM `like` WHERE `publi_id` = :publi_id AND `user_id` = :user_id";
$stmtDeleteLike = $mysqlClient->prepare($sqlDeleteLike);
$stmtDeleteLike->execute(['publi_id' => $publiId, 'user_id' => $userId]);

// Rediriger l'utilisateur vers la page d'affichage de la publication
header("Location: affichage_publication.php?publi_id=$publiId");
exit();

