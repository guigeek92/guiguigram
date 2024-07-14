<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/variables.php');
?>

<!DOCTYPE html>
<html>
<head>
<style>
    .font-fam{
        font-family: 'Comic Sans MS', 'Comic Sans', cursive;
    }
    .profile-pic {
        width: 150px; /* Taille de la photo de profil */
        height: 150px;
        object-fit: cover; /* Assurer que l'image est bien ajustée dans le cadre */
        border-radius: 50%; /* Rendre l'image ronde */
        margin-bottom: 20px;
    }
    #profil{
        margin-top: 50px; /* Ajuster la marge selon les besoins */
    }
     .back{
            background-color: #F4CCCC;
        }
</style>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>- Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100 back">
    <div class="container">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <div id="profil" class="mt-5">
            <h1 class="font-fam">Modifier votre Profil</h1>
            <img src="<?php echo getProfileImage($_SESSION['LOGGED_USER']['email'], $users) ?>" alt="Photo de profil" class="profile-pic">
            <form action="post_profil.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Nouveau Email, ancien: <mark><?php echo $_SESSION['LOGGED_USER']['email'] ?></mark></label>
                    <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help">
                    <div id="email-help" class="form-text">Nous ne revendrons pas votre email.</div>
                </div>
                <div class="mb-3">
                    <label for="age" class="form-label">Age, avant: <mark><?php echo getage($_SESSION['LOGGED_USER']['email'],$users) ?></mark></label>
                    <input type="int" class="form-control" id="age" name="age" aria-describedby="age-help">
                    <div id="age-help" class="form-text">vos informations restent confidentielles</div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Prenom Nom, ancien: <mark><?php echo getname($_SESSION['LOGGED_USER']['email'],$users) ?></mark></label>
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="name-help">
                    <div id="name-help" class="form-text">vos informations restent confidentielles</div>
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Biographie</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo getbio($_SESSION['LOGGED_USER']['email'], $users) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Nouveau Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </div>
    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
