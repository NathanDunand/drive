<?php
session_start();
include ('../functions/functions.php');

$connect=new functions();
$contenu=new functions();
$categories=new functions();
$auteurs=new functions();

if(!$connect->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	$connect->redirect('connexion.php');
	exit;
}

include ('header.php');
?>
<!-- formulaires de recherches rapides -->
<div class="row">
	<div class="col-12">	
		<div class="row zone-search">
			<div class="col">Rechercher par nom de documents
				<form method="post" action="../scripts/search.php">
					<div class="col-12">
						<input id="search" class="form-control" type="search" name="search" placeholder="Votre recherche ..." onfocusout="this.form.submit();" required>
					</div>
				</form>
			</div>
			<div class="col">Rechercher par catégorie
				<form method="post" action="../scripts/search.php">
					<select class="form-control" name="categorie">
						<option>Sélectionnez une catégorie</option>
						<?php 
						foreach ($categories->lister_categories() as $categorie => $info) {
							echo '<option onclick="this.form.submit();" class="form-control" value="'.$info['id'].'">'.$info['nom'].'</option>';
						}
						/*foreach ($categories->lister_categories() as $sous_categorie => $info)
						{
							if($info['id']!=000)
							{
								echo '<optgroup label="'.$info['nom'].'">';
								foreach ($categories->lister_sous_categories_par_id_categorie($info['id']) as $sous_categorie => $value)
								{
									echo '<option onclick="this.form.submit();" class="form-control" value="'.$value['id'].'">'.$value['nom'].'</option>';
								}
								echo '</optgroup>';
							}
						}*/
						?>
					</select>
				</form>				
			</div>
			<div class="col">Rechercher par auteur
				<form method="post" action="../scripts/search.php">
					<select class="form-control" name="auteur">
						<option>Sélectionnez un auteur</option>
						<?php
						foreach ($auteurs->lister_auteurs() as $auteur => $key) {
							echo '<option onclick="this.form.submit();" value="'.$auteur.'">'.$key.'</option>';
						} ?>
					</select>
				</form>
			</div>
		</div>			
	</div>
</div>
<?php
if($connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))
{
	?>
	<div class="row justify-content-center mt-2 mb-5">
		<div class="col-auto"  data-toggle="tooltip" data-placement="top" title="Cette vignette est visible uniquement en mode super-administrateur."><?php $connect->get_content_part_banniere('img', 'vignette', 'banniere_site') ?></div>
	</div>
	<?php
}
?>
<div class="encart-banner"></div>
<div class="col-12">
	<div class="row justify-content-center">
		<div class="col-auto"><?php $contenu->get_content_part_image('img', 'mx-auto d-block logo', 'accueil_logo') ?></div>
	</div>
</div>
</div>
<div class="row">
	<div class="col"><!--endroit où sont affichés les résultats-->
		<?php
		if(isset($_SESSION['search']))
		{
			?><div class="alert alert-secondary alert-dismissible fade show" role="alert">
				Voilà ce que j'ai trouvé pour vous : <br><br><strong><?php
				foreach ($_SESSION['search'] as $one => $emplacement)
				{

					if($one=='')
					{
						echo "absolument rien";
						break;
					}
					else
					{
						echo '<a href="'.$emplacement.'" target="_blank">'.$one.'</a><br>';
					}

				}
				?></strong>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				</div><?php
			}

			?>
		</div>
	</div>
	<div class="row justify-content-center">
		<div class="col-auto text"><?php $contenu->get_content_part('p', 'textarea text-justify', 'accueil_text') ?></div>
	</div>
	<?php 

	if(!$connect->is_connected())
	{
		echo "Connectez-vous pour avoir accès au document";
		echo "<a class='btn btn-info' href='connexion.php'>Se connecter</a>";
	}
	else//si l'utilisateur est connecté
	{
		include ('vue_part/document.php');
		// include ('vue_part/favoris.php');
	}
	?>
	<div class="row justify-content-center">
		<div class="col-auto"><?php $contenu->get_content_part('h2', '', 'accueil_titre_actualite') ?></div>
	</div>
	<?php if($connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur')): ?>
		<form method="post" action="../scripts/modifier_configuration.php">
			Nombre de documents à afficher dans le fil d'actualité : <input type="number" name="X_DERNIERS_DOCUMENTS_FIL_ACTU" value="<?php echo X_DERNIERS_DOCUMENTS_FIL_ACTU; ?>">
			<input type="submit" name="" value="Valider" class="btn btn-success">
		</form>
		<?php
	endif;
	foreach ($documents=$connect->lister_documents_par_categorie(000, X_DERNIERS_DOCUMENTS_FIL_ACTU, $_SESSION['utilisateur_id']) as $document => $info)
	{
	if(isset($info[0]))://si c'est un tableau multidimensionnel -> que l'utilisateur appartient à plusieurs sous catégories
	foreach ($info as $one):
		?><div class="ligne">
			<div class="row tr entete navbar navbar-expand-lg ">
				<?php
				echo '<div class="col-5 col-sm-3"><button id="button-collapse-partbis'.$one['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContentPart'.$one['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$one['libelle'].'</button></div>';
				echo '<div class="col-4 col-sm-5">';
				$one['categorie']=explode('|', $one['categorie']);
				$one['categorie']=array_filter($one['categorie']);
				foreach ($one['categorie'] as $id)
				{
					echo $utilisateur->obtenir_nom_categorie_par_id($id).' ';
				}
				echo '</div>';
				echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="voir_doc?id='.$one['id'].'">&darr;</a></div>';
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
				{
					echo '<div class="col-4 d-sm-none"></div>';
					echo '<div class="col-2 col-sm-1"><a class="btn btn-secondary content-modify" href="modifier_document.php?id='.$one['id'].'"></a></div>';
				}
				else
				{
					echo '';
				}
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
				{
					echo '<div class="col-2 col-sm-auto"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$one['id'].'">&#10005;</button></div>';
					echo '<div class="col-4 d-sm-none"></div>';

					echo '<div class="modal fade" id="supprimer'.$one['id'].'" tabindex="-1" role="dialog" aria-labelledby="supprimer'.$one['id'].'" aria-hidden="true">
					<div class="modal-dialog" role="document">
					<div class="modal-content">
					<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Supprimer un utilisateur</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
					</button>
					</div>
					<div class="modal-body">
					Vous allez supprimer le document '.$one['libelle'].'.
					</div>
					<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
					<a class="btn btn-danger" href="../scripts/supprimer_document.php?id='.$one['id'].'">Supprimer</a>
					</div>
					</div>
					</div>
					</div>'; 
				}
				?></div><!--end row-->

				<div class="navbar-expand-lg"><?php
				echo '<div class="collapse navbar-collapse" id="navbarSupportedContentPart'.$one['id'].'">';
				?><div class="row tr">
					<div class="col-6 col-lg-3 order-lg-1 th">Dépôt</div><?php
					echo '<div class="col-6 col-lg-3 order-lg-5 td">'.strftime("%d/%m/%G", strtotime($one['date_depot'])).'</div>';

					?><div class="col-6 col-lg-3 order-lg-2 th">Auteur</div>
					<div class="col-6 col-lg-3 order-lg-6 td"><?php
					foreach($utilisateur->obtenir_information_utilisateur_par_id($one['id_utilisateur']) as $key)
					{
							if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier un compte
							{
								echo '<a href="espace_membre.php?id='.$one['id_utilisateur'].'">'.$key['nom'].'</a>';
							}
							else
							{
								echo $key['nom'];
							}
						}

						?></div>
						<div class="col-6 col-lg-3 order-lg-3 th">Mise en ligne</div><?php
						echo '<div class="col-6 col-lg-3 order-lg-7 td">'.strftime("%d/%m/%G", strtotime($one['date_document'])).'</div>';

	if($one['date_validite']!=null)//si il y a une date
	{
		?><div class="col-6 col-lg-2 order-lg-4 th">Validité</div><?php
		echo '<div class="col-6 col-lg-2 order-lg-8 td">'.strftime("%d/%m/%G", strtotime($one['date_validite'])).'</div>';
	}
	else
	{
		?><div class="col-6 col-lg-2 order-lg-4"></div>
		<div class="col-6 col-lg-2 order-lg-8"></div><?php
	}
	?></div><!--end row-->
</div><!--end collapse-->
</div><!--end navbar expand-->


</div><!--end ligne-->
<script type="text/javascript">
	if (window.matchMedia("(min-width: 992px)").matches)
	{
		var button = document.getElementById("button-collapse-partbis<?php echo $one['id'] ?>");
			button.setAttribute("disabled", "");//désactive le bouton
			button.className +=" button-disabled";//ajoute une class

			var row = document.getElementById("navbarSupportedContent<?php echo $one['id'] ?>");
			row.className ="aligne";//modifie la class de l'élément pour que les divs soient alignés
		}
		else
		{

		}
	</script>
<?php endforeach;
else:
	?><div class="ligne">
		<div class="row tr entete navbar navbar-expand-lg ">
			<?php
			echo '<div class="col-5 col-sm-5 col-lg-5"><button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['libelle'].'</button></div>';
			echo '<div class="col-3 col-sm-2 x">'.$array->obtenir_noms_categories_par_ids($info['categorie']).'</div>';
	// echo '<div class="col-4 col-sm-3"><div class="btn button-disabled">'.$info['categorie'].'</div></div>';

			echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="voir_doc?id='.$info['id'].'">&darr;</a></div>';
			if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
			{
				echo '<div class="col-3 d-sm-none"></div>';
				echo '<div class="col-1 col-sm-1"><a class="btn btn-secondary content-modify" href="modifier_document.php?id='.$info['id'].'"></a></div>';
			}
			else
			{
				echo '';
			}
			if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
			{
				echo '<div class="col-1 col-sm-2"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$info['id'].'">&#10005;</button></div>';
				echo '<div class="col-3 d-sm-none"></div>';

				echo '<div class="modal fade" id="supprimer'.$info['id'].'" tabindex="-1" role="dialog" aria-labelledby="supprimer'.$info['id'].'" aria-hidden="true">
				<div class="modal-dialog" role="document">
				<div class="modal-content">
				<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Supprimer un utilisateur</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
				</div>
				<div class="modal-body">
				Vous allez supprimer le document '.$info['libelle'].'.
				</div>
				<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
				<a class="btn btn-danger" href="../scripts/supprimer_document.php?id='.$info['id'].'">Supprimer</a>
				</div>
				</div>
				</div>
				</div>';
			}
			else
			{
				echo '';
			}

			?></div><!--end row-->

			<div class="navbar-expand-lg"><?php
			echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
			?><div class="row tr">
				<div class="col-6 col-lg-3 order-lg-1 th">Dépôt</div><?php
				echo '<div class="col-6 col-lg-3 order-lg-5 td">'.strftime("%d/%m/%G", strtotime($info['date_depot'])).'</div>';

				?><div class="col-6 col-lg-3 order-lg-2 th">Auteur</div>
				<div class="col-6 col-lg-3 order-lg-6 td"><?php
				foreach($utilisateur->obtenir_information_utilisateur_par_id($info['id_utilisateur']) as $key)
				{
		if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier un compte
		{
			echo '<a href="espace_membre.php?id='.$info['id_utilisateur'].'">'.$key['nom'].'</a>';
		}
		else
		{
			echo $key['nom'];
		}
	}
	?></div>
	<div class="col-6 col-lg-3 order-lg-3 th">Date doc.</div><?php
	echo '<div class="col-6 col-lg-3 order-lg-7 td">'.strftime("%d/%m/%G", strtotime($info['date_document'])).'</div>';

	if($info['date_validite']!=null)//si il y a une date
	{
		?><div class="col-6 col-lg-2 order-lg-4 th">Validité</div><?php
		echo '<div class="col-6 col-lg-2 order-lg-8 td">'.strftime("%d/%m/%G", strtotime($info['date_validite'])).'</div>';
	}
	else
	{
		?><div class="col-6 col-lg-2 order-lg-4"></div>
		<div class="col-6 col-lg-2 order-lg-8"></div><?php
	}
	?></div><!--end row-->
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
		{

		}
	</script>
	<?php
endif;
}//end foreach
	//$connect->afficher_documents_par_categorie(X_DERNIERS_DOCUMENTS_FIL_ACTU);
include ('footer.php');
?>