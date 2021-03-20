<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$style=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
if(!$connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))
{
	header('Location: index.php');
	exit;
}

include ('header.php');
?>
<div class="row justify-content-center">
	<div class="col-auto"><h2>Personnaliser l'apparence du site</h2></div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Couleurs principales du site</h3></div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto">Couleur 1 <div class="badge" style="background-color:<?php $style->get_style_part('couleur1', 'valeur') ?>;"></div>
			<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur1', 'id') ?>">
				<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur1', 'valeur') ?>">
			</form>
		</div>
		<div class="col-auto">Couleur 2 <div class="badge" style="background-color:<?php $style->get_style_part('couleur2', 'valeur') ?>;"></div>
		<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur2', 'id') ?>">
			<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur2', 'valeur') ?>">
		</form>
	</div>
	<div class="col-auto">Couleur 3 <div class="badge" style="background-color:<?php $style->get_style_part('couleur3', 'valeur') ?>;"></div>
	<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur3', 'id') ?>">
		<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur3', 'valeur') ?>">
	</form>
</div>
</div>
</div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Couleurs des tableaux</h3></div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto"><h5>Couleur 1</h5><div class="badge" style="background-color:<?php $style->get_style_part('tableau1', 'valeur') ?>;"></div>
			<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('tableau1', 'id') ?>">
				<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('tableau1', 'valeur') ?>">
			</form>
		</div>
		<div class="col-auto"><h5>Couleur 2</h5><div class="badge" style="background-color:<?php $style->get_style_part('tableau2', 'valeur') ?>;"></div>
		<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('tableau2', 'id') ?>">
			<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('tableau2', 'valeur') ?>">
		</form>
	</div>
</div>
</div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Boutons</h3></div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto"><h5>Suppression</h5><div class="badge" style="background-color:<?php $style->get_style_part('suppression', 'valeur') ?>;"></div>
			<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('suppression', 'id') ?>">
				<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('suppression', 'valeur') ?>">
			</form>
		</div>
		<div class="col-auto"><h5>Suppression au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('suppression-hover', 'valeur') ?>;"></div>
		<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('suppression-hover', 'id') ?>">
			<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('suppression-hover', 'valeur') ?>">
		</form>
	</div>
	<div class="col-auto"><h5>Modification</h5><div class="badge" style="background-color:<?php $style->get_style_part('modification', 'valeur') ?>;"></div>
	<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('modification', 'id') ?>">
		<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('modification', 'valeur') ?>">
	</form>
</div>
<div class="col-auto"><h5>Modification au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('modification-hover', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('modification-hover', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('modification-hover', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Téléchargement</h5><div class="badge" style="background-color:<?php $style->get_style_part('telechargement', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('telechargement', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('telechargement', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Téléchargement au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('telechargement-hover', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('telechargement-hover', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('telechargement-hover', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Boutons classiques</h5><div class="badge" style="background-color:<?php $style->get_style_part('classique', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('classique', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('classique', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Boutons classiques au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('classique-hover', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('classique-hover', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('classique-hover', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Boutons de validation</h5><div class="badge" style="background-color:<?php $style->get_style_part('validation', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('validation', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('validation', 'valeur') ?>">
</form>
</div>
<div class="col-auto"><h5>Boutons de validation au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('validation-hover', 'valeur') ?>;"></div>
<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('validation-hover', 'id') ?>">
	<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('validation-hover', 'valeur') ?>">
</form>
</div>
</div>
</div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Titres</h3></div>
	<div class="col-12">
		<div class="row justify-content-center">
			<div class="col-auto">
				<small>Note : les titres sont répartis en 6 niveaux allant du titre principal h1, sous-titre h2, sous-sous-titre h3 jusqu'à h6.</small>
			</div>
		</div>
	</div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h1', 'valeur') ?>;">H1</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h1', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h1', 'valeur') ?>">
				</form>
				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h1', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h1', 'valeur') ?>">
				</form>
				<!-- <script type="text/javascript">
					function onlyNumber()
					{
						var champ=document.getElementById('number24');
						var valeur=champ.value;
						if(!isNaN(valeur))//si ce n'est pas un nombre
						{
							champ.value=champ.value.replace(/[^0-9]/);
						}	
					}
				</script> -->
			</div>
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h2', 'valeur') ?>;">H2</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h2', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" onkeyup="onlyNumber();" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h2', 'valeur') ?>">
				</form>
				<form id="formulaire" method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h2', 'id') ?>">
					<input id="number" class="form-control" onkeyup="onlyNumber();" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h2', 'valeur') ?>">
				</form>
			</div>
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h3', 'valeur') ?>;">H3</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h3', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h3', 'valeur') ?>">
				</form>
				<form id="formulaire" method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h3', 'id') ?>">
					<input id="number" class="form-control" onkeyup="onlyNumber();" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h3', 'valeur') ?>">
				</form>
			</div>
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h4', 'valeur') ?>;">H4</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h4', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h4', 'valeur') ?>">
				</form>
				<form id="formulaire" method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h4', 'id') ?>">
					<input id="number" class="form-control" onkeyup="onlyNumber();" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h4', 'valeur') ?>">
				</form>
			</div>
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h5', 'valeur') ?>;">H5</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h5', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h5', 'valeur') ?>">
				</form>
				<form id="formulaire" method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h5', 'id') ?>">
					<input id="number" class="form-control" onkeyup="onlyNumber();" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h5', 'valeur') ?>">
				</form>
			</div>
			<div class="col-auto">
				<h5 style="color:<?php $style->get_style_part('couleur-h6', 'valeur') ?>;">H6</h5>

				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('couleur-h6', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('couleur-h6', 'valeur') ?>">
				</form>
				<form id="formulaire" method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('taille-h6', 'id') ?>">
					<input id="number" class="form-control" onkeyup="onlyNumber();" onfocusout="this.form.submit()" maxlength="7" type="number" name="couleur" value="<?php $style->get_style_part('taille-h6', 'valeur') ?>">
				</form>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Menu</h3></div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto"><h5>Onglet</h5><div class="badge" style="background-color:<?php $style->get_style_part('menu-item', 'valeur') ?>;"></div>
				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('menu-item', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('menu-item', 'valeur') ?>">
				</form>
			</div>
			<div class="col-auto"><h5>Onglet au survol</h5><div class="badge" style="background-color:<?php $style->get_style_part('menu-item-hover', 'valeur') ?>;"></div>
				<form method="post" action="../scripts/modifier_style.php?id=<?php $style->get_style_part('menu-item-hover', 'id') ?>">
					<input class="form-control" onfocusout="this.form.submit()" maxlength="7" type="text" name="couleur" value="<?php $style->get_style_part('menu-item-hover', 'valeur') ?>">
				</form>
			</div>
		</div>
	</div>
</div>
<hr>
<div class="row justify-content-center">
	<div class="col-auto"><h3>Image de la page de connexion</h3></div>
</div>
<div class="row mb-3 mt-3">
	<div class="col-12">
		<div class="row justify-content-center mb-3 mt-3">
			<div class="col-auto">
				<?php $contenu->get_content_part_image('img', 'vignette mx-auto d-block', 'administration_connexion_image') ?>
			</div>
		</div>
	</div>
</div>
<?php include ('footer.php');