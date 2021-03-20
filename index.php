<?php
session_start();//toujours en premier

if(!isset($_SESSION['connecte'])||$_SESSION['connecte']==false)//si la session connecte n'existe pas c'est que l'utilisateur n'est pas connecté
{
	header('Location: vue/connexion.php');
	exit;
}
else
{
	header('Location: vue/index.php');
	exit;
}
?>