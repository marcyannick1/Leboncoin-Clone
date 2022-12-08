<?php
session_start();
include_once("config/PDO.php");

if(isset($_SESSION['username-logged'])){
    if(isset($_POST['annonce'])){ //si Clique sur Publier l'annonce
        if(!empty($_POST['categorie'])){
            $titre = $_POST['titre'];
            $description = $_POST['description'];
            $prix = $_POST['prix'];
            $categorie = $_POST['categorie'];

            $categorie_id = "SELECT `categorie_id` FROM `categories` WHERE categorie_titre = '$categorie'";
            $user_id = $_SESSION['user_id-logged'];

            $stmt = $db->prepare("INSERT INTO `annonces`(`annonce_titre`, `description`, `prix`, `annonce_date`, `categories_categorie_id`, `users_user_id`) VALUES (?, ?, ?, now(), ($categorie_id), ?)");
            $stmt->execute([$titre, $description, $prix, $user_id]);

            header('location: index.php');

            // Ajout photos bdd et fichiers
            $lastid = $db->lastInsertId();

            for ($i=0; $i < count($_FILES['photos']['tmp_name']); $i++) {
                $name = "annonce".$lastid."_".($i+1).".jpg";
                $path = "images/annonces/".$name;
                move_uploaded_file($_FILES['photos']['tmp_name'][$i], $path);

                $stmt = $db->prepare("INSERT INTO `photos`(`photo_nom`, `annonces_annonce_id`) VALUES (?, ?)");
                $stmt->execute([$name, $lastid]);
            }
        }else{
            $error = "Veuillez remplir tout les champs";
        }
    }
}else{
    header('location: connexion.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annonce</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="titre" id="titre" placeholder="Titre"><br><br>
        <textarea name="description" placeholder="Description"></textarea><br><br>
        <select name="categorie" id="categorie">
            <option value="">Choisir une catégorie</option>
            <option value="Vehicules">Véhicules</option>
            <option value="Immobilier">Immobilier</option>
            <option value="Mode">Mode</option>
            <option value="Maison">Maison</option>
            <option value="Multimedia">Multimédia</option>
            <option value="Loisirs">Loisirs</option>
            <option value="Animaux">Animaux</option>
            <option value="Materiels professionnel">Matériels Professionnel</option>
            <option value="Divers">Divers</option>
        </select><br><br>
        <input type="number" name="prix" id="prix" placeholder="Prix">€<br><br>
        <!-- <input type="file" name="photo" id="photo" accept="image/jpeg, image/png, image/gif, image/heic, image/heif, image/tiff, image/webp" multiple><br><br> -->
        <input type="file" name="photos[]" id="photo" accept="image/jpeg" multiple><br><br>
        <button type="submit" name="annonce">Publier l'annonce</button>
    </form>
    <?php if (isset($error)) : ?>
        <span><?= $error ?></span>
    <?php endif ?>
</body>
</html>