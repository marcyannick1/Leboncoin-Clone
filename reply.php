<?php
session_start();
include_once("config/PDO.php");

if(!empty($_GET['annonce_id'])){
    $annonce_id = $_GET['annonce_id'];
    isset($_SESSION['user_id-logged']) ? $user_id_logged = $_SESSION['user_id-logged'] : header('location: connexion.php');
    
    $stmt = $db->prepare(
        "SELECT * FROM annonces
        INNER JOIN users ON annonces.users_user_id = users.user_id
        INNER JOIN categories ON annonces.categories_categorie_id = categories.categorie_id
        WHERE annonce_id = ?");

    $stmt->execute([$annonce_id]);

    // Verification si l'annonce existe
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

    // Verification du user qui envoie le msg
    if($user_id_logged == $annonce_user_id){
        header('location: annonce.php?annonce_id='.$annonce_id);
    }

    // Verification si un message existe déja
    $check_message = $db->prepare("SELECT * FROM `messages` WHERE annonces_annonce_id = ? AND users_user_id = ?");
    $check_message->execute([$annonce_id, $user_id_logged]);
    if($check_message->rowCount() != 0){
        header('location: messages.php');
    }

}else{
    header('location: index.php');
}

if(isset($_POST['send-message'])){
    $message = $_POST['message'];
    $stmt = $db->prepare("INSERT INTO `messages`(`message`, `message_date`, `annonces_annonce_id`, `users_user_id`, `destinataire_id`) VALUES (?, now(), ?, ?, ?)");
    $stmt->execute([$message, $annonce_id, $user_id_logged, $annonce_user_id]);
    header('location: annonce.php?annonce_id='.$annonce_id);
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
    <?php if(isset($data)): ?>
        <form action="" method="post">
            <textarea name="message" id="message" placeholder="Bonjour cette annonce est disponible ?"></textarea><br>
            <button type="submit" name="send-message">Envoyer</button>
        </form>
    <?php elseif (isset($error)) : ?>
        <span><?= $error ?></span>
    <?php endif ?>
</body>
</html>
