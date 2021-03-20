<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$categories=new functions();
$autorisation=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
include ('header.php');
?>
<h1>Gestion des listes d'utilisateur</h1>
<h2>Modifier une liste</h2>
<table class="table table-striped table-sm">
	<tbody>
		<?php foreach ($categories->lister_categories_utilisateurs() as $categorie => $info)
		{
			?><tr>
				<td><?php
				echo '<form method="post" action="../scripts/modifier_categorie_utilisateur.php?id='.$info['id'].'">';
				?>
				<div class="row">
					<div class="col-9 mt-1"><input type="text" name="nom" class="form-control" value="<?php echo $info['nom'] ?>"></div><?php
					if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_categorie'))
					{
						?><div class="col-auto mt-1"><?php
						echo '<input type="submit" class="btn btn-success" value="Modifier la catégorie">';
						?></div><?php
					}
					?><div class="col-auto mt-1"><?php
					if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_categorie'))
					{
						echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$info['id'].'">&#10005;</button>';

						echo '<div class="modal fade" id="supprimer'.$info['id'].'" tabindex="-1" role="dialog" aria-labelledby="supprimer'.$info['id'].'" aria-hidden="true">
						<div class="modal-dialog" role="document">
						<div class="modal-content">
						<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Supprimer une catégorie</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
						</div>
						<div class="modal-body">
						Vous allez supprimer la catégorie '.$info['nom'].'.
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
						<a class="btn btn-danger" href="../scripts/supprimer_categorie_utilisateur.php?id='.$info['id'].'">Supprimer</a>
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
				</div><!--end col--></div><!--end row-->
			</form>
		</td></div><!--end row--></tr>
	<?php }//end foreach
	?>
</tbody>
</table>
<?php
if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'ajouter_categorie'))
{
	?><h2>Ajouter une nouvelle liste</h2>
	<form method="post" action="../scripts/ajouter_categorie_utilisateur.php">
		<input class="form-control" type="text" placeholder="Nom de la nouvelle liste" name="nom" required>
		<input type="submit" value="Ajouter" class="btn btn-success">
	</form>
	<?php
}
include ('footer.php');
?> 