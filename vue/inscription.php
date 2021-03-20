<?php
session_start();
include ('../functions/functions.php');
$autorisation1=new functions();
$autorisation2=new functions();

if(!$autorisation1->is_connected() || !$autorisation2->is_autorisation($_SESSION['utilisateur_id'], 'creer_compte'))//si on est connecté ou qu'on a pas l'autorisation de créér un compte
{
	header('Location: index.php');
	exit();
}
include ('header.php');
?>

<div class="row justify-content-center">
	<div class="col-auto">
		<form method="post" action="../scripts/inscription.php">
			Prénom et Nom
			<input type="text" name="nom" placeholder="Prénom et Nom" required><br>
			Statut
			<select class="form-control" name="poste[]" required="" multiple=""><?php
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
			?></select>
			Adresse mail
			<input type="mail" name="mail" placeholder="email@exemple.com" required><br>
			Mot de passe ATTENTION, le mot de passe n'est pas modifiable ensuite
			<input type="password" name="password1" placeholder="mot de passe" required><br>
			Confirmer votre mot de passe
			<input type="password" name="password2" placeholder="confirmer le mot de passe" required><br>
			<input type="submit" name="submit" value="S'inscrire">
		</form>
	</div>
</div>
<?php
echo $_SESSION['error_message'];
include ('footer.php');
?>