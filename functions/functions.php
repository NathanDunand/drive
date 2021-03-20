<?php
ob_start();
setlocale(LC_TIME, "fr_FR", "French");//définit l'affichage des dates en français
include('config.php');//inclu la configuration entre autre de la bdd
//print_r($query->errorInfo());

/*DEFINITION DES VARIABLES GLOBALES*/
$configuration=new functions();

/** Racine pour les redirections PHP*/
define('ROOT_HTTP', 'http://nathandunand.fr/mairie_smb/vue/');

/** Mail pour le formulaire d'envoie*/
define('MAIL_MESSAGE', $configuration->get_configuration('MAIL_MESSAGE'));

/** Sujet des mails envoyés pour notifier lors d'un ajout d'un document*/
define('OBJET_NOTIFICATION', $configuration->get_configuration('OBJET_NOTIFICATION'));

/** Message des mails envoyés pour notifier lors d'un ajout d'un document*/
define('MESSAGE_NOTIFICATION', $configuration->get_configuration('MESSAGE_NOTIFICATION'));

define('X_DERNIERS_DOCUMENTS_FIL_ACTU', $configuration->get_configuration('X_DERNIERS_DOCUMENTS_FIL_ACTU'));

class functions //fichier du controller
{
	public function redirect($adresse)
	{
		header('location: '.ROOT_HTTP.''.$adresse.'');
		if(stristr($adresse, 'http')!=FALSE)//si jamais on doit passer une adresse web entière par cette fonction ex : $_SERVER["HTTP_REFERER"]
		{
			header('location: '.$adresse.'');
		}
		else
		{
			header('location: '.ROOT_HTTP.''.$adresse.'');
		}
		
		return true;
	}
	
	function __construct()
	{

	}

	public function get_titre_site()
	{
		include ('bdd_connect.php');

		foreach ($bdd->query('SELECT texte from contenu WHERE nom="titre_site"') as $return)
		{
			echo $return['texte'];
		}
		return true;
	}

	public function lister_dossier($dossier)
	{
		$dir=new functions();
		$fichiers=scandir('../'.$dossier);

		foreach ($fichiers as $fichier)
		{
			if(!($fichier=='.'||$fichier=='..'))
			{
				if(is_file($fichier))
				{
					$return[]=$fichier;
				}
			}
		}
		return $return;
	}

	public function import_codemirror_links()
	{
		echo '<!--faire l\'éditeur de code source : https://codemirror.net/-->
		<script src="js/codemirror/lib/codemirror.js"></script>
		<link rel="stylesheet" href="js/codemirror/lib/codemirror.css">
		<script src="js/codemirror/mode/css/css.js"></script><!--mode qui permet de mettre en forme le css-->
		<link rel="stylesheet" href="js/codemirror/theme/base16-dark.css"><!--thème sombre-->';
		return true;
	}

	public function import_codemirror_initialisation()
	{
		echo '<script>
		var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
			lineNumbers: true,
			styleActiveLine: true,
			matchBrackets: true,
			theme: "base16-dark"
			});
			</script>';
			return true;
		}

		public function get_configuration($nom)
		{
			include ('bdd_connect.php');

			$user=new functions();
			$configuration= new functions();
			if(!isset($_SESSION['utilisateur_id']))
			{
				return false;
				exit;
			}
		if(!$user->is_connected())//si pas connecté
		{
			return false;
			exit;
		}
		if(!$user->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))// si pas super admin
		{
			return false;
			exit;
		}
		foreach ($bdd->query('SELECT * from configuration WHERE nom="'.$nom.'"') as $config)
		{
			return $config['valeur'];
		}
	}

	public function unset_messages()
	{
		$_SESSION['error_message']='';
		$_SESSION['success_message']='';
		return true;
	}

	public function unset_search()
	{
		unset($_SESSION['search']);
		return true;
	}

	public function verify_entry($string)//on neutralise les éventuels scripts malveillants
	{
		if(is_array($string))
		{
			foreach ($string as $one)
			{
				$return=htmlspecialchars(addslashes($one));
			}
		}
		else
		{
			$return=htmlspecialchars(addslashes($string));
		}
		
		return $return;
	}

	public function get_style_part($nom, $colonne)
	{
		include ('bdd_connect.php');

		foreach ($bdd->query('SELECT * from style WHERE nom="'.$nom.'"') as $return)
		{
			echo $return[$colonne];
		}
		return true;
	}

	public function document_vue($id_document)
	{
		include ('bdd_connect.php');

		$query = "UPDATE documents SET vue='oui' WHERE id=".$id_document."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		//print_r($stmt->errorInfo());
		return true;
	}

	public function modifier_style_par_id($valeur, $id)
	{
		include ('bdd_connect.php');

		$query = "UPDATE style SET valeur='".$valeur."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		//print_r($stmt->errorInfo());
		return true;
	}

	public function get_content_part($balise, $class, $nom)
	{
		include ('bdd_connect.php');
		$user=new functions();

		if($user->is_connected())//s'il est co
		{
			if(isset($_SESSION['utilisateur_id']) && $user->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//si c'est le super admin
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<div class="'.$return['class'].'" onmouseover="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\'btn btn-info button-collapse button-modify-display-block\'" onmouseout="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\' btn btn-info button-collapse button-modify-display-none\'">';
					echo '<'.$balise.' class="'.$class.'"'.' href="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
					
					echo '<button id="button-collapse-contenu'.$return['id'].'" class="btn btn-info button-collapse button-modify-display-none" type="button" data-toggle="collapse" data-target="#collapse-contenu'.$return['id'].'" aria-controls="collapse" aria-expandelg"false" aria-label="Toggle navigation"><img src="../vue/vue_part/images/pen.png"></button>';

					echo '<div class="collapse" id="collapse-contenu'.$return['id'].'">';
					?>
					<a target="_blank" href="aide_html.php" class="btn btn-info">?</a>
					<form method="post" action="../scripts/modifier_contenu?id=<?php echo $return['id'] ?>">
						<textarea class="input-modify-content form-control <?php echo $class ?>" onfocusout="this.form.submit()" type="text" name="texte"><?php echo $return['texte'];?></textarea>
					</form>

				</div><!--end collapse-->
			</div>
			<script type="text/javascript">
				if (window.matchMedia("(max-width: 992px)").matches)
				{
					var button2=document.getElementById("button-collapse-contenu<?php echo $return['id'] ?>").className="btn btn-info button-collapse button-modify-display-block";
				}
				else
				{}
		</script>
		<?php
	}
}
else
{
	if($balise=='')
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo $return['texte'];
		}
	}
	else
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo '<'.$balise.' class="'.$class.'"'.' href="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
		}
	}
}
}
		else//s'il n'est pas co
		{
			if($balise=='')
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo $return['texte'];
				}
			}
			else
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<'.$balise.' class="'.$class.'"'.'>'.$return['texte'].'</'.$balise.'>';
				}
			}
		}

		return true;
	}

	public function get_footer_liste($balise, $class, $nom)
	{
		include ('bdd_connect.php');

		$groupe=$nom;//plus lisible
		$user=new functions();
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'" AND groupe="'.$groupe.'"') as $return)://pour chaque compartiment du footer
			if($user->is_connected())//s'il est co
			{
				if(isset($_SESSION['utilisateur_id']) && $user->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//si c'est le super admin
				{
					
					echo '<div class="'.$return['class'].'" onmouseover="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\'btn btn-info button-collapse button-modify-display-block\'" onmouseout="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\' btn btn-info button-collapse d-none\'">';
					?><div class="col-auto"><?php
					if(file_exists('../vue/'.$return['href']))
					{
						echo '<a href="'.$return['texte'].'" target="_blank">';
						echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'"></'.$balise.'>';
					}
					
					?></a>
					</div><?php

					echo '<button id="button-collapse-contenu'.$return['id'].'" class="btn btn-info button-collapse d-none" type="button" data-toggle="collapse" data-target="#collapse-contenu'.$return['id'].'" aria-controls="collapse" aria-expandelg"false" aria-label="Toggle navigation"><img src="../vue/vue_part/images/pen.png"></button>';

					echo '<div class="collapse" id="collapse-contenu'.$return['id'].'">';
					?>
					<form method="post" enctype="multipart/form-data" action="../scripts/modifier_contenu?id=<?php echo $return['id'] ?>">
						<input type="file" name="fichier" required>
						<input type="hidden" name="MAX_FILE_SIZE" value="4000000">
						<input type="submit" class="btn btn-success" name="upload" value="Modifier l'image">
					</form>
					<form method="post" enctype="multipart/form-data" action="../scripts/modifier_contenu?id=<?php echo $return['id'] ?>">
						<input class="input-modify-content form-control" onfocusout="this.form.submit()" type="text" name="texte" value="<?php echo $return['texte'] ?>">
						<input type="submit" class="btn btn-success" name="upload" value="Modifier le lien">
					</form>
					<form method="post" action="../scripts/supprimer_contenu?id=<?php echo $return['id'] ?>">
						<div class="col-1 col-sm-2"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer<?php echo $return['id'] ?>">Supprimer</button></div>
						<div class="col-3 d-sm-none"></div>

						<div class="modal fade" id="supprimer<?php echo $return['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="supprimer<?php echo $return['id'] ?>" aria-hidden="true">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLabel">Supprimer un contenu</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										Vous allez supprimer ce contenu, êtes-vous sûr ?
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
										<a class="btn btn-danger" href="../scripts/supprimer_contenu.php?id=<?php echo $return['id'] ?>">Supprimer</a>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div><!--end collapse-->
			</div>
			<script type="text/javascript">
				if (window.matchMedia("(max-width: 992px)").matches)
				{
					var button2=document.getElementById("button-collapse-contenu<?php echo $return['id'] ?>").className="btn btn-info button-collapse button-modify-display-block";
				}
				else
				{}
		</script>
		<?php
	}
	else
	{
		if(file_exists('../vue/'.$return['href']))
		{
			if($balise=='')
			{						
				echo $return['texte'];
			}
			else
			{
				?><div class="col-auto"><?php
				echo '<a href="'.$return['texte'].'" target="_blank">';
				echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'"></'.$balise.'>';
				?></a>
				</div><?php
			}
		}
	}
}
			else//s'il n'est pas co
			{
				if($balise=='')
				{
					echo $return['texte'];
				}
				else
				{
					?><div class="col-auto"><?php
					echo '<a href="'.$return['texte'].'" target="_blank">';
					echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'"></'.$balise.'>';
					?></a>
					</div><?php
				}
			}
		endforeach;

		return true;
	}

	public function get_source($nom)
	{
		include ('bdd_connect.php');
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo $return['href'];
		}
		return true;
	}

	public function get_content_part_banniere($balise, $class, $nom)
	{
		include ('bdd_connect.php');
		$user=new functions();
		if($user->is_connected())//s'il est co
		{
			if(isset($_SESSION['utilisateur_id']) && $user->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//si c'est le super admin
			{ 
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<div class="'.$return['class'].'" onmouseover="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\'btn btn-info button-collapse button-modify-display-block\'" onmouseout="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\' btn btn-info button-collapse button-modify-display-none\'">';
					echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'"></'.$balise.'>';
					
					echo '<button id="button-collapse-contenu'.$return['id'].'" class="btn btn-info button-collapse button-modify-display-none" type="button" data-toggle="collapse" data-target="#collapse-contenu'.$return['id'].'" aria-controls="collapse" aria-expandelg"false" aria-label="Toggle navigation"><img src="../vue/vue_part/images/pen.png"></button>';

					echo '<div class="collapse" id="collapse-contenu'.$return['id'].'">';
					?>
					<form method="post" enctype="multipart/form-data" action="../scripts/modifier_contenu?id=<?php echo $return['id'] ?>">
						<input type="file" name="fichier" size="30" required>
						<input type="hidden" name="MAX_FILE_SIZE" value="4000000"><br>
						<input type="submit" class="btn btn-success" name="upload" value="Modifier l'image">
					</form>
					<form method="post" action="../scripts/modifier_style?id=<?php $user->get_style_part('placement-banner', 'id') ?>">
						Placement de l'image en %, 100% cible la zone haute, 0% la zone basse de l'image<input type="number" name="couleur" value="<?php $user->get_style_part('placement-banner', 'valeur') ?>">
						<input type="submit" class="btn btn-success" name="Modifier le placement">
					</form>
				</div><!--end collapse-->
			</div>
			<script type="text/javascript">
				if (window.matchMedia("(max-width: 992px)").matches)
				{
					var button2=document.getElementById("button-collapse-contenu<?php echo $return['id'] ?>").className="btn btn-info button-collapse button-modify-display-block";
				}
				else
				{}
		</script>
		<?php
	}
}
else
{
	if($balise=='')
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo $return['texte'];
		}
	}
	else
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'"></'.$balise.'>';
		}
	}
}
}
		else//s'il n'est pas co
		{
			if($balise=='')
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo $return['texte'];
				}
			}
			else
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
				}
			}
		}

		return true;
	}

	public function get_content_part_image($balise, $class, $nom)
	{
		include ('bdd_connect.php');
		$user=new functions();

		if($user->is_connected())//s'il est co
		{
			if(isset($_SESSION['utilisateur_id']) && $user->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))//si c'est le super admin
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<div class="'.$return['class'].'" onmouseover="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\'btn btn-info button-collapse button-modify-display-block\'" onmouseout="document.getElementById(\'button-collapse-contenu'.$return['id'].'\').className=\' btn btn-info button-collapse button-modify-display-none\'">';
					echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
					
					echo '<button id="button-collapse-contenu'.$return['id'].'" class="btn btn-info button-collapse button-modify-display-none" type="button" data-toggle="collapse" data-target="#collapse-contenu'.$return['id'].'" aria-controls="collapse" aria-expandelg"false" aria-label="Toggle navigation"><img src="../vue/vue_part/images/pen.png"></button>';

					echo '<div class="collapse" id="collapse-contenu'.$return['id'].'">';
					?>
					<form method="post" enctype="multipart/form-data" action="../scripts/modifier_contenu?id=<?php echo $return['id'] ?>">
						<input type="file" name="fichier" size="30" required>
						<input type="hidden" name="MAX_FILE_SIZE" value="4000000"><br>
						<input type="submit" class="btn btn-success" name="upload" value="Valider">
					</form>
				</div><!--end collapse-->
			</div>
			<script type="text/javascript">
				if (window.matchMedia("(max-width: 992px)").matches)
				{
					var button2=document.getElementById("button-collapse-contenu<?php echo $return['id'] ?>").className="btn btn-info button-collapse button-modify-display-block";
				}
				else
				{}
		</script>
		<?php
	}
}
else
{
	if($balise=='')
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo $return['texte'];
		}
	}
	else
	{
		foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
		{
			echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
		}
	}
}
}
		else//s'il n'est pas co
		{
			if($balise=='')
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo $return['texte'];
				}
			}
			else
			{
				foreach ($bdd->query('SELECT * from contenu WHERE nom="'.$nom.'"') as $return)
				{
					echo '<'.$balise.' class="'.$class.'"'.' src="'.$return['href'].'">'.$return['texte'].'</'.$balise.'>';
				}
			}
		}

		return true;
	}

	public function lister_auteurs()
	{
		include ('bdd_connect.php');

		$utilisateur=new functions();
		foreach($bdd->query('SELECT DISTINCT id_utilisateur from documents') as $id_utilisateur)
		{
			foreach ($utilisateur->obtenir_information_utilisateur_par_id($id_utilisateur['id_utilisateur']) as $key)
			{
				$array[$id_utilisateur['id_utilisateur']]=$key['nom'];
			}
		}
		return $array;
	}

	public function modifier_contenu_par_id($id, $texte)
	{
		include ('bdd_connect.php');

		$query = "UPDATE contenu SET texte='".$texte."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		echo $query;
		//print_r($stmt->errorInfo());
		return true;
	}

	public function supprimer_contenu_par_id($id)
	{
		include ('bdd_connect.php');

		$contenu=new functions();
		foreach($bdd->query('SELECT href from contenu WHERE id='.$id.'') as $ligne)
		{
			unlink('../vue/'.$ligne['href']);//supprime le fichier
		}
		return ;
	}

	public function modifier_configuration_par_nom($valeur, $nom)
	{
		include ('bdd_connect.php');

		$query = "UPDATE configuration SET valeur='".$valeur."' WHERE nom='".$nom."'";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		echo $query;
		print_r($stmt->errorInfo());
		return true;
	}

	public function ajouter_favori($id_utilisateur, $id_document)
	{
		include ('bdd_connect.php');

		$query=$bdd->prepare("INSERT INTO `favoris` (`id_utilisateur`, `id_document`) VALUES (:id_utilisateur, :id_document);");
		$query->execute(array(
			"id_utilisateur"=>$id_utilisateur,
			"id_document"=>$id_document
		));
		//print_r($query->errorInfo());
		return true;
	}

	public function obtenir_favoris_utilisateur_par_id($id)
	{
		include ('bdd_connect.php');

		return $bdd->query('SELECT * from favoris WHERE id_utilisateur='.$id.'');
	}

	public function afficher_utilisateurs()
	{
		include ('bdd_connect.php');

		$utilisateurs=array();

		foreach ($bdd->query('SELECT * from utilisateurs') as $row) {
			$utilisateurs[] = $row['nom'];
		}

		return $utilisateurs;
	}

	public function obtenir_id_utilisateur($login, $password)
	{
		include ('bdd_connect.php');

		$utilisateur_id=array();
		//on obtient l'id utilisateur à partir de ses logs
		foreach ($bdd->query('SELECT id from utilisateurs WHERE mail="'.$login.'" AND password="'.$password.'"') as $row) {
			$utilisateur_id=$row['id'];
		}

		return $utilisateur_id;
	}

	public function obtenir_poste_utilisateur($id_utilisateur)
	{
		include ('bdd_connect.php');

		$utilisateur_poste=array();
		//on obtient l'id utilisateur à partir de ses logs
		foreach ($bdd->query('SELECT poste from utilisateurs WHERE id='.$id_utilisateur.'') as $row) {
			$utilisateur_poste=$row['poste'];
		}

		return $utilisateur_poste;
	}

	public function verification_connexion($login, $password)
	{
		include ('bdd_connect.php');

		$resultat=$bdd->query('SELECT COUNT(*) as nombre from utilisateurs WHERE mail="'.$login.'" AND password="'.$password.'" AND visible="oui"');//on vérifie si l'utilisateur est présent dans la bdd avec ses logs de connexion.

		$donnees=$resultat->fetch();
		if($donnees['nombre']!=0)//s'il y a une personne qui correspond dans la bdd
		{
			$_SESSION['connecte']=true;//l'utilisateur est connecté
			$_SESSION['error_message']='';// il n'y a pas d'erreur

			$id_utilisateur=new functions();
			$_SESSION['utilisateur_id']=$id_utilisateur->obtenir_id_utilisateur($login, $password);//on met en session l'id de l'utilisateur

			return true;
			exit;
		}
		else
		{
			$_SESSION['connecte']=false;//l'utilisateur n'est pas connecté
			$_SESSION['error_message']='Identifiant ou mot de passe incorrect.';
			return false;
			exit;
		}

	}

	public function obtenir_information_utilisateur_par_id($id)
	{
		include ('bdd_connect.php');
		return $bdd->query('SELECT * from utilisateurs WHERE id='.$id.'');
	}

	public function obtenir_information_categorie_par_id($id)
	{
		include ('bdd_connect.php');
		return $bdd->query('SELECT * from categories WHERE id='.$id.'');
	}

	public function obtenir_information_sous_categorie_par_id($id)
	{
		include ('bdd_connect.php');
		return $bdd->query('SELECT * from sous_categories WHERE id='.$id.'');
	}

	public function obtenir_information_categories_utilisateurs_par_id($id)
	{
		include ('bdd_connect.php');
		return $bdd->query('SELECT * from categories_utilisateurs WHERE id='.$id.'');
	}

	public function obtenir_information_document_par_id($id)
	{
		include ('bdd_connect.php');
		return $bdd->query('SELECT * from documents WHERE id='.$id.'');
	}

	public function ajouter_utilisateur($nom, $poste, $mail, $password)
	{
		include ('bdd_connect.php');

		$query=$bdd->prepare("INSERT INTO `utilisateurs` (`nom`, `poste`, `mail`, `password`) VALUES (:nom, :poste, :mail, :password);");
		$query->execute(array(
			"nom"=>$nom,
			"poste"=>$poste,
			"mail"=>$mail,
			"password"=>$password,
		));
		//print_r($query->errorInfo());
		$query2=$bdd->prepare("INSERT INTO `autorisations` (`id_utilisateur`, `creer_compte`, `modifier_compte`, `supprimer_compte`, `ajout_document`, `modifier_document`, `supprimer_document`, `ajouter_categorie`, `modifier_categorie`, `supprimer_categorie`, `notifier_utilisateur`) VALUES (:id_utilisateur, :creer_compte, :modifier_compte, :supprimer_compte, :ajout_document, :modifier_document, :supprimer_document, :ajouter_categorie, :modifier_categorie, :supprimer_categorie, :notifier_utilisateur);");

		$max_id=new functions();
		$id_utilisateur=$max_id->get_max_id('utilisateurs');//obtenir l'id de l'utilisateur actif

		$query2->execute(array(
			"id_utilisateur"=>$id_utilisateur,
			"creer_compte"=>'non',
			"modifier_compte"=>'non',
			"supprimer_compte"=>'non',
			"ajout_document"=>'non',
			"modifier_document"=>'non',
			"supprimer_document"=>'non',
			"ajouter_categorie"=>'non',
			"modifier_categorie"=>'non',
			"supprimer_categorie"=>'non',
			"notifier_utilisateur"=>'non'
		));
		
		//print_r($query2->errorInfo());
		return true;
	}

	public function modifier_utilisateur_par_id($id, $nom, $poste, $mail)
	{
		include ('bdd_connect.php');

		$query = "UPDATE utilisateurs SET nom='".$nom."', poste='".$poste."', mail='".$mail."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function modifier_mdp_par_id($id, $password)
	{
		include ('bdd_connect.php');

		$query = "UPDATE utilisateurs SET password='".$password."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function modifier_sous_categorie_par_id_document($id_document, $sous_categories)
	{
		include ('bdd_connect.php');

		$sous_categories=implode('|', $sous_categories).'|';//obligé de faire ça pour ensuite traiter les chaines de caractères des sous catégories

		$query="UPDATE documents SET sous_categories='".$sous_categories."' WHERE id='".$id_document."'";
		$stmt=$bdd->prepare($query);
		$stmt->execute();
		print_r($stmt->errorInfo());
		return true;
	}

	public function modifier_categorie_utilisateur_par_id($id, $nom)
	{
		include ('bdd_connect.php');

		$query = "UPDATE categories_utilisateurs SET nom='".$nom."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function supprimer_categorie_utilisateur_par_id($id)
	{
		include ('bdd_connect.php');

		$query = "UPDATE categories_utilisateurs SET visible='non' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function modifier_categorie_par_id($id, $nom, $id_categories_utilisateurs_notifications)
	{
		include ('bdd_connect.php');

		$categorie_ancien=new functions();
		$nom_categorie_ancien=$categorie_ancien->obtenir_nom_categorie_par_id($id);

		//modification en cascade sur la table documents
		$query2 = "UPDATE documents SET categorie='".$nom."' WHERE categorie='".$nom_categorie_ancien."'";
		$stmt2 = $bdd->prepare($query2);
		$stmt2->execute();
		//print_r($query2);

		//modification du nom de la catéggorie
		$query = "UPDATE categories SET nom='".$nom."', id_categories_utilisateurs_notifications='".$id_categories_utilisateurs_notifications."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function modifier_sous_categorie_par_id($id, $nom, $id_categorie_mere, $id_categories_utilisateurs_notifications, $position)
	{
		include ('bdd_connect.php');
		$function=new functions();
		//$ancien_id=$function->();

		//modification du nom de la catéggorie
		$query = "UPDATE sous_categories SET nom='".$nom."', id_categorie='".$id_categorie_mere."', id_categories_utilisateurs_notifications='".$id_categories_utilisateurs_notifications."', position='".$position."'  WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		//print_r($query);
		$_SESSION['success_message']='Les modifications ont été prises en compte.';

		//$id_categories_utilisateurs_notifications=explode('|', $id_categories_utilisateurs_notifications);
		//$id_categories_utilisateurs_notifications=array_filter($id_categories_utilisateurs_notifications);
		// foreach ($id_categories_utilisateurs_notifications as $one)
		// {
		$query2="UPDATE documents SET visible_par ='super-administrateur|".$id_categories_utilisateurs_notifications."' WHERE sous_categories LIKE '%".$id."%'";
			//$query2 = "SELECT * FROM documents WHERE visible_par LIKE '%".$one."%'";
		$stmt2 = $bdd->prepare($query2);
		$stmt2->execute();
		//}
		return true;
	}

	public function supprimer_categorie_par_id($id)
	{
		include ('bdd_connect.php');

		$categorie_ancien=new functions();
		$nom_categorie_ancien=$categorie_ancien->obtenir_nom_categorie_par_id($id);

		//modification en cascade sur la table documents
		$query2 = "UPDATE documents SET categorie='' WHERE categorie='".$nom_categorie_ancien."'";
		$stmt2 = $bdd->prepare($query2);
		$stmt2->execute();
		//print_r($query2);

		//modification en cascade sur la table sous_categories
		$query2 = "UPDATE sous_categories SET visible='non' WHERE id_categorie=".$id."";
		$stmt2 = $bdd->prepare($query2);
		$stmt2->execute();
		//print_r($query2);

		//modification du nom de la catéggorie
		$query = "UPDATE categories SET visible='non' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function supprimer_sous_categorie_par_id($id)
	{
		include ('bdd_connect.php');

		$categorie_ancien=new functions();
		$nom_categorie_ancien=$categorie_ancien->obtenir_nom_sous_categorie_par_id($id);

		//modification en cascade sur la table documents
		$query2 = "UPDATE documents SET sous_categorie='' WHERE sous_categorie='".$nom_categorie_ancien."'";
		$stmt2 = $bdd->prepare($query2);
		$stmt2->execute();
		//print_r($query2);

		//modification du nom de la catéggorie
		$query = "UPDATE sous_categories SET visible='non' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function obtenir_noms_categories_par_ids($id)
	{
		include ('bdd_connect.php');

		if(strlen($id)>4)//s'il y a plus d'une seule catégorie pour ce doc
		{

			$id=explode('|', $id);
			$id=array_filter($id);
			foreach ($id as $one)
			{
				$sth = $bdd->prepare("SELECT nom FROM categories WHERE id LIKE '%".$one."%'");
				$sth->execute();
				$result[]=$sth->fetchAll(PDO::FETCH_ASSOC);
			}
			foreach ($result as $array)//$result contient des sous tableaux, d'où les deux foreach imbriqués
			{
				foreach ($array as $one)
				{
					return $one['nom'].' ';
				}
			}
		}
		else
		{
			$id=explode('|', $id);
			$id=array_filter($id);
			$id=implode('', $id);
			
			$sth = $bdd->prepare("SELECT nom FROM categories WHERE id LIKE '%".$id."%'");
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value)
			{
				return $value['nom'];
			}
		}		
	}

	public function obtenir_nom_categorie_par_id($id)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT nom FROM categories WHERE id=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $key => $value)
		{
			return $value['nom'];
		}
	}

	public function obtenir_categorie_par_id_document($id)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT categorie FROM documents WHERE id=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $key => $value)
		{
			return $value['categorie'];
		}
	}

	public function obtenir_id_categorie_par_nom($nom)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT id FROM categories WHERE nom='".$nom."'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $key => $value)
		{
			return $value['id'];
		}
	}

	public function obtenir_nom_sous_categorie_par_id($id)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT nom FROM sous_categories WHERE id=".$id." AND visible='oui'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result as $key => $value) {
			return $value['nom'];
		}
	}

	public function obtenir_nom_categories_utilisateurs_par_id($id)
	{
		include ('bdd_connect.php');

		$id=explode('|', $id);
		$id=array_filter($id);

		if(count($id)>1)
		{
			return;
		}

		foreach ($id as $one)
		{
			$sth = $bdd->prepare("SELECT nom FROM categories_utilisateurs WHERE id=".$one." AND visible='oui'");
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
			foreach ($result as $key => $value) {
				return $value['nom'];
			}
		}
		
	}

	public function modifier_autorisations_par_id_utilisateur($id, $creer_compte, $modifier_compte, $supprimer_compte, $ajout_document, $modifier_document, $supprimer_document, $ajouter_categorie, $modifier_categorie)
	{
		include ('bdd_connect.php');

		$query = "UPDATE autorisations SET creer_compte='".$creer_compte."', modifier_compte='".$modifier_compte."', supprimer_compte='".$supprimer_compte."', ajout_document='".$ajout_document."', modifier_document='".$modifier_document."', supprimer_document='".$supprimer_document."', ajouter_categorie='".$ajouter_categorie."', modifier_categorie='".$modifier_categorie."' WHERE id_utilisateur=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		//print_r($stmt->errorInfo());
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		return true;
	}

	public function modifier_notification_par_id($id, $notification)
	{
		include ('bdd_connect.php');

		$query = "UPDATE utilisateurs SET notification='".$notification."' WHERE id=".$id."";
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		print_r($stmt->errorInfo());
		return true;
	}

	public function get_max_id($table)
	{
		include ('bdd_connect.php');

		$return=$bdd->query('SELECT MAX(id) FROM '.$table.'');
		foreach ($return as $id) {
			return $id[0];
		}
		return true;
	}

	public function ajouter_document($nom, $libelle, $emplacement, $date_document, $id_utilisateur, $date_depot, $categorie, $sous_categories, $date_validite)
	{
		include ('bdd_connect.php');

		if($date_validite!='')
		{
			$query=$bdd->prepare("INSERT INTO `documents` (`nom`, `libelle`, `emplacement`, `date_document`, `id_utilisateur`, `date_depot`, `categorie`, `sous_categories`, `date_validite`, visible) VALUES (:nom, :libelle, :emplacement, :date_document, :id_utilisateur, :date_depot, :categorie, :sous_categories, :date_validite, :visible);");
			$query->execute(array(
				"nom"=>$nom,
				"libelle"=>$libelle,
				"emplacement"=>$emplacement,
				"date_document"=>$date_document,
				"id_utilisateur"=>$id_utilisateur,
				"date_depot"=>$date_depot,
				"categorie"=>$categorie,
				"sous_categories"=>$sous_categories,
				"date_validite"=>$date_validite,
				"visible"=>"oui"//par défaut le document est visible
				
			));
			return true;
		}
		else
		{
			$query=$bdd->prepare("INSERT INTO `documents` (`nom`, `libelle`, `emplacement`, `date_document`, `id_utilisateur`, `date_depot`, `categorie`, `sous_categories`, visible) VALUES (:nom, :libelle, :emplacement, :date_document, :id_utilisateur, :date_depot, :categorie, :sous_categories, :visible);");
			$query->execute(array(
				"nom"=>$nom,
				"libelle"=>$libelle,
				"emplacement"=>$emplacement,
				"date_document"=>$date_document,
				"id_utilisateur"=>$id_utilisateur,
				"date_depot"=>$date_depot,
				"categorie"=>$categorie,
				"sous_categories"=>$sous_categories,
				"visible"=>"oui"//par défaut le document est visible
			));
			return true;
		}
		//print_r($query->errorInfo());
		return true;
	}

	public function ajouter_categorie($nom)
	{
		include ('bdd_connect.php');

		$query=$bdd->prepare("INSERT INTO `categories` (`nom`) VALUES (:nom);");
		$query->execute(array(
			"nom"=>$nom
		));
		//print_r($query->errorInfo());
		return true;
	}

	public function ajouter_categorie_utilisateur($nom)
	{
		include ('bdd_connect.php');

		$query=$bdd->prepare("INSERT INTO `categories_utilisateurs` (`nom`) VALUES (:nom);");
		$query->execute(array(
			"nom"=>$nom
		));
		//print_r($query->errorInfo());
		return true;
	}

	public function ajouter_sous_categorie($id_categorie, $nom)//$id_categorie est l'id de la catégorie mère
	{
		include ('bdd_connect.php');

		$query=$bdd->prepare("INSERT INTO `sous_categories` (`id_categorie`, `nom`) VALUES (:id_categorie, :nom);");
		$query->execute(array(
			"id_categorie"=>$id_categorie,
			"nom"=>$nom
		));
		//print_r($query->errorInfo());
		return true;
	}

	public function lister_categories()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from categories WHERE visible='oui'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_categories_utilisateurs()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from categories_utilisateurs WHERE visible='oui'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_categories_par_id_document($id_document)//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT categorie, categorie2 from documents WHERE id=".$id_document."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_sous_categories()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from sous_categories WHERE visible='oui'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_arborescence_categories()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from categories WHERE visible='oui'");
		$sth->execute();
		$categories = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($categories as $key => $value)
		{
			$sth = $bdd->prepare("SELECT * from sous_categories WHERE visible='oui'");
			$sth->execute();
			$sous_categories[] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		print_r($sous_categories);
		return $result;
	}

	public function lister_sous_categories_par_id_document($id_document)//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT sous_categories from documents WHERE id='".$id_document."'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_sous_categories_par_id_categorie($id_categorie)//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from sous_categories WHERE visible='oui' AND id_categorie='".$id_categorie."' ORDER BY position");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function obtenir_categorie_par_id_sous_categorie($id_sous_categorie)//fonction propre
	{
		include ('bdd_connect.php');
		$function=new functions();

		$sth = $bdd->prepare("SELECT * from sous_categories WHERE visible='oui' AND id='".$id_sous_categorie."'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function obtenir_utilisateurs_par_id_categorie($id)
	{
		include ('bdd_connect.php');
		$function=new functions();

		$sth = $bdd->prepare("SELECT id, nom, poste, mail FROM utilisateurs WHERE visible='oui' AND poste LIKE '%".$id."%'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function lister_documents()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_document($id_document, $id_utilisateur)//fonction propre
	{
		include ('bdd_connect.php');
		
		$function=new functions();
		foreach($function->obtenir_information_utilisateur_par_id($id_utilisateur) as $one)
		{
			$id_utilisateur=$one['poste'];
		}
		$id_utilisateur=explode('|', $id_utilisateur);
		$id_utilisateur=array_filter($id_utilisateur);
		foreach ($id_utilisateur as $one)
		{
			$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND id='".$id_document."' AND visible_par LIKE '%".$one."%'");
			$sth->execute();
			$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		$result=array_filter($result);
		return $result;
	}

	public function unique_multidim_array($array, $key) {
		$temp_array = array();
		$i = 0;
		$key_array = array();

		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$i] = $val[$key];
				$temp_array[$i] = $val;
			}
			$i++;
		}
		return $temp_array;
	} 

	public function lister_documents_par_categorie($categorie, $limit, $id_utilisateur)//fonction propre
	{
		include ('bdd_connect.php');

		$id=$id_utilisateur;
		$function=new functions();
		foreach($function->obtenir_information_utilisateur_par_id($id_utilisateur) as $one)
		{
			$id_utilisateur=$one['poste'];
		}
		if(strlen($id_utilisateur)>3 && !$function->is_poste($id, 'super-administrateur'))//si l'utilisateur a plusieurs postes
		{
			$id_utilisateur=explode('|', $id_utilisateur);
			$id_utilisateur=array_filter($id_utilisateur);
			foreach ($id_utilisateur as $one)
			{
				if($limit=='')
				{
					if($function->is_autorisation($id, 'ajout_document')||$categorie=='000')
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." ORDER BY libelle");
					}
					else
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." AND visible_par LIKE '%".$one."%' ORDER BY libelle");
					}
					$sth->execute();
					$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
					break;
				}
				else
				{
					if($function->is_autorisation($id, 'ajout_document')||$categorie=='000')
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." ORDER BY libelle LIMIT ".$limit."");
					}
					else
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." AND visible_par LIKE '%".$one."%' ORDER BY libelle LIMIT ".$limit."");
					}
					$sth->execute();
					$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		}
		else
		{
			if($limit=='')
			{
				if($function->is_autorisation($id, 'ajout_document')||$categorie=='000')
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." ORDER BY libelle");
				}
				else
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." AND visible_par LIKE '%".$id_utilisateur."%' ORDER BY libelle");
				}
			}

			else
			{
				if($function->is_autorisation($id, 'ajout_document')||$categorie=='000')
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." ORDER BY libelle LIMIT ".$limit."");
				}
				else
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND categorie=".$categorie." AND visible_par LIKE '%".$id_utilisateur."%' ORDER BY libelle LIMIT ".$limit."");
				}
			}
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		$result=array_filter($result);
		return $result;
	}

	public function lister_documents_par_sous_categorie($sous_categories, $limit, $id_utilisateur)//fonction propre
	{
		include ('bdd_connect.php');

		$function=new functions();
		foreach($function->obtenir_information_utilisateur_par_id($id_utilisateur) as $one)
		{
			$poste=$one['poste'];
		}

		if(strlen($poste)>3)//si l'utilisateur a plusieurs postes
		{
			$poste=explode('|', $poste);
			$poste=array_filter($poste);
			foreach ($poste as $one)
			{
				if($limit=='')
				{
					if($function->is_autorisation($id_utilisateur, 'ajout_document'))
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' ORDER BY libelle");
					}
					else
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' AND visible_par LIKE '%".$one."%' ORDER BY libelle");
					}
					$sth->execute();
					$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
				}
				else
				{

					if($function->is_autorisation($id_utilisateur, 'ajout_document'))
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' LIMIT ".$limit."");
					}
					else
					{
						$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' LIMIT ".$limit."");
					}
					$sth->execute();
					$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
				}
			}
		}
		else
		{
			if($function->is_autorisation($id_utilisateur, 'ajout_document'))
			{
				if($limit=='')
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%'");
				}
				else
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' LIMIT ".$limit."");
				}
			}
			else
			{
				if($limit=='')
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' AND visible_par LIKE '%".$id_utilisateur."%'");
				}
				else
				{
					$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND sous_categories LIKE '%".$sous_categories."%' AND visible_par LIKE '%".$id_utilisateur."%' LIMIT ".$limit."");
				}
			}
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		$result=array_filter($result);
		return $result;
	}

	public function afficher_documents_par_categorie($categorie, $limit, $id_utilisateur)//fonction propre
	{
		$documents=new functions();
		$array=new functions();
		$autorisation=new functions();
		$utilisateur=new functions();
		foreach ($documents->lister_documents_par_categorie($categorie, $limit, $id_utilisateur) as $document => $info):
		if(file_exists($info['emplacement']))://si le fichier existe
		?>
		<div class="ligne">
			<div class="row tr entete navbar navbar-expand-lg ">
				<div class="col-5 col-sm-3"><?php echo '<button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['libelle'].'</button>';//pas le 'vrai' nom du fichier?></div>
				<div class="col-4 col-sm-5"><?php echo $array->obtenir_nom_categorie_par_id($info['categorie']) ?></div>
				<?php
				echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="voir_doc?id='.$info['id'].'"></a></div>';
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
				{
					echo '<div class="col-4 d-sm-none"></div>';
					echo '<div class="col-2 col-sm-1"><a class="btn btn-secondary content-modify" href="modifier_document.php?id='.$info['id'].'"></a></div>';
				}
				else
				{
					echo '';
				}
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
				{
					echo '<div class="col-2 col-sm-auto"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$info['id'].'">&#10005;</button></div>';
					echo '<div class="col-4 d-sm-none"></div>';

					echo '<div class="modal fade" id="supprimer'.$info['id'].'" tabindex="-1" role="dialog" aria-labelledby="supprimer'.$info['id'].'" aria-hidden="true">
					<div class="modal-dialog" role="document">
					<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Supprimer un utilisateur</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>
					<div class="modal-body">
					Vous allez supprimer le document '.$info['libelle'].'.
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
					<a class="btn btn-danger" href="../scripts/supprimer_document.php?id='.$info['id'].'">Supprimer</a>
					</div>
					</div>
					</div>
					</div>';
				}
				else
				{
					echo '';
				}
				?>
			</div><!--end entete-->
			<?php
			if($info['visible']=='oui')
			{
				?>
				<div class="navbar-expand-lg">
					<?php echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
				}
				else
					{ ?>
						<div class="navbar-expand-lg alert-danger">
							<?php echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
						}
						?><div class="row tr">
							<div class="col-6 col-lg-3 order-lg-1 th">Date doc.</div>
							<?php
							echo '<div class="col-6 col-lg-3 order-lg-7 td">'.strftime("%d/%m/%G", strtotime($info['date_document'])).'</div>';
							?>
							<div class="col-6 col-lg-3 order-lg-3 th">Auteur</div>
							<?php
							echo '<div class="col-6 col-lg-3 order-lg-8 td">';
							foreach($utilisateur->obtenir_information_utilisateur_par_id($info['id_utilisateur']) as $key)
							{
			if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier un compte
			{
				echo '<a href="espace_membre.php?id='.$info['id_utilisateur'].'">'.$key['nom'].'</a>';
			}
			else
			{
				echo $key['nom'];
			}
		}
		?></div><!-- end row tr-->
		<div class="col-6 col-lg-3 order-lg-4 th">Dépôt</div>
		<?php
		echo '<div class="col-6 col-lg-3 order-lg-9 td">'.strftime("%d/%m/%G", strtotime($info['date_depot'])).'</div>';
		?>
		<div class="col-6 col-lg-3 order-lg-5">
			<?php
		if($info['date_validite']!=null)//si il y a une date
		{
			?>Validité</div><?php
			echo '<div class="col-6 col-lg-3 order-lg-10 td">'.strftime("%d/%m/%G", strtotime($info['date_validite'])).'</div>';
		}
		else
		{	
			?></div><?php
			echo '<div class="col-6 col-lg-3 order-lg-10"></div>';
		}
		?>
	</div><!--end row-->
</div><!--end collapse-->
</div><!--end expand-->
<?php
		endif;//ferme le if qui vérifie si le fichier existe
		?>
	</div><!--end ligne-->
	<script type="text/javascript">
		if (window.matchMedia("(min-width: 992px)").matches)
		{
			var button = document.getElementById("button-collapse<?php echo $info['id'] ?>");
			button.setAttribute("disabled", "");//désactive le bouton
			button.className +=" button-disabled";//ajoute une class

			var row = document.getElementById("navbarSupportedContent<?php echo $info['id'] ?>");
			row.className ="aligne";//modifie la class de l'élément pour que les divs soient alignés
		}
		else
		{

		}
	</script>
	<?php
endforeach;//ferme le foreach qui créer le tableau

}
	public function lister_derniers_documents_non_lus($id_utilisateur)//fonction propre
	{
		include ('bdd_connect.php');

		$actualite=new functions();
		
		foreach ($actualite->obtenir_information_utilisateur_par_id($id_utilisateur) as $utilisateur)
		{
			$poste=$utilisateur['poste'];
		}

		if(strlen($poste)>5)//si l'utilisateur a plusieurs poste
		{
			$poste=explode('|', $poste);
			$poste=array_filter($poste);
			
			foreach ($poste as $id)//terminer la gestion des postes utilisateurs quand ceux ci sont multiples
			{
				$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND vue='non' AND NOT categorie='".$actualite->obtenir_id_categorie_par_nom('Actualité')."' AND visible_par LIKE '%".$id."%' ORDER BY libelle, date_depot DESC LIMIT 10");
				$sth->execute();
				$result[] = $sth->fetchAll(PDO::FETCH_ASSOC);
			}
			$result=array_filter($result);
		}
		else
		{
			$sth = $bdd->prepare("SELECT * from documents WHERE visible='oui' AND supprime='non' AND vue='non' AND NOT categorie='".$actualite->obtenir_id_categorie_par_nom('Actualité')."' AND visible_par LIKE '%".$poste."%' ORDER BY libelle, date_depot DESC LIMIT 10");
			$sth->execute();
			$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		}
		return $result;
	}

	public function recherche_documents($search)//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT libelle, emplacement from documents WHERE visible='oui' AND supprime='non' ORDER BY date_depot");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $one => $info)
		{
			similar_text($search, $info['libelle'], $pourcent);
			$ressemblance=$pourcent;
			if($ressemblance>=25)//on filtre les résultats qui ne sont pas intéressants (en dessous de 15% de correspondance)
			{
				$resultats[$info['libelle']]=similar_text($search, $info['libelle']);
				$emplacements[$info['libelle']]=$info['emplacement'];
			}	
		}
		if(empty($resultats)||empty($emplacements))//si le moteur de recherche n'a rien trouvé
		{
			$_SESSION['search']='Je n\'ai rien trouvé pour vous ...';
			$resultats[]='';
			$emplacements[]='';
		}
		arsort($resultats);//on classe les résultats par ordre décroissant, les plus ressemblants en premier
		return $emplacements;
		return $resultats;
	}

	public function recherche_documents_par($id, $table, $utilisateur_id)//fonction propre
	{
		include ('bdd_connect.php');
		$functions=new functions();

		$poste=$functions->obtenir_poste_utilisateur($utilisateur_id);
		foreach ($poste as $one)
		{
			/*TERMINER LA RECHERCHE PAR CATEGORIE EN FONCTION DES AUTORISATIONS DE LINTERNAUTE*/
		}

		$sth = $bdd->prepare("SELECT libelle, emplacement from documents WHERE visible='oui' AND supprime='non' AND ".$table." LIKE '%".$id."%' AND visible_par LIKE '%".$poste."%' ORDER BY date_depot");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $one => $info)
		{
			$emplacements[$info['libelle']]=$info['emplacement'];
		}
		if(empty($emplacements))//si le moteur de recherche n'a rien trouvé
		{
			$_SESSION['search']='Je n\'ai rien trouvé pour vous ...';
			$emplacements[]='';
		}
		var_dump($sth);
		return $emplacements;
	}

	public function lister_tous_documents()//liste même les docs non visible
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from documents where supprime='non'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);//on a tous les résultat
		return $result;
	}

	public function lister_utilisateurs()//fonction propre
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from utilisateurs WHERE visible='oui'");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	public function lister_utilisateurs_where($col, $condition, $col2, $condition2)//fonction propre
	{
		include ('bdd_connect.php');

		if($col2!='' && $condition2!='')
		{
			$sth = $bdd->prepare("SELECT * from utilisateurs WHERE visible='oui' AND ".$col."='".$condition."' AND ".$col2."='".$condition2."'");
		}
		else
		{
			$sth = $bdd->prepare("SELECT * from utilisateurs WHERE visible='oui' AND ".$col."='".$condition."' ");
		}		
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function supprimer_table_par_id($table, $id)//on laisse l'entrée en bdd, on la rend juste invisible
	{
		include ('bdd_connect.php');
		$sth=$bdd->prepare("UPDATE ".$table." SET visible='non' WHERE id=".$id."");
		$sth->execute();
		//print_r($sth->errorInfo());
		return true;
	}

	public function supprimer_favori_par_id_utilisateur($id_document, $id_utilisateur)
	{
		include ('bdd_connect.php');
		$sth=$bdd->prepare("DELETE FROM favoris WHERE id_document=".$id_document." AND id_utilisateur=".$id_utilisateur."");
		$sth->execute();
		print_r($sth->errorInfo());
		return true;
	}

	public function supprimer_favori_par_id($id_favori)
	{
		include ('bdd_connect.php');
		$sth=$bdd->prepare("DELETE FROM favoris WHERE id=".$id_favori."");
		$sth->execute();
		print_r($sth->errorInfo());
		return true;
	}

	public function supprimer_utilisateur_par_id($id)//on laisse l'entrée en bdd, on la rend juste invisible
	{
		include ('bdd_connect.php');
		$sth=$bdd->prepare("UPDATE utilisateurs SET visible='non', notification='non' WHERE id=".$id."");
		$sth->execute();
		//print_r($sth->errorInfo());
		return true;
	}

	public function supprimer_document_par_id($id)//on laisse l'entrée en bdd, on la rend juste invisible
	{
		include ('bdd_connect.php');
		$sth=$bdd->prepare("UPDATE documents SET supprime='oui' WHERE id=".$id."");
		$sth->execute();
		print_r($sth->errorInfo());
		return true;
	}

	public function is_connected()//est-ce que l'utilisateur est connecté
	{
		if(isset($_SESSION['connecte']) && $_SESSION['connecte']==true)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	public function is_poste($id, $poste)//est-ce que l'utilisateur est un ...
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT poste from utilisateurs WHERE id=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if($result[0]['poste']==$poste)
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}

	public function is_autorisation($id, $autorisation)//est-ce que l'utilisateur a l'autorisation ...
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT ".$autorisation." from autorisations WHERE id_utilisateur=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if($result[0][$autorisation]=='oui')//si c'est oui en bdd
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}

	public function is_favori($id_utilisateur, $id)//id du document à tester
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT id_document from favoris WHERE id_document=".$id." AND id_utilisateur=".$id_utilisateur."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		if(!empty($result))//si ce n'est pas vide
		{
			return true;
		}
		else
		{
			return false;
		}
		return false;
	}

	public function obtenir_autorisations_utilisateur_par_id($id)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from autorisations WHERE id_utilisateur=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function obtenir_informations_document_par_id($id)
	{
		include ('bdd_connect.php');

		$sth = $bdd->prepare("SELECT * from documents WHERE id=".$id."");
		$sth->execute();
		var_dump($sth);
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		return $result;
	}

	public function generer_id_sous_categorie($string)
	{
		$return='';
		for($i=0; $i<strlen($string); $i++)
		{	
			if($i%3==0)
			{
				$return.='|'.$string[$i];
			}
			else
			{
				$return.=$string[$i];
			}
		}
		$return=substr($return, 1).'|';
		return $return;
	}

	public function obtenir_tableau_sous_categories($string)
	{
		$function=new functions();

		$array=array();

		for ($i=0; $i<strlen($string); $i++)//lis toute la chaîne
		{
			if($string[$i]!='|')// | est le séparateur
			{
				$array[]=intval($string[$i]);
			}
			else//on peut traduire par tant qu'on lis des chiffres sans séparateurs
			{	
				$array[]=null;
			}
		}
		return $array;
	}

	// public function traiter_tableau_sous_categories($chiffres)
	// {
	// 	$function=new functions();

	// 	for($i=0; $i<count($chiffres); $i++)
	// 	{
	// 		if(!is_null($chiffres[$i]) && !is_null($chiffres[$i+1]) && !is_null($chiffres[$i+2]))
	// 		{
	// 			$result[][$chiffres[$i].$chiffres[$i+1].$chiffres[$i+2]]=$function->obtenir_nom_sous_categorie_par_id($chiffres[$i].$chiffres[$i+1].$chiffres[$i+2]);
	// 		}
	// 	}
	// 	var_dump($result);
	// 	return $result;
	// }

	public function traiter_tableau_sous_categories($chiffres)
	{
		$function=new functions();

		for($i=0; $i<count($chiffres); $i++)
		{
			if(!is_null($chiffres[$i]) && !is_null($chiffres[$i+1]) && !is_null($chiffres[$i+2]))
			{
				$result[]=$chiffres[$i].$chiffres[$i+1].$chiffres[$i+2];
			}
		}
		return $result;
	}

	public function compter_nombre_sous_categories_par_id_document($id)
	{
		include('bdd_connect.php');

		$sth = $bdd->prepare("SELECT sous_categories from documents WHERE id=".$id."");
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);

		foreach ($result as $one)
		{
			foreach ($one as $chaine)
			{
				return substr_count($chaine, '|');//on compte le nombre d'occurence du séparateur pour en déduire le nombre de sous cat
			}
		}
		return false;
	}

	public function countSQL($sql)
	{
		include('bdd_connect.php');
		$array=new functions();

		$sth = $bdd->prepare("SELECT COUNT(*)".$sql);
		$sth->execute();
		$result = $sth->fetchAll(PDO::FETCH_ASSOC);
		echo "SELECT COUNT(*)".$sql;
		return $result[0]['COUNT(*)'];
	}

	public function traiter_sous_categories_par_id_document($id_document, $sinfo_id)
	{
		$categories=new functions();

		foreach($categories->lister_sous_categories_par_id_document($id_document) as $categorie => $data)
		{
			foreach($a=$categories->traiter_tableau_sous_categories($categories->obtenir_tableau_sous_categories($data['sous_categories'])) as $result)
			{
				return $result;
				// foreach ($result as $nom_sous_cat)
				// {
				// 	echo $nom_sous_cat;
				// }
			}
		}
		return true;
	}

	// public function traiter_sous_categories_par_id_document($id_document, $information_sous_categories, $sinfo_id)
	// {
	// 	$categories=new functions();

	// 	foreach($categories->lister_sous_categories_par_id_document($id_document) as $categorie => $data)
	// 	{
	// 		<!-- <select name="sous_categorie" class="form-control">
	// 			<option value="">Aucune</option> -->
	// 		foreach($a=$categories->traiter_tableau_sous_categories($categories->obtenir_tableau_sous_categories($data['sous_categories'])) as $result)
	// 		{
	// 			if($information_sous_categories==$sinfo_id){$selected='selected';}
	// 			else{$selected='';}
	// 			echo '<option value="'.key($result).' '.$selected.'">';
	// 			foreach ($result as $nom_sous_cat)
	// 			{
	// 				echo $nom_sous_cat;
	// 			}
	// 			</option>
	// 		}
	// 		<!-- </select> -->
	// 	}
	// 	return true;
	// }

	public function mise_a_jour_visibilite_document()
	{
		include ('bdd_connect.php');

		$query = $bdd->prepare("UPDATE documents SET visible = REPLACE(visible, 'oui', 'non') where date_validite <= now()");//met à jour la bdd pour la visibilé des documents
		$query->execute();
		$query = $bdd->prepare("UPDATE documents SET visible = REPLACE(visible, 'non', 'oui') where date_validite >= now()");//met à jour la bdd pour la visibilé des documents
		$query->execute();

		if($date_validite=='')
		{
			$query = $bdd->prepare("UPDATE documents SET visible = REPLACE(visible, 'non', 'oui') where date_validite IS NULL");//met à jour la bdd pour la visibilé des documents
			$query->execute();
		}
		return true;
	}

	public function modifier_document_par_id($id, $libelle, $date_document, $categorie, $sous_categories, $date_validite)
	{
		include ('bdd_connect.php');
		$function=new functions();

		$b=$function->obtenir_id_utilisateurs_notifications_par_id_sous_categorie($sous_categories);
		//var_dump($categorie);
		if($date_validite!='')//si on a rentré une nouvelle date de validité
		{
			$query = "UPDATE documents SET libelle='".$libelle."', date_document='".$date_document."', categorie='".$categorie."', sous_categories='".$sous_categories."', date_validite='".$date_validite."', visible='oui', visible_par='super-administrateur|".$b."' WHERE id=".$id."";
		}
		else//si on a enlevé la date de validité pendant la modification
		{
			$query = "UPDATE documents SET libelle='".$libelle."', date_document='".$date_document."', categorie='".$categorie."', sous_categories='".$sous_categories."', date_validite=NULL, visible_par='super-administrateur|".$b."' WHERE id=".$id."";
		}
		$stmt = $bdd->prepare($query);
		$stmt->execute();
		//print_r($stmt->errorInfo());
		$_SESSION['success_message']='Les modifications ont été prises en compte.';
		//print_r($stmt->errorInfo());
		return true;
	}

	public function obtenir_id_utilisateurs_notifications_par_id_sous_categorie($id)
	{
		include ('bdd_connect.php');

		if(strlen($id)>4)
		{
			$id=explode('|', $id);
			$id=array_filter($id);
			foreach($id as $one)
			{
				$one=trim($one, '|');//supprime les | qui bloquent la requête sql
				//FAIRE EN SORTE QUE CETTE FONCTION RETOURNE UN STRING POUR NE PAS QUIL Y AIT DERREUR FATALE A LA LIGNE 1727
				foreach($bdd->query('SELECT id_categories_utilisateurs_notifications from sous_categories WHERE id LIKE "%'.$one.'%"') as $one)
				{
					$result[]=$one;
				}
			}
			foreach($result as $one)
			{
				$return[]=$one['id_categories_utilisateurs_notifications'];
			}
			$return=implode('', $return);//array -> string
			return $return;
		}
		else
		{
			$id=trim($id, '|');//supprime les | qui bloquent la requête sql
			foreach ($bdd->query('SELECT id_categories_utilisateurs_notifications from sous_categories WHERE id LIKE "%'.$id.'%" ') as $one)
			{
				$result=$one;
			}
			return $result['id_categories_utilisateurs_notifications'];
		}
		
	}

	// public function envoie_mail_categories($id_categories)
	// {
	// 	include ('bdd_connect.php');

	// 	$notifies=new functions();

	// 	$sujet=OBJET_NOTIFICATION;
	// 	$message=MESSAGE_NOTIFICATION;

	// 	foreach ($liste_notifications as $data)//pour toutes les catégories notifiées
	// 	{
	// 		foreach($notifies->lister_utilisateurs_where('poste', $data, 'notification', 'oui') as $utilisateur)//liste des utilisateurs qui sont dans cette catégorie
	// 		{
	// 			mail($data, $sujet, $message);
	// 		}
	// 	}
	// 	return true;
	// }

	public function envoie_mail_notifies($liste_notifications, $liste_notifications_particulieres)
	{
		include ('bdd_connect.php');

		$notifies=new functions();

		$mail=MAIL_MESSAGE;
		$sujet=OBJET_NOTIFICATION;
		$message=MESSAGE_NOTIFICATION;

		if($liste_notifications!='')
		{
			foreach ($liste_notifications as $data)//pour toutes les catégories notifiées
			{
				foreach($notifies->lister_utilisateurs_where('poste', $data, 'notification', 'oui') as $utilisateur)//liste des utilisateurs qui sont dans cette catégorie
				{
					//echo $utilisateur['mail'].'<br>';
					mail($utilisateur['mail'], $sujet, $message);
				}
			}
		}
		
		if($liste_notifications_particulieres!='')
		{
			foreach ($liste_notifications_particulieres as $mail)
			{
				//echo $mail.'<br>';
				mail($mail, $sujet, $message);
			}
		}
		
		return true;
	}
}
?>