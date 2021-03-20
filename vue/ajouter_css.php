<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
include ('header.php');
?>
<form method="post" action="../scripts/generer_css.php">
	<div class="row">
		<div class="text">
			<div class="col-12">
				<p>Ecrivez du CSS dans la console afin de personnaliser votre site. Appuyez ensuite sur "Ajouter le CSS", les nouvelles règles ont été ajoutées au site.<br>
				Note : cette feuille de style prendra le dessus sur toutes les autres.<br>
					<a href="https://developer.mozilla.org/fr/docs/Web/CSS/Reference" target="_blank">La documentation CSS Mozilla</a></p>
			</div>
		</div>
		<div class="col-12">
			<?php $connect->import_codemirror_links() ?>				
			<textarea id="code" name="code" class="mt-3 form-control" placeholder="Ecrivez vos règles de CSS ici" required>
				<?php include('bootstrap/css/css_additionnel.css') ?>
			</textarea>
		</div>
		<?php $connect->import_codemirror_initialisation() ?>
	</div>
	<div class="col-12">
		<input type="submit" class="btn btn-danger btn-lg btn-block mt-3 mb-3" value="Ajouter le CSS">
	</div>
</form>
<?php include ('footer.php');