<?php
session_start();
if(isset($_SESSION['username'])){
    echo ("Bonjour ".$_SESSION['username']);
}

echo "<br>Leboncoin";

echo ('
    <br>
    <a href="connexion.php">Connexion</a>
    <br>
    <a href="inscription.php">Inscription</a>
    <br>
    <a href="deconnexion.php">Déconnexion</a>
')

?>