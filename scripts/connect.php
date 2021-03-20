<?php
session_start();
include ('../functions/functions.php');

$logs=new functions();
$autorisation=new functions();
$maj=new functions();
$verify=new functions();

$_POST['login']=$verify->verify_entry($_POST['login']);
$_POST['password']=$verify->verify_entry($_POST['password']);

$logs->verification_connexion($_POST['login'], $_POST['password']);

if($autorisation->is_connected())//si la personne est un connectée, elle peut voir les documents
{
	$maj->mise_a_jour_visibilite_document();//on fait la mise à jour
	$maj->redirect('index.php');
	exit;
}
else
{
	$maj->redirect('connexion.php');
	exit;
}
?>