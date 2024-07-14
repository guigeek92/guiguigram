<?php

function displayAuthor(string $authorEmail, array $users): string
{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['full_name'] . '(' . $user['age'] . ' ans)';
        }
    }

    return 'Auteur inconnu';
}
function getname(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['full_name'];
        }
    }

    return 'Auteur inconnu';

}
function getpass(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['password'];
        }
    }

    return 'Auteur inconnu';

}
function getname2(int $user_id, $users) {
    foreach ($users as $user) {
        if ($user['user_id'] == $user_id) {
            if (isset($user['pseudo']) && !empty($user['pseudo'])) {
                return $user['pseudo'];
            } else {
                return 'Auteur inconnu';
            }
        }
    }
    return 'Auteur inconnu';
}

function getage(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['age'];
        }
    }

    return 'Auteur inconnu';


}
function getpseudo(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['pseudo'];
        }
    }

    return 'Auteur inconnu';


}
function getid2( int $publiid, array $publi): string {
    foreach ($publi as $publ) {
        if ($publiid === $publ['publi_id']) {
            // Ajoutez une instruction de débogage ici
            echo "User ID found: " . $publ['user_id'] . "<br>";
            return $publ['user_id'];
        }
    }

    // Ajoutez une instruction de débogage ici
    echo "User ID not found for publication ID: " . $publiid . "<br>";
    return 'Auteur inconnu';
}

function getpseu(int $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['user_id']) {
            return $user['pseudo'];
        }
    }

    return 'Auteur inconnu';


}
function getid(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['user_id'];
        }
    }

    return 'Auteur inconnu';

}
function getbio(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            return $user['bio'];
        }
    }

    return 'Auteur inconnu';

}
function getProfileImage(string $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['email']) {
            $image_folder = "images/";

            // Concaténez le chemin du dossier avec le nom de fichier de la photo de profil
            return $image_folder . $user['image'];
        }
    } 
    return 'Auteur inconnu';
}
function getimagee(int $authorEmail, array $users): string{
    foreach ($users as $user) {
        if ($authorEmail === $user['user_id']) {
            $image_folder = "images/";

            // Concaténez le chemin du dossier avec le nom de fichier de la photo de profil
            return $image_folder . $user['image'];
        }
    } 
    return 'Auteur inconnu';
}
function getimage($user_id, $users) {

    foreach ($users as $user) {
        if ($user['user_id'] == $user_id) {
            if (isset($user['image']) && !empty($user['image'])) {
                $imagePath = 'images/' . $user['image']; // Assurez-vous que le chemin est correct       
                return $imagePath;
            } else {
           
                return 'images/default_profile_image.png'; // Chemin vers une image par défaut
            }
        }
    }
    return 'images/default_profile_image.png'; // Chemin vers une image par défaut
}


function getAdmin(array $users): array
{
    $valid_admin = [];

    foreach ($users as $user) {
        if ($user['admin']===1) {
            $valid_admin[] = $user;
        }
    }

    return $valid_admin;
}

function redirectToUrl(string $url): never
{
    header("Location: {$url}");
    exit();
}
