<?php
session_start();
include ('../functions/functions.php');
$categorie=new functions();
$autorisation=new functions();
$messages=new functions();
$verify=new functions();

$_POST['nom']=$verify->verify_entry($_POST['nom']);
$_GET['id']=$verify->verify_entry($_GET['id']);

if($_GET['id']==000)//catégorie actu
{
	$_SESSION['error_message']='Vous ne pouvez pas modifier la catégorie Actualité.';
	$verify->redirect('categories.php');
	exit;
}

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_categorie'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$categorie->modifier_categorie_par_id($_GET['id'], $_POST['nom']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect('categories.php');
exit;
?>