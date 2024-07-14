<?php
session_start();

require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
    $text = htmlspecialchars($_POST['recipe'], ENT_QUOTES, 'UTF-8');
    $userId = getid($_SESSION['LOGGED_USER']['email'], $users);
    $uploadDir = __DIR__ . '/publication/';

    // Vérifier si un fichier a été téléchargé
    if (!empty($_FILES['media']['name'])) {
        $file = $_FILES['media'];
        $fileName = basename($file['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = mime_content_type($file['tmp_name']);

        // S'assurer que le nom du fichier est unique
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $counter = 1;
        while (file_exists($filePath)) {
            $fileName = $baseName . '_' . $counter . '.' . $extension;
            $filePath = $uploadDir . $fileName;
            $counter++;
        }

        // Vérifier le type de fichier
        if (strpos($fileType, 'image/') === 0 || strpos($fileType, 'video/') === 0) {
            // Déplacer le fichier téléchargé vers le dossier de destination
            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Vérifier la durée de la vidéo si c'est une vidéo
                if (strpos($fileType, 'video/') === 0) {
                    $videoDuration = exec("ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '$filePath'");
                    if ($videoDuration > 30) {
                        unlink($filePath); // Supprimer la vidéo si elle dépasse 30 secondes
                        $_SESSION['UPLOAD_ERROR'] = 'La vidéo ne doit pas dépasser 30 secondes.';
                        header('Location: index.php');
                        exit();
                    }
                }
            } else {
                $_SESSION['UPLOAD_ERROR'] = 'Erreur lors du téléchargement du fichier.';
                header('Location: index.php');
                exit();
            }
        } else {
            $_SESSION['UPLOAD_ERROR'] = 'Seuls les fichiers image et vidéo sont autorisés.';
            header('Location: index.php');
            exit();
        }
    } else {
        $fileName = null; // Pas de fichier téléchargé
    }

    // Requête SQL pour insérer les données dans la base de données
    $sql = 'INSERT INTO publication (name, text, media_path, user_id) VALUES (:title, :text, :media_path, :user_id)';
    $stmt = $mysqlClient->prepare($sql);
    $sql2 = 'INSERT INTO notif (type, text, user_id) VALUES (:type, :text,:user_id)';
    $stmt2 = $mysqlClient->prepare($sql2);

    // Exécuter la requête préparée en liant les valeurs aux marqueurs de paramètres
    $stmt->execute(array(
        'user_id' => $userId,
        'title' => $title,
        'text' => $text,
        'media_path' => $fileName, // Enregistrer le nom du fichier dans la base de données
    ));
    $text2= getname2($userId,$users)." a publié du nouveau contenu!";
    $stmt2->execute(array(
        'user_id' => $userId,
        'type' =>"publication",
        'text' => $text2,
       
    ));

    // Rediriger vers une autre page ou afficher un message de succès
    header('Location: index.php');
    exit();
}
?>

