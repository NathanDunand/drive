<?php
session_start();
include ('../functions/functions.php');
$document=new functions();
$autorisation=new functions();
$messages=new functions();
$verify=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès
$_GET['id']=$verify->verify_entry($_GET['id']);
$_POST['libelle']=$verify->verify_entry($_POST['libelle']);
$_POST['date_document']=$verify->verify_entry($_POST['date_document']);

$sous_categories=implode('|', $_POST['sous_categories']).'|';
$sous_categories=$verify->verify_entry($sous_categories);

if(isset($_POST['notification']))
{
	foreach ($_POST['sous_categories'] as $id_sous_categorie)
		{
			foreach ($document->obtenir_information_sous_categorie_par_id($id_sous_categorie) as $truc)
			{
				$tab_id_sous_categories_utilisateurs_notifications[]=explode('|', $truc['id_categories_utilisateurs_notifications']);
			}
		}
		for ($i=0; $i < count($tab_id_sous_categories_utilisateurs_notifications); $i++)
		{//fusion des tableaux pour avoir tous les ids ensemble
			if($i==0)
			{
				$mail=array_merge($tab_id_sous_categories_utilisateurs_notifications[$i]);
			}
			else
			{
				$mail=array_merge($tab_id_sous_categories_utilisateurs_notifications[$i-1], $tab_id_sous_categories_utilisateurs_notifications[$i]);
			}
		}
		$mail=array_unique($mail);//supprime les doublons du tableau et permet ainsi de ne pas envoyer deux fois le même mail.
		$mail=array_filter($mail,'strlen');//enlève les cases '' null du tableau
		$document->envoie_mail_notifies($mail, '');
}

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))//s'il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	$verify->redirect('index.php');
	exit;
}
foreach($document->lister_tous_documents() as $one => $info)
{
	if($info['libelle']==$_POST['libelle'])
	{
		if($_GET['id']!=$info['id'])//on compare les id parce que si on change que la catégorie mais pas le nom, on trouvera une correspondance si ce ne sont pas les mêmes alors ça veut dire qu'on essaie de rentrer deux fichiers avec le même libellé (non apparent)
		{
			$_SESSION['error_message']='Un fichier existe déjà avec ce nom, merci de le renommer.';
			$verify->redirect($_SERVER['HTTP_REFERER']);
			exit;
		}		
	}
}
foreach ($_POST['sous_categories'] as $id_sous_categorie)
{
	foreach ($document->obtenir_information_sous_categorie_par_id($id_sous_categorie) as $truc)
	{
		$tab_id_sous_categories_utilisateurs_notifications=$truc['id_categories_utilisateurs_notifications'];
	}
}

//trouve la catégorie mère
foreach ($_POST['sous_categories'] as $id)
{
	foreach ($document->obtenir_categorie_par_id_sous_categorie($id) as $one)
	{
		$categories[]=$one['id_categorie'];
	}
}
$categories=array_unique($categories);
$categories=implode('|', $categories).'|';//en fait un string
//var_dump($categories=array_unique($categories));
$visible_par=$tab_id_sous_categories_utilisateurs_notifications;
$document->modifier_document_par_id($_GET['id'], $_POST['libelle'], $_POST['date_document'], $categories, $sous_categories, $_POST['date_validite'], $visible_par);
$_SESSION['success_message']='Les modifications ont été enregistrées.';
$document->mise_a_jour_visibilite_document($_POST['date_validite']);
$verify->redirect($_SERVER['HTTP_REFERER']);
exit;
?>