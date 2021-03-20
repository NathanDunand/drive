<?php
session_start();
include ('../functions/functions.php');
$recherche=new functions();
$verify=new functions();

if(!$recherche->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connnecter pour effectuer cette action.';
	$verify->redirect('connexion.php');
	exit;
}
if(!isset($_SESSION['search']))
{
	$_SESSION['search']='';
}
if(isset($_POST['search']))//si on fait une recherche textuelle
{
	var_dump($_POST['search']);
	$_POST['search']=$verify->verify_entry($_POST['search']);
	$_SESSION['search']=$recherche->recherche_documents($_POST['search']);
}
if(isset($_POST['categorie']))//si on fait une recherche par catégorie
{
	var_dump($_POST['categorie']);
	$_POST['categorie']=$verify->verify_entry($_POST['categorie']);
	$_SESSION['search']=$recherche->recherche_documents_par($_POST['categorie'], 'categorie', $_SESSION['id_utilisateur']);
}
if(isset($_POST['auteur']))//si on fait une recherche par catégorie
{
	var_dump($_POST['auteur']);
	$_POST['auteur']=$verify->verify_entry($_POST['auteur']);
	$_SESSION['search']=$recherche->recherche_documents_par($_POST['auteur'], 'id_utilisateur', $_SESSION['id_utilisateur']);
}
//$verify->redirect($_SERVER['HTTP_REFERER']);
?>