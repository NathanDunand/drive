<!--SESSION INTEGREES FUNCTIONS INTEGREES-->
<?php
$array=new functions();
$utilisateur=new functions();
$autorisation=new functions();
$documents=$array->lister_derniers_documents_non_lus($_SESSION['utilisateur_id']);
$favori=new functions();
?>
<div class="row justify-content-center">
	<div class="col-auto mt-3 mb-3"><?php $contenu->get_content_part('h2', '', 'accueil_titre') ?></div>
</div>
<div class="row justify-content-center">
	<?php if(count($documents)!=0): ?>
		<div class="col-auto"><h2>(<?php $count=0; foreach ($documents as $document)//compte le nombre de tour
		{
			foreach ($document as $one)
			{
				$count++;
			}	
		} echo $count ?>)</h2></div>
		
		<?php
		else: ?><div class="col-auto"><h5><i>Vous n'avez pas de document non lu</i></h5></div><?php
		endif;
		?></div><?php
		foreach ($documents as $document => $info)
		{
	if(isset($info[0]))//si c'est un tableau multidimensionnel -> que l'utilisateur appartient à plusieurs sous catégories
	{
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
}
else
{
	if(stristr($info['visible_par'], $array->obtenir_poste_utilisateur($_SESSION['utilisateur_id'])))
	{
		?><div class="ligne">
			<div class="row tr entete navbar navbar-expand-lg ">
				<?php
				echo '<div class="col-5 col-sm-3"><button id="button-collapse-part'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContentPart'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['libelle'].'</button></div>';
				echo '<div class="col-4 col-sm-5">';
				$array->obtenir_noms_categories_par_ids($info['categorie']);
				echo '</div>';

				echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="voir_doc?id='.$info['id'].'">&darr;</a></div>';
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
				{
					echo '<div class="col-4 d-sm-none"></div>';
					echo '<div class="col-2 col-sm-1"><a class="btn btn-secondary content-modify" href="modifier_document.php?id='.$info['id'].'"></a></div>';
				}
				else
				{
					echo '';
				}
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
				{
					echo '<div class="col-2 col-sm-auto"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$info['id'].'">&#10005;</button></div>';
					echo '<div class="col-4 d-sm-none"></div>';

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
				echo '<div class="collapse navbar-collapse" id="navbarSupportedContentPart'.$info['id'].'">';
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
					}
					?></div>
					<div class="col-6 col-lg-3 order-lg-3 th">Mise en ligne</div><?php
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
		var button = document.getElementById("button-collapse-part<?php echo $info['id'] ?>");
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
}
}
?>