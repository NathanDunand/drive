<?php
session_start();
include ('../functions/functions.php');
$categorie=new functions();
$autorisation=new functions();
$messages=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_connected())//s'il n'est pas connecté
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

if(!isset($_POST['notification']))
{
	$_POST['notification']='oui';
}
else
{
	$_POST['notification']='non';
}
$categorie->modifier_notification_par_id($_SESSION['utilisateur_id'], $_POST['notification']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect('mon_espace.php');
exit;
?>