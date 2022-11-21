<?php
session_start();
include_once("config/PDO.php");

if($_SESSION['code-validate']){
    $deletecode = $db->prepare("DELETE FROM `recuperation_mdp` WHERE email = ?");
    $deletecode->execute([$_SESSION['email-recup']]);
    
    if(isset($_POST['password-change'])){
        $password = password_hash($_POST['new-password'], PASSWORD_DEFAULT);

        $stmt = $db->prepare("UPDATE `users` SET `password` = ? WHERE email = ?");
        $stmt->execute([$password,$_SESSION['email-recup']]);

        session_unset();

        header('location: connexion.php');
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
    <title>Password change</title>
</head>
<body>
    <form action="" method="post">
        <input type="password" name="new-password">
        <button type="submit" name="password-change">Changer mdp</button>
    </form>
</body>
</html>