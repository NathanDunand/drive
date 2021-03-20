<?php
session_start();
include ('../functions/functions.php');
$messages=new functions();
$verify=new functions();
$autorisation=new functions();
$valeur=new functions();

$_POST['couleur']=$verify->verify_entry($_POST['couleur']);
$_GET['id']=$verify->verify_entry($_GET['id']);

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$valeur->modifier_style_par_id($_POST['couleur'], $_GET['id']);
$verify->redirect($_SERVER['HTTP_REFERER']);
exit;
?>