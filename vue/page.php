<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$array=new functions();
$utilisateur=new functions();
$autorisation=new functions();
$_GET['id']=$connect->verify_entry($_GET['id']);
$favori=new functions();

include ('header.php');

// $favori->dechiffrer_cat2($favori->dechiffrer_cat());

if(!isset($_GET['id']))//évite qu'on rentre n'importe quoi en GET
{
	header('Location: index.php');
	exit;
}
if(isset($_GET['type'])&&($_GET['type']!='sous-cat'))//évite qu'on rentre n'importe quoi en GET
{
	header('Location: connexion.php');
	exit;
}

if(isset($_GET['type'])&&$_GET['type']=='sous-cat')://si on veut les docs d'une sous-cat
$documents=$array->lister_documents_par_sous_categorie($_GET['id'], '', $_SESSION['utilisateur_id']) ?>
<div class="row justify-content-center">
	<div class="col-auto"><h2><?php echo $connect->obtenir_nom_sous_categorie_par_id($_GET['id']) ?></h2></div>
</div>
<?php else: $documents=$array->lister_documents_par_categorie($_GET['id'], '', $_SESSION['utilisateur_id']); ?>
	<div class="row justify-content-center">
		<div class="col-auto"><h2><?php echo $connect->obtenir_nom_categorie_par_id($_GET['id']) ?></h2></div>
	</div>
<?php endif;

if(empty($documents))//s'il n'y a pas de documents à afficher
{
	?><div class="row justify-content-center"><div class="col-auto"><h5><i>Vous n’avez pas de documents dans cette catégorie</i></h5></div></div><?php
}

foreach ($documents as $document => $info)
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
include ('footer.php'); ?>