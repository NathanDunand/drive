<?php
session_start();
include ('../functions/functions.php');
$utilisateur=new functions();
$autorisation=new functions();
$messages=new functions();
$verify=new functions();

$_POST['id']=$verify->verify_entry($_POST['id']);
$_POST['nom']=$verify->verify_entry($_POST['nom']);
$_POST['mail']=$verify->verify_entry($_POST['mail']);
$_POST['poste']=implode('|', $_POST['poste']).'|';
$_POST['poste']=$verify->verify_entry($_POST['poste']);

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer ce type d\'action.';
	$verify->redirect('espace_membre.php');
	exit;
}
$utilisateur->modifier_utilisateur_par_id($_POST['id'], $_POST['nom'], $_POST['poste'], $_POST['mail']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect($_SERVER['HTTP_REFERER']);
exit;
?>