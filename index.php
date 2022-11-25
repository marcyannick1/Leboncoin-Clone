<?php
session_start();
include_once("config/PDO.php");

echo "Leboncoin<br><br>";

if(isset($_SESSION['username-logged'])){
    echo ("Bonjour ".$_SESSION['username-logged']);
    echo ('
    <br>
    <a href="create_annonce.php">Ajouter une annonce</a><br>
    <a href="deconnexion.php">Déconnexion</a>');
}else{
    echo('
    <a href="connexion.php">Se connecter</a>');
}

$stmt = $db->prepare("
SELECT annonce_id, annonces.titre, description, prix, annonce_date, categories.titre AS 'categorie', username, user_id
FROM categories
INNER JOIN annonces
ON annonces.categories_categorie_id = categories.categorie_id
INNER JOIN users
ON annonces.users_user_id = users.user_id
ORDER BY annonces.annonce_date DESC");
$stmt->execute();

$data = $stmt->fetchAll();

// print_r($data);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leboncoin</title>
</head>
<body>
    <?php
    foreach ($data as $annonce):
    ?>
    <br><br><span><?= $annonce['username'] ?></span><br><br>
    <a href="annonce.php?annonce_id=<?=$annonce['annonce_id']?>">
        <img src="images/annonces/annonce<?= $annonce['annonce_id']?>.jpg" width="200px"><br>
        <span><?= $annonce['titre'] ?></span><br>
        <span><?= $annonce['prix'] ?> €</span><br>
        <span><?= $annonce['annonce_date'] ?></span><br><br>
    </a>
    <span><?= $annonce['categorie'] ?></span><br><br>
        <?php
        if(isset($_SESSION['user_id-logged']) && $_SESSION['user_id-logged'] == $annonce['user_id']):
        ?>
        <a href="update_annonce.php?annonce_id=<?=$annonce['annonce_id']?>">Modifier</a>

        <?php 
        endif
        ?>
    <?php
    endforeach
    ?>
</body>
</html>