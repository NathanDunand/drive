<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$array=new functions();
$utilisateur=new functions();
$autorisation=new functions();
$documents=$array->lister_documents_par_categorie('Organisation de la collectivité');
$favori=new functions();

include ('header.php');

// $favori->dechiffrer_cat2($favori->dechiffrer_cat());

foreach ($documents as $document => $info)
{
	?><div class="ligne">
		<div class="row tr entete navbar navbar-expand-lg ">
			<div class="col-1"><?php
	if($favori->is_favori($_SESSION['utilisateur_id'], $info['id']))//si c'est un favoris
	{
		?><form method="post" action="../scripts/supprimer_favoris.php"><?php
		echo '<input type="hidden" name="id" value="'.$info['id'].'">
		<input class="btn btn-warning" type="submit" value="&bigstar;"></form></div>';
	}
	else//si ce n'est pas un favoris
	{
		?><form method="post" action="../scripts/ajouter_favoris.php"><?php
		echo '<input type="hidden" name="id" value="'.$info['id'].'">
		<input class="btn btn-info" type="submit" value="&bigstar;"></form></div>';
	}
	echo '<div class="col-5 col-sm-5 col-lg-5"><button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['libelle'].'</button></div>';
	echo '<div class="col-3 col-sm-2">'.$info['categorie'].'</div>';
	// echo '<div class="col-4 col-sm-3"><div class="btn button-disabled">'.$info['categorie'].'</div></div>';

	echo '<div class="col-3 col-sm-1"><a class="btn btn-secondary content-download" target="_blank" href="'.$info['emplacement'].'">&darr;</a></div>';
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
		echo '<div class="col-6 col-lg-3 order-lg-5 td">'.strftime("%D", strtotime($info['date_depot'])).'</div>';

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
	echo '<div class="col-6 col-lg-3 order-lg-7 td">'.strftime("%D", strtotime($info['date_document'])).'</div>';

	if($info['date_validite']!=null)//si il y a une date
	{
		?><div class="col-6 col-lg-2 order-lg-4 th">Validité</div><?php
		echo '<div class="col-6 col-lg-2 order-lg-8 td">'.strftime("%D", strtotime($info['date_validite'])).'</div>';
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
}
include ('footer.php'); ?>