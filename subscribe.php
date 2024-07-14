<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #inscrip {
            margin-top: 50px;
        }
        .font-fam{
            font-family: 'Comic Sans MS', 'Comic Sans', cursive;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <?php require_once(__DIR__ . '/header.php'); ?>
        <div id="inscrip" class="bg-light p-5 rounded-3 shadow-lg">
    <h1 class="text-center mb-4 font-fam">Inscrivez-vous</h1>
    <form action="post_subscribe.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="email" class="form-label font-fam">Email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help">
            <div id="email-help" class="form-text">Nous ne revendrons pas votre email.</div> 
        </div>
        <div class="mb-3">
            <label for="age" class="form-label font-fam">Age</label>
            <input type="number" class="form-control" id="age" name="age" aria-describedby="age-help">
            <div id="age-help" class="form-text">Vos informations restent confidentielles.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label font-fam">Prénom Nom</label>
            <input type="text" class="form-control" id="name" name="name" aria-describedby="name-help">
            <div id="name-help" class="form-text">Vos informations restent confidentielles.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label font-fam">Pseudo</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" aria-describedby="name-help">
            <div id="name-help" class="form-text">Vos informations restent confidentielles.</div>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label font-fam">Bio</label>
            <input type="text" class="form-control" id="bio" name="bio" aria-describedby="name-help">
            <div id="name-help" class="form-text">Vos informations restent confidentielles.</div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label font-fam">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password">
        </div>
        <div class="mb-3">
            <label for="profile_image" class="form-label font-fam">Photo de profil</label>
            <input type="file" class="form-control" id="profile_image" name="profile_image">
            <div id="profile-image-help" class="form-text">Veuillez choisir une image au format JPEG, PNG ou GIF.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100 font-fam">Envoyer</button>
    </form>
</div>

    </div>

    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
