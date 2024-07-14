<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #ffffff, #808080);
            font-family: Arial, sans-serif;
            color: white;
        }
        .welcome {
            width: 100%;
            text-align: center;
            margin-top: -200px;
        }
        .formu {
            margin-top: 200px;
            margin-left: -110px;
            margin-right: auto;
            max-width: 400px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .font-fam {
            font-family: 'Comic Sans MS', 'Comic Sans', cursive;
        }
        .alert {
            margin-bottom: 20px;
        }
        @media (max-width: 576px) {
            .formu {
                margin-top: 50px;
                margin-left: -220px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<?php
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');

$admin = 0;
if (isset($_SESSION['LOGGED_USER'])) {
    if (!isset($_SESSION['LOGGED_USER']['name'])) {
        $_SESSION['LOGGED_USER']['name'] = getname($_SESSION['LOGGED_USER']['email'], $users);
    }
}

if (!isset($_SESSION['LOGGED_USER'])):
?>
<!-- Formulaire de connexion -->
<form action="submit_login.php" method="POST">
    <!-- Affichage du message d'erreur s'il existe -->
    <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $_SESSION['LOGIN_ERROR_MESSAGE'];
            unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
        </div>
    <?php endif; ?>

    <!-- Vérification si l'utilisateur est déjà inscrit -->
    <?php if (isset($_SESSION['var']) && $_SESSION['var'] == "connect"): ?>
        <h2>Vous êtes déjà inscrit!</h2>
    <?php endif; ?>

    <!-- Champ email -->
    <div class="formu">
        <h2 class="font-fam"> CONNEXION</h2>
        <div class="mb-3">
            <label for="email" class="form-label font-fam">Email</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="email-help" placeholder="you@example.com" required>
            <div id="email-help" class="form-text">Entrez l'email utilisé lors de la création du compte.</div>
        </div>

        <!-- Champ mot de passe -->
        <div class="mb-3">
            <label for="password" class="form-label font-fam">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>

        <!-- Bouton Envoyer -->
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </div>
</form>
<?php else:
    // Vérification du statut administrateur
    foreach ($users as $user) {
        if ($_SESSION['LOGGED_USER']['email'] === $user['email'] && $user['admin'] == 1) {
            $admin = 1;
            break;
        }
    }

    // Affichage du message de bienvenue en fonction du statut administrateur
    if ($admin == 1):
?>
    <div class="alert alert-info welcome" role="alert">
        Bonjour <?php echo $_SESSION['LOGGED_USER']['name']; ?> et bienvenue sur le site !&emsp;&emsp;&emsp;compte administrateur
    </div>
<?php else: ?>
    <div class="alert alert-success welcome" role="alert">
        Bonjour <?php echo $_SESSION['LOGGED_USER']['name']; ?> et bienvenue sur le site !
    </div>
<?php endif; ?>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

