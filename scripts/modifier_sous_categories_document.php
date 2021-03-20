<?php
session_start();
include ('../functions/functions.php');
$function=new functions();

$_POST['id']=$function->verify_entry($_POST['id']);

$function->unset_messages();//réinitialise les messages d'erreur et de succès

if(!$function->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}

$function->modifier_sous_categorie_par_id_document($_POST['id'], $_POST['sous_categories']);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$verify->redirect('modifier_document.php?id='.$_POST['id'].'');
exit;
?>