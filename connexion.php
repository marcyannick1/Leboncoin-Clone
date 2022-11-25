<?php
session_start();
include_once("config/PDO.php");

if (isset($_POST['connexion'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM `users` WHERE email = ?");
    $stmt->execute([$email]);

    $data = $stmt->fetch();
    
    $user_id = $data['user_id'];
    $username = $data['username'];
    $password_hash = $data['password'];

    if (password_verify($password, $password_hash)) {
        $_SESSION['user_id-logged'] = $user_id;
        $_SESSION['email-logged'] = $email;
        $_SESSION['username-logged'] = $username;
        header("location: index.php");
    } else {
        $error = "Identifiant ou mot de passe incorrect";
    }
}

if(isset($_SESSION['email-logged'])){
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>

<body>
    <form action="" method="post">
        <input type="email" name="email" id="email" placeholder="E-mail" value="<?php if (isset($email)) {echo $email;} ?>">
        <input type="password" name="password" id="password" placeholder="Mot de passe">
        <button type="submit" name="connexion">Connexion</button>
        <a href="inscription.php">S'inscrire</a><br>
        <a href="password_reset.php">Mot de passe oublié</a>
    </form>
    <?php if (isset($error)) : ?>
        <span><?= $error ?></span>
    <?php endif ?>
</body>

</html>