<?php
session_start();
include_once("config/PDO.php");

echo "Leboncoin<br><br>";

if(isset($_SESSION['username-logged'])){
    echo ("Bonjour ".$_SESSION['username-logged']);
    echo ('
    <br>
    <a href="create_annonce.php">Ajouter une annonce</a><br>
    <a href="messages.php">Messages</a><br>
    <a href="deconnexion.php">Déconnexion</a>');
}else{
    echo('
    <a href="connexion.php">Se connecter</a>');
}

$stmt = $db->query(
    "SELECT annonce_id, annonce_titre, description, prix, annonce_date, categorie_titre, username, user_id
    FROM annonces
    INNER JOIN categories
    ON annonces.categories_categorie_id = categories.categorie_id
    INNER JOIN users
    ON annonces.users_user_id = users.user_id
    ORDER BY annonces.annonce_date DESC");

$data = $stmt->fetchAll();

// echo '<pre>' , print_r($data) , '</pre>';
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
    <form action="recherche.php" method="get">
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
        </select>
        <input type="text" name="text" id="recherche" placeholder="Que recherchez vous?">
        <input type="number" name="prix_min" placeholder="Prix minimum">
        <input type="number" name="prix_max" placeholder="Prix maximum">
        <button type="submit">Recherche</button>
    </form>
    <?php
    foreach ($data as $annonce):
    ?>
    <hr>
    <br><br><span><?= $annonce['username'] ?></span><br><br>
    <a href="annonce.php?annonce_id=<?=$annonce['annonce_id']?>">
        <img src="images/annonces/annonce<?= $annonce['annonce_id']?>_1.jpg" width="200px"><br>
        <span><?= $annonce['annonce_titre'] ?></span><br>
        <span><?= $annonce['prix'] ?> €</span><br>
        <span><?= $annonce['annonce_date'] ?></span><br><br>
    </a>
    <span><?= $annonce['categorie_titre'] ?></span><br><br>
        <?php
        if(isset($_SESSION['user_id-logged']) && $_SESSION['user_id-logged'] == $annonce['user_id']):
        ?>
        <a href="update_annonce.php?annonce_id=<?=$annonce['annonce_id']?>">Modifier</a>
        <a href="delete_annonce.php?annonce_id=<?=$annonce['annonce_id']?>">Supprimer</a>

        <?php 
        endif
        ?>
    <?php
    endforeach
    ?>
</body>
</html>