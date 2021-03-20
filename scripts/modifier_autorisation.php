<?php
session_start();
include ('../functions/functions.php');
$messages=new functions();
$verify=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès

if($_SESSION['utilisateur_id']==$_GET['id'])//on ne peut pas modifier ses propres informations
{
	$_SESSION['Vous ne pouvez pas modifier vos propres informations.'];
	$verify->redirect('index.php');
	exit();
}

if(!isset($_POST['creer_compte'])){$_POST['creer_compte']='non';}/*si la checkbox n'a rien envoyé, alors la variable post n'existe pas*/
else{$_POST['creer_compte']='oui';}/*si la variable existe on lui attribue la valeur oui pour la bdd*/

if(!isset($_POST['modifier_compte'])){$_POST['modifier_compte']='non';}
else{$_POST['modifier_compte']='oui';}

if(!isset($_POST['supprimer_compte'])){$_POST['supprimer_compte']='non';}
else{$_POST['supprimer_compte']='oui';}

if(!isset($_POST['ajout_document'])){$_POST['ajout_document']='non';}
else{$_POST['ajout_document']='oui';}

if(!isset($_POST['modifier_document'])){$_POST['modifier_document']='non';}
else{$_POST['modifier_document']='oui';}

if(!isset($_POST['supprimer_document'])){$_POST['supprimer_document']='non';}
else{$_POST['supprimer_document']='oui';}

if(!isset($_POST['ajouter_categorie'])){$_POST['ajouter_categorie']='non';}
else{$_POST['ajouter_categorie']='oui';}

if(!isset($_POST['modifier_categorie'])){$_POST['modifier_categorie']='non';}
else{$_POST['modifier_categorie']='oui';}

$autorisations=new functions();
$autorisations->modifier_autorisations_par_id_utilisateur($_GET['id'], $_POST['creer_compte'], $_POST['modifier_compte'], $_POST['supprimer_compte'], $_POST['ajout_document'], $_POST['modifier_document'], $_POST['supprimer_document'], $_POST['ajouter_categorie'], $_POST['modifier_categorie']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect('administration.php');
exit;
?>