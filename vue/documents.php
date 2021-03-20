<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$autorisation=new functions();
$contenu=new functions();
include ('header.php');
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'ajout_document'))//si la personne peut ajouter un document
{
	$ajouter_document='<a class="btn btn-info" href="../vue/ajouter_document.php">Ajouter un document</a>';
}
else
{
	$ajouter_document='';
}
echo $ajouter_document;
if($connect->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
{
	include ('vue_part/document_admin.php');
}
include ('footer.php');
?>