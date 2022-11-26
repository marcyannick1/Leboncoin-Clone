<?php
session_start();
include_once("config/PDO.php");
if(!empty($_GET['annonce_id'])){
    $annonce_id = $_GET['annonce_id'];
    
    $stmt = $db->prepare("SELECT * FROM `annonces` WHERE annonce_id = ? AND users_user_id = ?");
    $stmt->execute([$annonce_id, $_SESSION['user_id-logged']]);

    if($stmt->rowCount() == 1){
        $data = $stmt->fetch();
        // echo '<pre>' , print_r($data) , '</pre>';

        $titre = $data['annonce_titre'];
        $description = $data['description'];
        $categorie_id = $data['categories_categorie_id'];
        $prix = $data['prix'];
    }else{
        $error = "Cette annonce n'existe pas ou a été supprimée";
    }

    if(isset($_POST['annonce-modif'])){
        $titre_upd = $_POST['titre'];
        $description_upd = $_POST['description'];
        $prix_upd = $_POST['prix'];

        $update = $db->prepare("UPDATE `annonces` SET `annonce_titre`= ?,`description`= ?,`prix`= ? WHERE annonce_id = ?");
        $update->execute([$titre_upd, $description_upd, $prix_upd, $annonce_id]);

        header('location: index.php');
    }
}else{
    header('location: index.php');
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="titre" id="titre" placeholder="Titre" value="<?=$titre?>"><br><br>
        <textarea name="description" placeholder="Description"><?=$description?></textarea><br><br>
        <select name="categorie" id="categorie" disabled>
            <option value="">--Choisir une catégorie--</option>
            <option value="Vehicules" <?php if($categorie_id == 1){echo "selected";}?>>Véhicules</option>
            <option value="Immobilier" <?php if($categorie_id == 2){echo "selected";}?>>Immobilier</option>
            <option value="Mode" <?php if($categorie_id == 3){echo "selected";}?>>Mode</option>
            <option value="Maison" <?php if($categorie_id == 4){echo "selected";}?>>Maison</option>
            <option value="Multimedia" <?php if($categorie_id == 5){echo "selected";}?>>Multimédia</option>
            <option value="Loisirs" <?php if($categorie_id == 6){echo "selected";}?>>Loisirs</option>
            <option value="Animaux"<?php if($categorie_id == 7){echo "selected";}?>>Animaux</option>
            <option value="Materiels professionnel" <?php if($categorie_id == 8){echo "selected";}?>>Matériels Professionnel</option>
            <option value="Divers" <?php if($categorie_id == 9){echo "selected";}?>>Divers</option>
        </select><br><br>
        <input type="number" name="prix" id="prix" placeholder="Prix" value="<?=$prix?>">€<br><br>
        <!-- <input type="file" name="photo" id="photo" accept="image/jpeg, image/png, image/gif, image/heic, image/heif, image/tiff, image/webp" multiple><br><br> -->
        <!-- <input type="file" name="photo" id="photo" accept="image/jpeg,"><br><br> -->
        <button type="submit" name="annonce-modif">Modifier l'annonce</button>
        <a href="index.php">
            Annuler
        </a>
    </form>
</body>
</html>