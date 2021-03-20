<?php
session_start();
include ('../functions/functions.php');
$autorisation=new functions();
$verify=new functions();
if(!$autorisation->is_connected($_SESSION['utilisateur_id']))
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer ce type d\'action.';
	$verify->redirect('espace_membre.php');
	exit;
}

$id=$_SESSION['utilisateur_id'];
$_POST['password1']=$verify->verify_entry($_POST['password1']);
$_POST['password2']=$verify->verify_entry($_POST['password2']);
if(isset($_POST['id'])){$id=$verify->verify_entry($_POST['id']);}
else{$id=$_SESSION['utilisateur_id'];}

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte') && $_SESSION['utilisateur_id']!=$_POST['id'])//s'il n'a pas les droits && qu'il n'est pas sur son compte
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer ce type d\'action.';
	$verify->redirect('espace_membre.php');
	exit;
}
if($_POST['password1']!=$_POST['password2'])//si les deux mots de passe sont différents
{
	$_SESSION['error_message']='Les deux mots de passe doivent être identiques.';
	$verify->redirect('index.php');
	exit;
}
else
{
	$password=$_POST['password1'];
	$logs=new functions();
	$logs->modifier_mdp_par_id($id, $password);
	$_SESSION['success_message']='Le mot de passe a été réinitialisé avec succès.';
	$verify->redirect('index.php');
	exit;
}
exit;
?>