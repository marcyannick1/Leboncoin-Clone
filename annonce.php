<?php
include_once("config/PDO.php");
if(empty($_GET['annonce_id'])){
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
    <div>
        <img src="images/annonces/annonce<?= $_GET['annonce_id']?>.jpg" width="400px">
    </div>
</body>
</html>