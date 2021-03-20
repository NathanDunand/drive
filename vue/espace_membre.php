<?php
session_start();
include ('../functions/functions.php');

$utilisateur=new functions();
$connect=new functions();
$autorisation=new functions();
$contenu=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}

if($_GET['id']==$_SESSION['utilisateur_id'])//si on est sur son propre compte on est redirigé vers son espace perso
{
	header('Location: mon_espace.php');
	exit;
}

include ('header.php');

$id=$_SESSION['utilisateur_id'];//valeur par défaut

if(isset($_GET['id']) && $autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si c'est l'admin qui visite un compte && qu'il a les droits
{
	$id=$_GET['id'];
	$ajouter_document='';
	$modifier_information='<a class="btn btn-info" href="../vue/update_utilisateur.php?id='.$id.'">Modifier les informations</a>';
	$modifier_mdp='<a href="changer_mdp.php?id='.$_GET['id'].'" class="btn btn-info">Changer le mot de passe</a>';
	if($autorisation->is_poste($_GET['id'], 'super-administrateur'))//si on est sur la page du super admin
	{
		$modifier_information='';
		$modifier_mdp='';	}
		$deconnexion='';
	}
else//si il n'a pas les droits
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	header('Location: index.php');
	exit;
}

?>
<h1>Informations de ce compte</h1>
<?php
foreach ($utilisateur->obtenir_information_utilisateur_par_id($id) as $information) {
	echo 'id : '.$information['id'].'<br>';
	echo 'nom : '.$information['nom'].'<br>';
	?><a class="btn btn-secondary" data-toggle="collapse" href="#collapseListe" role="button" aria-expanded="false" aria-controls="collapseExample">Liste(s)</a>
	<div class="collapse" id="collapseListe">
		<div class="card card-body">
			<ul class="list-group list-group-flush"><?php
			$information['poste']=explode('|', $information['poste']);
			$information['poste']=array_filter($information['poste']);
			foreach ($information['poste'] as $one)
			{
				if($one=='super-administrateur')
				{
					echo $one;
					break;
				}
				?><li class="list-group-item"><?php
				echo $utilisateur->obtenir_nom_categories_utilisateurs_par_id($one);
				?></li><?php
			}
			?></ul>
		</div><!--end card-body-->
	</div><!--end collapse-->
	<?php
	echo 'mail : '.$information['mail'].'<br>';
}
echo $ajouter_document;
echo $modifier_information;
echo $modifier_mdp;
echo $deconnexion;
include ('footer.php');
?>