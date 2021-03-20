<?php
session_start();
include ('../functions/functions.php');
$favori=new functions();
$verify=new functions();

if(!isset($_POST['id']))//s'il y a une erreur avec l'id du document à ajouter dans les favoris
{
	$_SESSION['error_message']='Erreur dans l\'ajout du favoris.';
	$verify->redirect('index.php');
	exit;
}

$id_document=$verify->verify_entry($_POST['id']);

$favori->ajouter_favori($_SESSION['utilisateur_id'], $id_document);

$verify->redirect('index.php');
exit;
?>