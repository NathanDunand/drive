<?php
session_start();
include ('../functions/functions.php');
$favori=new functions();

if(!isset($_POST['id']) && !isset($_POST['id_favori']))//s'il y a une erreur avec l'id du document à ajouter dans les favoris
{
	$_SESSION['error_message']='Erreur dans la suppression du favoris';
	$favori->redirect('index.php');
	exit;
}
if(isset($_POST['id_favori']))// si on vient de mon espace . php
{
	$favori->supprimer_favori_par_id($_POST['id_favori']);
	$favori->redirect('index.php');
	exit;
}
else
{
	$id_document=$_POST['id'];
	$favori->supprimer_favori_par_id_utilisateur($id_document, $_SESSION['utilisateur_id']);
	$favori->redirect('index.php');
	exit;
}
?>