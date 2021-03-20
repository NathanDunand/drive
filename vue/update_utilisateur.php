<?php
session_start();
include ('../functions/functions.php');

$utilisateur=new functions();
$autorisation=new functions();

if(!$autorisation->is_connected())//on ne peut pas modifier les informations si on est pas connecté
{
	$_SESSION['error_message']='Connectez-vous pour effectuer cette action.';
	header('Location: index.php');
	exit();
}
if($autorisation->is_poste($_GET['id'], 'super-administrateur'))//on ne peut pas modifier les informations du super admin
{
	$_SESSION['error_message']='Action impossible.';
	header('Location: index.php');
	exit();
}
if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))
{
	$_SESSION['error_message']='Vous n\'avez pas les droits requis pour effectuer cette action.';
	header('Location: index.php');
	exit();
}
include ('header.php');

foreach ($utilisateur->obtenir_information_utilisateur_par_id($_GET['id']) as $information)
	{ ?>
		<form method="post" action="../scripts/update_utilisateur.php">
			<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
			Prénom et Nom
			<input type="text" name="nom" placeholder="Prénom et Nom" value="<?php echo $information['nom'] ?>" required><br>
			Statut <?php echo $utilisateur->obtenir_nom_categories_utilisateurs_par_id($information['poste']) ?>
			<?php 
			if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si on est pas super-administrateur on ne peut pas modifier
			{
				if($_GET['id']==$_SESSION['utilisateur_id'])//on ne peut pas modifier son propre rang
				{
					echo '';
				}
				else
				{
					?><select class="form-control" name="poste[]" required="" multiple=""><?php
					foreach ($connect->lister_categories_utilisateurs() as $cat_utilisateur)
					{
						if(stristr($information['poste'], $cat_utilisateur['id']))
						{
							echo '<option value="'.$cat_utilisateur['id'].'" selected>'.$cat_utilisateur['nom'].'</option>';
						}
						else
						{
							echo '<option value="'.$cat_utilisateur['id'].'">'.$cat_utilisateur['nom'].'</option>';
						}
					}
					?></select><?php
				}
			}
			?>
			Adresse mail
			<input type="mail" name="mail" placeholder="email@exemple.com" value="<?php echo $information['mail'] ?>" required><br>
			<input type="submit" name="submit" value="Modifier les informations">
		</form>
	<?php }

	include ('footer.php');
	?>