<?php
session_start();
include_once("config/PDO.php");

if (isset($_POST['verification'])) {
    $_SESSION['email-recup'] = $email = $_POST['email-recup'];

    $stmt = $db->prepare("SELECT * FROM `users` WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() != 0) { //Verification si l'email est associé à un compte
        $userexist = true;

        $verif_table_recup = $db->prepare("SELECT * FROM `recuperation_mdp` WHERE email = ?");
        $verif_table_recup->execute([$email]);

        if ($verif_table_recup->rowCount() == 0) { //Verification si un code de recuperation existe déja avec l'email
            $code = rand(100000, 999999);
            $insertcode = $db->prepare("INSERT INTO `recuperation_mdp`(email, code) VALUES (?, ?)");
            $insertcode->execute([$email, $code]);
        }
    }
}

if (isset($_POST['code-verif'])) {
    $codeput = $_POST['code'];

    $verif_code_recup = $db->prepare("SELECT code FROM `recuperation_mdp` WHERE email = ?");
    $verif_code_recup->execute([$_SESSION['email-recup']]);

    $code = $verif_code_recup->fetchColumn();

    if ($codeput != $code) {
        $userexist = true;
        $error = "Code incorrect";
    } else {
        $_SESSION['code-validate'] = true;
        header('location: password_change.php');
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
</head>

<body>
    <?php
    if (!isset($userexist)) :
    ?>
        <form action="" method="post">
            <input type="email" name="email-recup" id="email" placeholder="Entrez votre e-mail" required>
            <button type="submit" name="verification">Verification</button>
        </form>
    <?php
    else :
    ?>
        <form action="" method="post">
            <input type="number" name="code" id="code" placeholder="Entrez votre code">
            <button type="submit" name="code-verif">Code</button>
        </form>
    <?php
    endif
    ?>

    <?php if (isset($error)) : ?>
        <span><?= $error ?></span>
    <?php endif ?>

</body>

</html>