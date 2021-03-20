<?php
session_start();
include ('../functions/functions.php');
$contenu=new functions();
$autorisation=new functions();
$verify=new functions();

if(isset($_POST['texte']))//si on modifie du texte
{
	$texte=addslashes($_POST['texte']);
	$_GET['id']=$verify->verify_entry($_GET['id']);

	if(!$autorisation->is_connected())//s'il n'est pas co
	{
		$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
		$verify->redirect('index.php');
		exit;
	}

	if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))//s'il n'a pas les droits
	{
		$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
		$verify->redirect('index.php');
		exit;
	}
	switch ($_GET['id'])
	{
		case 35://iframe google agenda
		$texte=$_POST['texte'];
		$texte=substr_replace($texte, 'class="agenda" ', 8, 0);
		break;
	}

	$contenu->modifier_contenu_par_id($_GET['id'], $texte);
	$verify->redirect($_SERVER["HTTP_REFERER"]);
	exit;
}
else//si on modifie une image
{
	$extension=pathinfo($_FILES['fichier']['name']);//retourne un tableau comportant des informations sur le fichier

	$_GET['id']=$verify->verify_entry($_GET['id']);
	switch ($_GET['id'])
	{
		case 14:
		$nom_fichier='logo';
		break;
		case 15:
		$nom_fichier='footer1';
		break;
		case 16:
		$nom_fichier='footer2';
		break;
		case 17:
		$nom_fichier='footer3';
		break;
		case 18:
		$nom_fichier='footer4';
		break;
		case 19:
		$nom_fichier='footer5';
		break;
		case 21:
		$nom_fichier='footer6';
		break;
		case 23:
		$nom_fichier='background_connexion';
		break;
		case 27:
		$nom_fichier='favicon';
		if($extension['extension']!='png'&&$extension['extension']!='webp')
		{
			echo $extension['extension'];
			$_SESSION['error_message']='Pour l\'icône du site, seules les images au format PNG sont acceptées.';
			$verify->redirect($_SERVER["HTTP_REFERER"]);
	        	exit;
	        }
	        break;
	        case 28:
	        $nom_fichier='banner';
	        if($extension['extension']!='jpg'&&$extension['extension']!='webp')
	        {
	        	echo $extension['extension'];
	        	$_SESSION['error_message']='Pour la bannière du site, seules les images au format JPG ou WEBP sont acceptées.';
	        	$verify->redirect($_SERVER["HTTP_REFERER"]);//renvoie à la page précédente
	        	exit;
	        }
	        break;
	    }

	if($_FILES['fichier']['error']==4)//envoie sans fichier
	{
		$_SESSION['error_message']='Veuillez sélectionner un document';
		$verify->redirect('connexion.php');
		exit;
	}
	//$_FILE
	// UPLOAD
	$dossier_upload='../vue/vue_part/images/';
	$max_file_size=4000000;//taille du fichier maximal en octet

	//définition des variables
	$nom_fichier=$verify->verify_entry($nom_fichier.'.'.$extension['extension']);
	$libelle=$_FILES['fichier']['name'];
	$emplacement=$dossier_upload.$nom_fichier;

	if($extension['extension']!='png'&&$extension['extension']!='jpg'&&$extension['extension']!='webp')
	{
		$_SESSION['error_message']='Seules les images au format PNG, JPG ou WEBP sont acceptées.';
		$verify->redirect($_SERVER["HTTP_REFERER"]);
		exit;
	}

	if($_FILES['fichier']['size']>=$max_file_size)
	{
		$_SESSION['error_message']='Le fichier est trop lourd, taille maximale autorisée : '.$max_file_size/1000;
		$verify->redirect($_SERVER["HTTP_REFERER"]);
		exit;
	}

	if( preg_match('#[\x00-\x1F\x7F-\xFF\\\/]#', $_FILES['fichier']['name']) )//on vérifie si il n'y a pas de caractères de contrôl pour éviter une injection de code sur le serveur
	{
		$_SESSION['error_message']='Nom de fichier non valide. Le nom de votre fichier ne doit pas contenir les caractères suivants, <, >, :, « , /, \, |, ?, *.';
		$verify->redirect($_SERVER["HTTP_REFERER"]);
		exit;
	}

	if(!is_uploaded_file($_FILES['fichier']['tmp_name']))//on vérifie sur le fichier a été uploadé
	{
		$_SESSION['error_message']='Le fichier est introuvable, veuillez réessayer.';
		$verify->redirect($_SERVER["HTTP_REFERER"]);
		exit;
	}

	else if(!move_uploaded_file($_FILES['fichier']['tmp_name'], $dossier_upload . $nom_fichier))
	{
		$_SESSION['error_message']='Impossible de copier le fichier dans le dossier de destination.';
		$verify->redirect($_SERVER["HTTP_REFERER"]);
		exit;
	}

	$_SESSION['success_message']='Le fichier a été mis en ligne !';//si tout s'est bien passé

	clearstatcache();//PEUT ETRE A ENLEVER A LA FIN

	$verify->redirect($_SERVER["HTTP_REFERER"]);
	exit;
}
?>