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
if(!isset($_GET['id'])){$id=$_SESSION['utilisateur_id'];}//si on est sur sa propre page, la vérification des droits de l'utilisateur est fait dans le script
else{$id=$_GET['id'];}
include ('header.php');
?>
<h1>Gestion des catégories</h1>
<h2>Modifier une catégorie</h2>
<table class="table table-striped table-sm">
	<tbody>
		<?php foreach ($categories->lister_categories() as $categorie => $info)
		{
			if($info['id']!=000)://000=>catégorie actu
			?><tr>
				<td><?php
				echo '<form method="post" action="../scripts/modifier_categorie.php?id='.$info['id'].'">';
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
						<a class="btn btn-danger" href="../scripts/supprimer_categorie.php?id='.$info['id'].'">Supprimer</a>
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
			<?php
			$i=0;
			foreach ($categories->lister_sous_categories_par_id_categorie($info['id']) as $key => $sous_cat)
			{
				$i++;
				?>
				<form method="post" action="../scripts/modifier_sous_categorie.php?id=<?php echo $sous_cat['id'] ?>">
					<div class="row">
						<div class="col-2 ml-5"><input type="text" class="form-control" name="nom" value="<?php echo $sous_cat['nom'] ?>"></div>
						<div class="col-3">
							<select class="form-control" name="categorie_mere">							
								<?php foreach ($categories->lister_categories() as $categorie => $cat)
								{
									if($cat['id']!=000)
									{
										if($cat['id']==$sous_cat['id_categorie'])
										{
											echo '<option value="'.$cat['id'].'" selected>'.$cat['nom'].'</option>';
										}
										else
										{
											echo '<option value="'.$cat['id'].'">'.$cat['nom'].'</option>';
										}
									}
								} ?>
							</select>
						</div><!--end col-->
						<div class="col-3">
							<select class="form-control" name="id_categories_utilisateurs_notifications[]" multiple="">
								<?php
								foreach ($connect->lister_categories_utilisateurs() as $cat_utilisateur)
								{
									if(stristr($sous_cat['id_categories_utilisateurs_notifications'], $cat_utilisateur['id']))
									{
										echo '<option value="'.$cat_utilisateur['id'].'" selected>'.$cat_utilisateur['nom'].'</option>';
									}
									else
									{
										echo '<option value="'.$cat_utilisateur['id'].'">'.$cat_utilisateur['nom'].'</option>';
									}
								}
								?>
							</select>
						</div>
						<div class="col-1">
							<input class="form-control" type="number" name="position" required="" value="<?php echo $sous_cat['position'] ?>">
						</div>
						<div class="col-auto mt-1"><input class="btn btn-success" type="submit" value="Modifier la s.catégorie"></div>
						<div class="col-auto mt-1"><?php
							if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_categorie'))//que le super admin peut supprimer une catégorie
							{
								echo '<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimersouscat'.$sous_cat['id'].'">&#10005;</button>';

								echo '<div class="modal fade" id="supprimersouscat'.$sous_cat['id'].'" tabindex="-1" role="dialog" aria-labelledby="supprimersouscat'.$sous_cat['id'].'" aria-hidden="true">
								<div class="modal-dialog" role="document">
								<div class="modal-content">
								<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Supprimer une sous-catégorie</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
								</div>
								<div class="modal-body">
								Vous allez supprimer la sous-catégorie '.$sous_cat['nom'].'.
								</div>
								<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
								<a class="btn btn-danger" href="../scripts/supprimer_sous_categorie.php?id='.$sous_cat['id'].'">Supprimer</a>
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
						</div><!--end col-->
					</div><!--end row-->
				</form>	
			<?php } ?>
		</td></div><!--end row--></tr>
	<?php endif; }//end foreach
	?>
</tbody>
</table>
<?php
if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'ajouter_categorie'))
{
	?><h2>Ajouter une nouvelle catégorie</h2>
	<form method="post" action="../scripts/ajouter_categorie.php">
		<input class="form-control" type="text" placeholder="Nom de la nouvelle catégorie" name="nom" required>
		<input type="submit" value="Ajouter" class="btn btn-success">
	</form>

	<h2>Ajouter une nouvelle sous-catégorie</h2>
	<form method="post" action="../scripts/ajouter_sous_categorie.php">
		<input class="form-control" type="text" placeholder="Nom de la nouvelle sous-catégorie" name="nom" required>
		Catégorie mère<select name="id_categorie" class="form-control">
			<?php foreach ($categories->lister_categories() as $categorie => $info)
			{
				if($info['id']!=000)
				{
					echo '<option value="'.$info['id'].'">'.$info['nom'].'</option>';
				}				
			} ?>
		</select>
		<input type="submit" value="Ajouter" class="btn btn-success">
		</form><?php
	}

	include ('footer.php');
	?> 