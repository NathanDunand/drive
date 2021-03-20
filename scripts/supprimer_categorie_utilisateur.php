<?php
session_start();
include ('../functions/functions.php');
$categorie=new functions();
$suppr=new functions();
$messages=new functions();
$verify=new functions();
$_GET['id']=$verify->verify_entry($_GET['id']);
$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$categorie->is_connected())
{
	$_SESSION['error_message']='Connectez-vous pour effectuer cette action.';
	$verify->redirect('connexion.php');
	exit;
}
if(!$categorie->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_categorie'))//si on a pas le droit
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}
$suppr->supprimer_categorie_utilisateur_par_id($_GET['id']);
$_SESSION['success_message']='La suppression a été effectuée avec succès.';
$verify->redirect('listes.php');
exit;
?>