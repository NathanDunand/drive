<?php
session_start();
include ('../functions/functions.php');
$messages=new functions();
$verify=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

$_POST['nom']=$verify->verify_entry($_POST['nom']);

$_POST['poste']=implode('|', $_POST['poste']);
$_POST['poste']=$verify->verify_entry($_POST['poste']);
$_POST['mail']=$verify->verify_entry($_POST['mail']);
$_POST['password1']=$verify->verify_entry($_POST['password1']);
$_POST['password2']=$verify->verify_entry($_POST['password2']);

if($_POST['password1']!=$_POST['password2'])//si les deux mots de passe sont différents
{
	$_SESSION['error_message']='Les deux mots de passe doivent être identiques.';
	$verify->redirect('inscription.php');
	exit;
}

if(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))//si le mail n'est pas valide
{
	$_SESSION['error_message']='L\'adresse e-mail "'.$_POST['mail'].'" n\'est pas valide.';
	$verify->redirect('inscription.php');
	exit;
}

$logs=new functions();
$logs->ajouter_utilisateur($_POST['nom'], $_POST['poste'], $_POST['mail'], $_POST['password1']);

$_SESSION['success_message']='L\'inscription a été réalisée avec succès.';
$verify->redirect('inscription.php');
exit;
?>