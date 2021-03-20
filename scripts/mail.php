<?php
session_start();
include ('../functions/functions.php');

$destinataires=new functions();
$verify=new functions();

if(isset($_POST['liste']))
{
	$liste=$verify->verify_entry($_POST['liste']);
}
else
{
	$liste='';
}
$destinataire=$verify->verify_entry($_POST['destinataire']);
$objet=$verify->verify_entry($_POST['objet']);
$message=$verify->verify_entry($_POST['message']);//protéger

if($destinataire!='' && $liste!='')//si l'utilisateur a désactivé le js et tente d'entrer deux choses
{
	$_SESSION['error_message']='Action impossible, réactivez votre JavaScript.';
	$verify->redirect('index.php');
	exit;
}

if($destinataire!='')//si on a choisi d'envoyer un mail à une seule personne
{
	$destinataire=explode(';', $destinataire);
	var_dump($destinataire);
	foreach ($destinataire as $one)
	{
		if(!filter_var($one, FILTER_VALIDATE_EMAIL))//si le mail n'est pas valide
		{
			$_SESSION['error_message']='L\'adresse e-mail "'.$one.'" n\'est pas valide.';
			$verify->redirect('mail.php');
			$tout_bon=false;
			$_SESSION['destinataire']=implode(';', $destinataire);
			$_SESSION['objet']=$_POST['objet'];
			$_SESSION['message']=$_POST['message'];
			exit;
		}
		else
		{
			$tout_bon=true;
		}
	}
	if($tout_bon)
	{
		foreach ($destinataire as $one)
		{
			mail($one, $objet, $message);
		}
		$_SESSION['success_message']='Le mail a été envoyé.';
	}
	
	exit;
}
if($liste!='')//si on a choisi d'envoyer un mail à une liste
{
	foreach ($destinataires->lister_utilisateurs_where('poste', $liste) as $destinataire => $info)
	{
		mail($info['mail'], $objet, $message);
	}
	$_SESSION['success_message']='Le mail a été envoyé.';
	exit;
}
$_SESSION['success_message']='Le mail a été envoyé.';
$verify->redirect('mail.php');
exit;
?>