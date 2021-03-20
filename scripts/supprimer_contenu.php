<?php
session_start();
include ('../functions/functions.php');
$contenu=new functions();
$autorisation=new functions();
$verify=new functions();

if(!$autorisation->is_connected())//s'il n'est pas co
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	$verify->redirect($_SERVER['HTTP_REFERER']);
	exit;
}
if(!$autorisation->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//s'il n'est pas super admin
{
	$_SESSION['error_message']='Vous ne disposez pas des droits requis pour effectuer cette action.';
	$verify->redirect($_SERVER['HTTP_REFERER']);
	exit;
}
else
{
	$_GET['id']=$verify->verify_entry($_GET['id']);
	$contenu->supprimer_contenu_par_id($_GET['id']);
	$_SESSION['success_message']='Suppression affectuée avec succès.';
	$verify->redirect($_SERVER['HTTP_REFERER']);
	exit;
}
?>