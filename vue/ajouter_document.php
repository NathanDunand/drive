<?php
session_start();
include ('../functions/functions.php');

$connect=new functions();
$autorisation1=new functions();
$autorisation2=new functions();
$categories=new functions();
if(!$autorisation1->is_connected() || !$autorisation2->is_autorisation($_SESSION['utilisateur_id'], 'ajout_document'))//si on est connecté et qu'on a l'autorisation d'ajouter un document
{
	header('Location: index.php');
	exit();
}
include ('header.php');
?>

<h1>Ajouter un document</h1>
La taille maximale autorisée est de 10 Mo.
<a href="https://pdfcompressor.com/fr/" class="btn btn-info" target="_blank">Compresser un PDF ?</a>
ou bien <a href="https://www.ilovepdf.com/compress_pdf" class="btn btn-info" target="_blank">Compresser un PDF ?</a>
<form method="post" enctype="multipart/form-data" action="../scripts/upload.php">
	<div class="input-group">
		<div class="custom-file">
			<input type="file" class="custom-file-input" id="inputGroupFile02" required="" name="fichier">
			<label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choisissez un fichier</label>
		</div>
	</div>
	<?php

	
	// foreach ($categories->lister_sous_categories() as $sous_categorie => $info)
	// 	{
	// 		var_dump($info);
	// 		echo '<option value="'.$info['id'].'">'.$info['nom'].'</option>';
	// 	} 
	?>
	<input type="hidden" name="MAX_FILE_SIZE" value="10000000">
	Sous-catégorie<a href="#" class="btn btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Vous pouvez sélectionner plusieurs choix,  en maintenant la touche controle (CTRL) enfoncée tout en cliquant sur les différentes sous-catégories.">?</a><br>
	<select name="sous_categorie[]" multiple="" id="liste_sous_categories" required="">
		<option value="Actualité">Actualité</option>
		<?php foreach ($categories->lister_categories() as $sous_categorie => $info)
		{
			if($info['id']!=000)
			{
				echo '<optgroup label="'.$info['nom'].'">';
				foreach ($categories->lister_sous_categories_par_id_categorie($info['id']) as $sous_categorie => $value)
				{
					echo '<option value="'.$value['id'].'">'.$value['nom'].'</option>';
				}
				echo '</optgroup>';
			}
		}  ?>
	</select><br>
	<small>Attention : trois choix maximum sont permis.</small><br>
	Date du document, par défaut la date sera celle de l'ajout du fichier : <input type="date" name="date_document" value="<?php echo date('Y-m-d') ?>" required><br>
	<label for="collapse">Ajouter une date de validité au document</label>
	<input name="collapse" id="collapse" type="checkbox" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><br>
	<div class="collapse" id="collapseExample">
		<div class="card card-body">
			Date jusqu'à laquelle le fichier sera visible<input id="date_validite" type="date" name="date_validite">
		</div>
	</div>
	<br>
	<label for="notification">Notifier des personnes en plus</label>
	<input name="notification" id="notification" type="checkbox" data-toggle="collapse" href="#collapseNotification" role="button" aria-expanded="false" aria-controls="collapseExample"><br>
	<div class="collapse" id="collapseNotification">
		<div class="card card-body">

			<a href="#" class="btn btn-info" data-toggle="tooltip" data-placement="top" data-original-title="Vous pouvez sélectionner plusieurs choix,  en maintenant la touche controle (CTRL) enfoncée tout en cliquant sur différentes catégories.">Aide</a>
			<select id="notificationSelect" name="notification_liste[]" multiple="">
				<?php foreach ($categories->lister_categories_utilisateurs() as $utilisateur => $data) {
					echo '<option value="'.$data['id'].'">'.$data['nom'].'</option>';
				} ?>
			</select><br>
			<small>Attention : trois choix maximum sont permis.</small><br><br>
			Notifier une personne en particulier<br>
			<select id="notificationparticuliereSelect" name="notification_particuliere[]" multiple="">
				<?php foreach ($categories->lister_utilisateurs() as $utilisateur => $data) {
					echo '<option value="'.$data['mail'].'">'.$data['nom'].' ('.$data['mail'].')</option>';
				} ?>
			</select>
			<small>Pas de limite de choix pour cette liste.</small><br>
		</div>
	</div>
	<input type="submit" name="upload" value="Valider">
</form>

<script type="text/javascript">//permet de vider le champ date si on clique sur la checkbox
var element = document.getElementById('collapse');
element.addEventListener('click', function() {document.getElementById("date_validite").value = "";});

var element = document.getElementById('notification');//permet de vider le select notification si on clique sur la checkbox
element.addEventListener('click', function() {document.getElementById("notificationSelect").value = "";});
element.addEventListener('click', function() {document.getElementById("notificationparticuliereSelect").value = "";});
</script>
<?php
include ('footer.php');
?>