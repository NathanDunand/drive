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
$_POST['poste']=$verify->verify_entry($_POST['poste']);
if(isset($_POST['poste']))
{
	$_SESSION['search']=$verify->obtenir_utilisateurs_par_id_categorie($_POST['poste']);
}
$verify->redirect($_SERVER['HTTP_REFERER']);
exit;
?>