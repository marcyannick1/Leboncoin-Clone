<?php
session_start();
include_once("config/PDO.php");

if(!empty($_GET['annonce_id'])){
    $annonce_id = $_GET['annonce_id'];
    
    $stmt = $db->prepare(
        "SELECT * FROM annonces
        INNER JOIN users ON annonces.users_user_id	= users.user_id
        INNER JOIN categories ON annonces.categories_categorie_id	= categories.categorie_id
        WHERE annonce_id = ?");

    $stmt->execute([$annonce_id]);

    if($stmt->rowCount() == 1){
        $data = $stmt->fetch();
        // echo '<pre>' , print_r($data) , '</pre>';

        $titre = $data['annonce_titre'];
        $description = $data['description'];
        $prix = $data['prix'];
        $annonce_date = $data['annonce_date'];
        $annonce_user_id = $data['user_id'];
        $username = $data['username'];
        $categorie = $data['categorie_titre'];
    }else{
        $error = "Cette annonce n'existe pas ou a été supprimée";
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
    <span><?=$username?></span>
    <h1><?=$titre?></h1>
    <span><?=$categorie?></span>
    <div>
        <img src="images/annonces/annonce<?= $_GET['annonce_id']?>.jpg">
    </div>
    <span><?=$prix?> €</span><br>
    <span><?=$annonce_date?></span>
    <p><?=$description?></p>

    <?php
    if(isset($_SESSION['user_id-logged']) && $annonce_user_id == $_SESSION['user_id-logged']):
    ?>
    <a href="">Voir Messages</a>
    <?php
    else:
    ?>
    <a href="">Envoyer Message</a>
    <?php
    endif
    ?>

</body>
</html>