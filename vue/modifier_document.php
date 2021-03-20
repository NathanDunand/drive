<?php
session_start();
include ('../functions/functions.php');

$utilisateur=new functions();
$autorisation=new functions();
$categories=new functions();
if(!$autorisation->is_connected())
{
	header('Location: index.php');
	exit();
}

if(!$autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
{
	header('Location: index.php');
	exit();
}

include ('header.php');

foreach ($utilisateur->obtenir_information_document_par_id($_GET['id']) as $information)
	{ ?>
		<form method="post" action="../scripts/modifier_document.php?id=<?php echo $_GET['id'] ?>">
			<div class="row">
				<div class="col-12 col-sm-6 col-lg-3">
					Nom
					<input type="text" class="form-control" name="libelle" placeholder="Nom" value="<?php echo $information['libelle'] ?>" required>
				</div>
				<div class="col-12 col-sm-6 col-lg-3">
					Date du document
					<input type="date" class="form-control" name="date_document" value="<?php echo $information['date_document'] ?>" required>
				</div>
				<!-- <div class="col-12 col-sm-6 col-lg-3">
					Catégories
					<select class="form-control" name="categorie[]" multiple="">
						<?php foreach ($categories->lister_categories() as $categorie => $info){
							if(stristr($information['categorie'], $info['id'])){$selected='selected';}
							else{$selected='';}
							echo '<option value="'.$info['id'].'" '.$selected.'>'.$info['nom'].'</option>';
						} ?>
					</select>
				</div> -->

				<div class="col-12 col-sm-6 col-lg-3">
						Les sous-catégories de ce document : <br>
						<select class="form-control" name="sous_categories[]" multiple="" required="">
							<option value="Actualité">Actualité</option>
							<?php
							foreach ($connect->lister_sous_categories() as $sous_cat)
							{
								if(stristr($information['sous_categories'], $sous_cat['id']))
								{
									echo '<option value="'.$sous_cat['id'].'" selected>'.$sous_cat['nom'].'</option>';
								}
								else
								{
									echo '<option value="'.$sous_cat['id'].'">'.$sous_cat['nom'].'</option>';
								}
							}
							?>
						</select>
				</div>
				<div class="col-12 col-sm-6 col-lg-3"><label for="notification">Notifier les utilisateurs qui auront accès à ce document</label><input id="notification" type="checkbox" name="notification"></div>
				<div class="col-12 col-sm-6 col-lg-3">
					<label for="collapse" class="mt-3">Ajouter une date de validité au document</label>
					<input name="collapse" id="collapse" type="checkbox" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" <?php if($information['date_validite']!=null){echo 'checked' ;} ?>>
				</div>
				<div class="col-12 col-sm-6 col-lg-3 collapse <?php if($information['date_validite']!=null){echo 'show' ;} ?>" id="collapseExample">
					<div class="card card-body">
						Date jusqu'à laquelle le fichier sera visible<input id="date_validite" type="date" class="form-control" name="date_validite" value="<?php echo $information['date_validite']; ?>">
					</div>
				</div>
				<div class="col-12 mt-3 mb-3">
					<input type="submit" class="btn btn-success btn-block" name="submit" value="Modifier les informations">
				</div>
			</div>
		</form>
		<script type="text/javascript">//permet de vider le champ date si on clique sur la checkbox
		var element = document.getElementById('collapse');
		element.addEventListener('click', function() {document.getElementById("date_validite").value = "";});
	</script>
<?php }
?>
</div>
<?php
include ('footer.php');
?>