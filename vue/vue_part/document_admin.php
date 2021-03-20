<!--SESSION INTEGREES FUNCTIONS INTEGREES-->
<?php
$array=new functions();
$utilisateur=new functions();
$autorisation=new functions();
$contenu=new functions();
$documents=$array->lister_documents();
?>
<div class="row justify-content-center">
	<div class="col-auto"><?php $contenu->get_content_part('h2', 'mt-3 mb-3', 'documents_titre') ?></div>
</div>
<?php
if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_document'))
{
	$documents=$array->lister_tous_documents();//liste même les documents non visibles si on a l'autorisation de supprimer un doc
}
foreach ($documents as $document => $info):

	if(file_exists($info['emplacement']))://si le fichier existe
	?>
	<div class="ligne">
		<div class="row tr entete navbar navbar-expand-lg ">
			<div class="col-5 col-sm-3"><?php echo '<button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['libelle'].'</button>';//pas le 'vrai' nom du fichier?></div>
			<div class="col-4 col-sm-5"><?php echo $array->obtenir_nom_categorie_par_id($info['categorie']) ?></div>
			<?php
			echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="'.$info['emplacement'].'"></a></div>';
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
			?>
		</div><!--end entete-->
		<?php
		if($info['visible']=='oui')
		{
			?>
			<div class="navbar-expand-lg">
				<?php echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
			}
			else
				{ ?>
					<div class="navbar-expand-lg alert-danger">
						<?php echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
					}
					?><div class="row tr">
						<div class="col-6 col-lg-3 order-lg-2 th">Date doc.</div>
						<?php
						echo '<div class="col-6 col-lg-3 order-lg-7 td">'.strftime("%d/%m/%G", strtotime($info['date_document'])).'</div>';
						?>
						<div class="col-6 col-lg-2 order-lg-3 th">Auteur</div>
						<?php
						echo '<div class="col-6 col-lg-2 order-lg-8 td">';
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
	?></div><!-- end row tr-->
	<div class="col-6 col-lg-3 order-lg-4 th">Dépôt</div>
	<?php
	echo '<div class="col-6 col-lg-3 order-lg-9 td">'.strftime("%d/%m/%G", strtotime($info['date_depot'])).'</div>';
	?>
	<div class="col-6 col-lg-3 order-lg-5 th">
		<?php
	if($info['date_validite']!=null)//si il y a une date
	{
		?>Validité</div><?php
		echo '<div class="col-6 col-lg-3 order-lg-10 td">'.strftime("%d/%m/%G", strtotime($info['date_validite'])).'</div>';
	}
	else
	{	
		?></div><?php
		echo '<div class="col-6 col-lg-3 order-lg-10"></div>';
	}
	?>
</div><!--end row-->
</div><!--end collapse-->
</div><!--end expand-->
<?php
endif;//ferme le if qui vérifie si le fichier existe
?>
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
endforeach;//ferme le foreach qui créer le tableau
?>