<div class="row justify-content-center">
	<div class="col-auto mt-3 mb-3"><?php $contenu->get_content_part('h2', '', 'accueil_favoris') ?></div>
</div>
<?php
$id=$_SESSION['utilisateur_id'];//valeur par défaut
foreach ($utilisateur->obtenir_favoris_utilisateur_par_id($id) as $info)
{	
	?>
	<div class="ligne"><?php
	foreach ($utilisateur->obtenir_information_document_par_id($info['id_document']) as $information)
	{
		?><div class="row tr entete navbar navbar-expand-lg ">
			<div class="col-2"><form method="post" action="../scripts/supprimer_favoris.php"><?php
			echo '<input type="hidden" name="id_favori" value="'.$info['id'].'">
			<p><input class="btn btn-warning" type="submit" value="&#10005;"></p></form></div>';
			echo '<div class="col-5"><p><button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$information['libelle'].'</button></p></div>';
			echo '<div class="col-5">'.$information['categorie'].'</div>';
			?></div><!--end row-->

			<div class="navbar navbar-expand-lg"><?php
			echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
			?><div class="row">

				<div class="col-6 col-lg-3 order-lg-1 th">Date doc.</div><?php
				echo '<div class="col-6 col-lg-3 order-lg-5 td">'.$information['date_document'].'</div>';
				foreach($utilisateur->obtenir_information_utilisateur_par_id($information['id_utilisateur']) as $key)
				{
					?><div class="col-6 col-lg-3 order-lg-2 th">Auteur</div><?php
						if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier un compte
						{
							echo '<div class="col-6 col-lg-3 order-lg-6 td"><a href="espace_membre.php?id='.$information['id_utilisateur'].'">'.$key['nom'].'</a></div>';
						}
						else
						{
							echo '<div class="col-6 col-lg-3 order-lg-7 td">'.$key['nom'].'</div>';
						}
					}
					?><div class="col-6 col-lg-3 order-lg-3 th">Dépôt</div><?php
					echo '<div class="col-6 col-lg-3 order-lg-8 td">'.$information['date_depot'].'</div>';
					?><div class="col-6 col-lg-3 order-lg-4 th">Validité</div><?php
					echo '<div class="col-6 col-lg-3 order-lg-9 td">'.$information['date_validite'].'</div>';
				}
				?></div><!--end row-->
			</div><!--end collapse-->
		</div><!--end navbar expand-->

		<div class="row navbar navbar-expand-lg"><?php
		echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
		?><div class="col-12">
			<div class="row justify-content-center"><?php
			echo '<div class="col-auto"><a class="btn btn-secondary content-download" target="_blank" href="'.$information['emplacement'].'">&darr;</a></div>';
			?></div><!--end row justify content center-->
		</div><!--end col-12-->
	</div><!--end collapse-->
</div><!--end navbar expand-->

</div><!--end ligne-->
<script type="text/javascript">
	if (window.matchMedia("(min-width: 992px)").matches)
	{
		var button = document.getElementById("button-collapse<?php echo $info['id'] ?>");
				button.setAttribute("disabled", "");//désactive le bouton
				button.className +=" button-disabled";//ajoute une class

				var row = document.getElementById("navbarSupportedContent<?php echo $info['id'] ?>");
				row.className ="aligne";//modifie la class de l'élément pour que les divs soient alignés
			}
			else
			{}
	</script>
	<?php
}
?>