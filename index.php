<?php
session_start();

echo "Leboncoin<br><br>";

if(isset($_SESSION['username-logged'])){
    echo ("Bonjour ".$_SESSION['username-logged']);
    echo ('
    <br>
    <a href="deconnexion.php">Déconnexion</a>');
}else{
    echo('
    <a href="connexion.php">Se connecter</a>');
}

?>