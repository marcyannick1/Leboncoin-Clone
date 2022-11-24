<?php
include_once("config/PDO.php");
session_start();

if (isset($_POST['inscription'])) {
    $user_name = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $emailchek = $db->prepare("SELECT * FROM users WHERE email = ?");
    $emailchek->execute([$email]);

    if ($emailchek->rowCount() == 0) {
        $stmt = $db->prepare("INSERT INTO `users`(`username`, `email`, `password`) VALUES (?, ?, ?)");
        $stmt->execute([$user_name, $email, $password]);

        header('location: connexion.php');
    } else {
        $error = "L'email existe déja";
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
    <title>Inscription</title>
</head>

<body>
    <form action="" method="post">
        <input type="email" name="email" id="email" placeholder="E-mail" value="<?php if (isset($email)) {echo $email;} ?>">
        <input type="text" name="username" id="username" placeholder="Nom">
        <input type="password" name="password" id="password" placeholder="Mot de passe">
        <button type="submit" name="inscription">Inscription</button>
    </form>
    <a href="connexion.php">Se connecter</a>
    <?php if (isset($error)) : ?>
        <span><?= $error ?></span>
    <?php endif ?>
</body>

</html>