<?php
session_start();
include ('../functions/functions.php');
$autorisation=new functions();
$messages=new functions();
$verify=new functions();
$valeur=new functions();

if(!isset($_POST['MAIL_MESSAGE'])){$_POST['MAIL_MESSAGE']=MAIL_MESSAGE;}
if(!isset($_POST['OBJET_NOTIFICATION'])){$_POST['OBJET_NOTIFICATION']=OBJET_NOTIFICATION;}
if(!isset($_POST['MESSAGE_NOTIFICATION'])){$_POST['MESSAGE_NOTIFICATION']=MESSAGE_NOTIFICATION;}
if(!isset($_POST['X_DERNIERS_DOCUMENTS_FIL_ACTU'])){$_POST['X_DERNIERS_DOCUMENTS_FIL_ACTU']=X_DERNIERS_DOCUMENTS_FIL_ACTU;}

$_POST['MAIL_MESSAGE']=$verify->verify_entry($_POST['MAIL_MESSAGE']);
$_POST['OBJET_NOTIFICATION']=$verify->verify_entry($_POST['OBJET_NOTIFICATION']);
$_POST['MESSAGE_NOTIFICATION']=$verify->verify_entry($_POST['MESSAGE_NOTIFICATION']);
$_POST['X_DERNIERS_DOCUMENTS_FIL_ACTU']=$verify->verify_entry($_POST['X_DERNIERS_DOCUMENTS_FIL_ACTU']);

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$valeur->modifier_configuration_par_nom($_POST['MAIL_MESSAGE'], 'MAIL_MESSAGE');
$valeur->modifier_configuration_par_nom($_POST['OBJET_NOTIFICATION'], 'OBJET_NOTIFICATION');
$valeur->modifier_configuration_par_nom($_POST['MESSAGE_NOTIFICATION'], 'MESSAGE_NOTIFICATION');
$valeur->modifier_configuration_par_nom($_POST['X_DERNIERS_DOCUMENTS_FIL_ACTU'], 'X_DERNIERS_DOCUMENTS_FIL_ACTU');
$_SESSION['success_message']='La configuration a été enregistrée.';
$verify->redirect('index.php');
exit;
?>