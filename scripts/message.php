<?php
session_start();
include ('../functions/functions.php');
$autorisation=new functions();
$verify=new functions();

$objet=$verify->verify_entry($_POST['objet']);
$message=$verify->verify_entry($_POST['message']);//protéger

if(!$autorisation->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	$verify->redirect('connexion.php');
	exit;
}
mail(MAIL_MESSAGE, $objet, $message);//envoie du mail
$_SESSION['success_message']='Votre message a été envoyé avec succès.';
$verify->redirect('mon_espace.php');
exit;
?>