<?php
session_start();
include ('../functions/functions.php');
$document=new functions();
$id=new functions();
$connect=new functions();
$messages=new functions();
$notification=new functions();
$verify=new functions();

$messages->unset_messages();//réinitialise les messages d'erreur et de succès
if(!$connect->is_connected())
{
	$verify->redirect('connexion.php');
	exit;
}
if($_FILES['fichier']['error']==4)//envoie sans fichier
{
	$_SESSION['error_message']='Veuillez sélectionner un document';
	$verify->redirect('connexion.php');
	exit;
}
//$_FILE
// UPLOAD
$dossier_upload='../documents/';
$max_file_size=10000000;//taille du fichier maximal en octet

// AJOUT EN BDD
$max_id=$id->get_max_id('documents');
$max_id=$max_id+1;//on créer l'id suivant

$extension=pathinfo($_FILES['fichier']['name']);//retourne un tableau comportant des informations sur le fichier

//définition des variables
$nom_fichier=$verify->verify_entry($max_id.'.'.$extension['extension']);
$libelle=$_FILES['fichier']['name'];
$emplacement=$dossier_upload.$nom_fichier;
$date_document=$verify->verify_entry($_POST['date_document']);
$id_utilisateur=$_SESSION['utilisateur_id'];
$date_depot=$verify->verify_entry(date('Y-m-d'));
//$_POST['categorie']=implode('|', $_POST['categorie']).'|';

//notification
if(count($_POST['sous_categorie'])>3 || (in_array('Actualité', $_POST['sous_categorie'])&&count($_POST['sous_categorie'])>1))
{
	$_SESSION['error_message']='Seuls trois choix maximum sont permis dans la liste des sous-catégories.';
	$verify->redirect('ajouter_document.php');
	exit;
}
else
{
	if(!in_array('Actualité', $_POST['sous_categorie']))//si ce n'est pas la catégorie Actu
	{
		foreach ($_POST['sous_categorie'] as $id_sous_categorie)
		{
			foreach ($notification->obtenir_information_sous_categorie_par_id($id_sous_categorie) as $truc)
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
		//$notification->envoie_mail_notifies($mail, '');

		foreach ($_POST['sous_categorie'] as $id)//trouve la catégorie mère si ce n'est pas la cat acutalité
		{
			foreach ($document->obtenir_categorie_par_id_sous_categorie($id) as $one)
			{
				$categorie[]=$one['id_categorie'];
			}
		}
		$categorie=implode('|', $categorie).'|';//en fait un string

		$sous_categorie=$verify->verify_entry($_POST['sous_categorie']);
		$sous_categorie=implode('|', $_POST['sous_categorie']).'|';
	}
	else//si c'est la cat actu
	{
		$categorie='000';//ID TEMPORAIRE DE LA CATEGORIE ACTU (à mettre en string pas en int int000->0)
		$sous_categorie='';
	}
}
if(isset($mail))
{
	$visible_par=implode('|', $mail).'|';
}
else
{
	$visible_par='';
}
if(isset($_POST['notification']))//si notifie d'autres personnes
{
	//CONTROLE
	if(isset($_POST['notification_liste'])&&count($_POST['notification_liste'])>3)
	{
		$_SESSION['error_message']='Seules trois choix sont permis dans la liste.';
		$verify->redirect('ajouter_document.php');
		exit;
	}

	if(isset($_POST['notification_particuliere']) && isset($_POST['notification_liste']))//si on a rajouté un mail particulier à notifier
	{
		//$_POST['notification_particuliere']=$verify->verify_entry($_POST['notification_particuliere']);
		$notification->envoie_mail_notifies($_POST['notification_liste'], $_POST['notification_particuliere']);
	}
	elseif(isset($_POST['notification_liste']) && !isset($_POST['notification_particuliere']))
	{
		$notification->envoie_mail_notifies($_POST['notification_liste'], '');
	}
	elseif(!isset($_POST['notification_liste']) && isset($_POST['notification_particuliere']))
	{
		$notification->envoie_mail_notifies('', $_POST['notification_particuliere']);
	}
}

$date_validite=$verify->verify_entry($_POST['date_validite']);

//on vérifie s'il n'y a pas deux fois le même libellé pour éviter les doublons
//on le fait avant la définition des variables sinon on écrase le nom du fichier par l'id en bdd
// echo 'nom fichier : '.$libelle;
foreach($document->lister_tous_documents() as $one => $info)
{
	if($info['libelle']==$libelle)
	{
		$_SESSION['error_message']='Un fichier existe déjà avec ce nom, merci de le renommer.';
		$verify->redirect('ajouter_document.php');
		exit;
	}
}

// UPLOAD

if($_FILES['fichier']['size']>=$max_file_size)
{
	$_SESSION['error_message']='Le fichier est trop lourd, taille maximale autorisée : 10Mo';
	$verify->redirect('ajouter_document.php');
	exit;
}

if( preg_match('#[\x00-\x1F\x7F-\xFF\\\/]#', $_FILES['fichier']['name']) )//on vérifie si il n'y a pas de caractères de contrôl pour éviter une injection de code sur le serveur
{
	$_SESSION['error_message']='Nom de fichier non valide. Le nom de votre fichier ne doit pas contenir les caractères suivants, <, >, :, « , /, \, |, ?, *, é, è, ê ...<br>Privilégiez des noms de fichiers sans caractères spéciaux.';
	$verify->redirect('ajouter_document.php');
	exit;
}

if( !is_uploaded_file($_FILES['fichier']['tmp_name']) )//on vérifie sur le fichier a été uploadé
{
	$_SESSION['error_message']='Le fichier est introuvable, veuillez réessayer.';
	$verify->redirect('ajouter_document.php');
	exit;
}

else if( !move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier_upload . $nom_fichier) )
{
	$_SESSION['error_message']='Impossible de copier le fichier dans le dossier de destination.';
	$verify->redirect('ajouter_document.php');
	exit;
}
$_SESSION['success_message']='Le fichier a été mis en ligne !';//si tout s'est bien passé
$document->ajouter_document($nom_fichier, $libelle, $emplacement, $date_document, $id_utilisateur, $date_depot, $categorie, $sous_categorie, $date_validite, $visible_par);
$verify->redirect('ajouter_document.php');
?>