<?php
session_start();?>
<!DOCTYPE html>
<html lang="fr">
<head>
<script src="https://twemoji.maxcdn.com/v/latest/twemoji.min.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(to right, #ffffff, #808080);
            font-family: Arial, sans-serif;
        }

        #publi {
            margin-top: 200px;
        }

        .font-fam {
            font-family: 'Comic Sans MS', 'Comic Sans', cursive;
            color:white;
        }
        .cadre{
            
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
<script>
    // Initialise Twemoji
    twemoji.parse(document.body);
</script>
    <div class="container cadre">
        <?php require_once(__DIR__ . '/header.php'); ?>
        <section id="publi">

        <h1>Publier</h1>
        <form action="post_publication.php" method="POST" enctype="multipart/form-data" accept-charset="UTF-8">
            <div class="mb-3">
                <label for="media" class="form-label font-fam">Choisir une photo ou une vidéo (max 30s)</label>
                <input type="file" class="form-control" id="media" name="media" accept="image/*,video/*">
                <div id="media-help" class="form-text">Veuillez choisir une image au format JPEG, PNG, GIF ou une vidéo de moins de 30 secondes.</div>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Titre de la publication</label>
                <input type="text" class="form-control" id="title" name="title" aria-describedby="title-help">
            </div>
            <div class="mb-3">
                <label for="recipe" class="form-label">Description</label>
                <textarea class="form-control emoji" placeholder="Seulement du contenu vous appartenant ou libre de droits." id="recipe" name="recipe"></textarea>

            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        </section>
    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
