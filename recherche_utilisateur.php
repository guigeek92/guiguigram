<?php
session_start();
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if ($searchTerm) {
    $sql = "SELECT * FROM users WHERE pseudo LIKE :searchTerm";
    $stmt = $mysqlClient->prepare($sql);
    $stmt->execute(['searchTerm' => '%' . $searchTerm . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $results = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styles personnalisés pour le contenu */
        .container {
            margin-top: 80px; /* Ajustement pour le header fixe */
        }

        .results-list {
            margin-top: 20px;
            list-style-type: none; /* Suppression des puces */
            padding: 0; /* Suppression des marges internes */
        }

        .result-item {
            display: flex;
            align-items: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 50px;
            margin-bottom: 10px;
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }

        .result-item:hover {
            background-color: #f9f9f9;
        }

        .profile-picture {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover; /* Recadrer l'image pour remplir le conteneur */
            margin-right: 15px;
        }

        .profile-name {
            font-size: 1.2em;
            font-weight: 600;
            color: #333;
            text-decoration: none;
        }

        .profile-name:hover {
            text-decoration: none;
            color: #007bff;
        }
        .profil{
            margin-top:200px;
        }
    </style>
</head>
<body>
    <!-- Inclusion du header -->
    <?php require_once(__DIR__ . '/header.php');
     $image_folder = "images/"; ?>

    <div class="container profil">
        <h1>Résultats de la recherche</h1>
        <?php if ($results): ?>
            <ul class="results-list">
            <?php foreach ($results as $user): ?>
    <li class="result-item">
        <img src="<?php echo htmlspecialchars($image_folder.$user['image']); ?>" alt="Photo de profil" class="profile-picture">
        <a href="profil_affichage.php?user_id=<?php echo htmlspecialchars($user['user_id']); ?>" class="profile-name">
            <?php echo htmlspecialchars($user['pseudo']); ?>
        </a>
    </li>
<?php endforeach; ?>

            </ul>
        <?php else: ?>
            <p>Aucun utilisateur trouvé pour "<?php echo htmlspecialchars($searchTerm); ?>"</p>
        <?php endif; ?>
        <a href="index.php" class="btn btn-primary mt-3">Retour à l'accueil</a>
    </div>

    <!-- Inclusion du footer -->
    <?php require_once(__DIR__ . '/footer.php'); ?>

    <!-- Bootstrap JS et dépendances -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

