<?php
session_start();
include ('../functions/functions.php');
$categorie=new functions();
$autorisation=new functions();
$messages=new functions();
$verify=new functions();

$_POST['nom']=$verify->verify_entry($_POST['nom']);
$_POST['categorie_mere']=$verify->verify_entry($_POST['categorie_mere']);
$_GET['id']=$verify->verify_entry($_GET['id']);
$_POST['position']=$verify->verify_entry($_POST['position']);

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_categorie'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$_POST['id_categories_utilisateurs_notifications']=implode('|', $_POST['id_categories_utilisateurs_notifications']).'|';
$_POST['id_categories_utilisateurs_notifications']=$verify->verify_entry($_POST['id_categories_utilisateurs_notifications']);
$categorie->modifier_sous_categorie_par_id($_GET['id'], $_POST['nom'], $_POST['categorie_mere'], $_POST['id_categories_utilisateurs_notifications'], $_POST['position']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect('categories.php');
exit;
?>