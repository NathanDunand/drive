<?php
session_start();
include ('../functions/functions.php');
$document=new functions();
$suppr=new functions();
$messages=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if($document->is_connected()==false)
{
	$_SESSION['error_message']='Connectez-vous pour effectuer cette action.';
	$favori->redirect('connexion.php');
	exit;
}
if(!$document->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))//si on a pas le droit
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$favori->redirect('index.php');
	exit;
}

foreach ($document->obtenir_information_document_par_id($_GET['id']) as $info => $key)
{
	$chemin=$key['emplacement'];
}
unlink($chemin);//supprime le fichier
$suppr->supprimer_document_par_id($_GET['id']);
$_SESSION['success_message']='La suppression a été effectuée avec succès.';
$favori->redirect($_SERVER['HTTP_REFERER']);
exit;
?>