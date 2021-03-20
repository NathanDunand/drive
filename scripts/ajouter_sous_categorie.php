<?php
session_start();
include ('../functions/functions.php');
$messages=new functions();
$categorie=new functions();
$autorisation=new functions();
$verify=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès
$nom=$verify->verify_entry($_POST['nom']);
$id_categorie=$verify->verify_entry($_POST['id_categorie']);

if(!$autorisation->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'ajouter_categorie'))
{
	$_SESSION['error_message']='Vous n\'avez pas l\'autorisation requise pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$categorie->ajouter_sous_categorie($id_categorie, $nom);
$_SESSION['success_message']='L\'ajout a été effectué avec succès.';
$verify->redirect('categories.php');
exit;
?>