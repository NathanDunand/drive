<?php
session_start();
include ('../functions/functions.php');
$utilisateur=new functions();
$suppr=new functions();
$verify=new functions();
$messages=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if($utilisateur->is_poste($_GET['id'], 'super-administrateur'))//si on veut supprimer le compte su super admin
{
	$_SESSION['error_message']='Vous ne pouvez pas supprimer le compte du super-administrateur.';
	$verify->redirect('administration.php');
	exit;
}

if($utilisateur->is_connected()==false)
{
	$_SESSION['error_message']='Connectez-vous pour effectuer cette action.';
	$verify->redirect('connexion.php');
	exit;
}
if($_SESSION['utilisateur_id']==$_GET['id'])//on ne peut pas supprimer ses propres informations
{
	$_SESSION['error_message']='Vous ne pouvez pas supprimer votre propre compte.';
	$verify->redirect('index.php');
	exit;
}
$suppr->supprimer_utilisateur_par_id($_GET['id']);
$_SESSION['success_message']='La suppression a été effectuée avec succès.';
$verify->redirect('administration.php');
exit;
?>