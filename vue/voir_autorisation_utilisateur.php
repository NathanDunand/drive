<?php
session_start();
include ('../functions/functions.php');
$autorisation=new functions();
$utilisateur=new functions();
if($_SESSION['utilisateur_id']==$_GET['id'])//on ne peut pas modifier ses propres informations
{
	$_SESSION['error_message']='Vous ne pouvez pas modifier vos autorisations.';
	header('Location: espace_membre.php');
	exit();
}
if(!$autorisation->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connecter pour accéder à cette page.';
	header('Location: index.php');
	exit();
}
if($utilisateur->is_poste($_GET['id'], 'super-administrateur'))
{
	$_SESSION['error_message']='A quoi ça sert de voir les autorisations du super-administrateur ? Il les a toutes !';
	header('Location: administration.php');
	exit();
}

include ('header.php');
?>
<h1>Autorisations de ce compte</h1>

<table>
	<tr>
		<th>Créer un compte</th>
		<th>Modifier un compte</th>
		<th>Supprimer un compte</th>
		<th>Ajouter un document</th>
		<th>Modifier un document</th>
		<th>Supprimer un document</th>
	</tr>
	<form method="post" action="../scripts/modifier_autorisation.php?id=<?php echo $_GET['id'] ?>">
		<?php
		foreach ($utilisateur->obtenir_autorisations_utilisateur_par_id($_GET['id']) as $information)
		{
			?>
			<tr>
				<td><input type="checkbox" name="creer_compte" <?php if($information['creer_compte']=='oui'){echo 'checked';} ?>></td>
				<td><input type="checkbox" name="modifier_compte" <?php if($information['modifier_compte']=='oui'){echo 'checked';} ?>></td>
				<td><input type="checkbox" name="supprimer_compte" <?php if($information['supprimer_compte']=='oui'){echo 'checked';} ?>></td>
				<td><input type="checkbox" name="ajout_document" <?php if($information['ajout_document']=='oui'){echo 'checked';} ?>></td>
				<td><input type="checkbox" name="modifier_document" <?php if($information['modifier_document']=='oui'){echo 'checked';} ?>></td>
				<td><input type="checkbox" name="supprimer_document" <?php if($information['supprimer_document']=='oui'){echo 'checked';} ?>></td>
			</tr>
			<?php
		}
		?>
		<input type="submit" name="submit" value="Modifier les autorisations">
	</form>
</table>
<?php
include ('footer.php');
?>