<!--SESSION INTEGREES FUNCTIONS INTEGREES-->
<?php
$contenu=new functions();
$autorisation=new functions();
$user=new functions();
$utilisateurs=new functions();
?>
<div class="row justify-content-center">
	<div class="col-auto"><?php $contenu->get_content_part('h2', 'mt-3 mb-3', 'administration_titre') ?></div>
</div>
<div class="row justify-content-center">
	<div class="col-auto">
		<form method="post" action="../scripts/recherche_liste_utilisateurs.php">
			Recherche par catégorie d'utilisateur : <select class="form-control" name="poste" required="">
				<option value="">Sélectionnez votre liste</option><?php
				foreach ($connect->lister_categories_utilisateurs() as $cat_utilisateur)
				{
					echo '<option value="'.$cat_utilisateur['id'].'">'.$cat_utilisateur['nom'].'</option>';
				}
				?></select>
				<input type="submit" name="" value="Rechercher" class="btn btn-success">
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col"><!--endroit où sont affichés les résultats-->
			<?php
			if(isset($_SESSION['search']))
			{
				?><div class="alert alert-secondary alert-dismissible fade show" role="alert">
					Voilà ce que j'ai trouvé pour vous : <br><br><strong><?php
				if(count($_SESSION['search'])==0)//s'il n'a rien trouvé
				{
					echo 'absolument rien';
				}
				foreach ($_SESSION['search'] as $one)
				{
					echo '<a href="http://nathandunand.fr/mairie_smb/vue/espace_membre.php?id='.$one['id'].'" target="_blank">'.$one['nom'].' ('.$one['mail'].')</a><br>';
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
	<?php
	foreach ($utilisateurs->lister_utilisateurs() as $utilisateur => $info)
	{
		if($info['id']==$_SESSION['utilisateur_id'] || $autorisation->is_poste($info['id'], 'super-administrateur'))//ligne qui evite qu'on ne puisse modifier ses propres autorisations ou si on est sur la ligne du super admin
		{
			echo '';
		}
		else//on ne commence qu'après vérification au dessus, sinon on va afficher deux fois la même chose au début
		{
			?><div class="ligne"><?php
			foreach($user->obtenir_information_utilisateur_par_id($info['id']) as $key)
			{
				?><div class="row tr entete navbar navbar-expand-lg "><?php
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier un compte
				{
					echo '<div class="col-6 col-sm-4 col-md-3"><a href="espace_membre.php?id='.$info['id'].'">'.$key['nom'].'</a></div>';
				}
				else
				{
					echo '<div class="col-6 col-sm-4">'.$key['nom'].'</div>';
				}
				echo '<div class="col-6 col-sm-3">';
				echo $user->obtenir_nom_categories_utilisateurs_par_id($info['poste']);
				echo '</div>';
				echo '<div class="col-7 col-sm-5 col-md-4"><button id="button-collapse'.$info['id'].'" class="btn btn-info button-collapse" type="button" data-toggle="collapse" data-target="#navbarSupportedContent'.$info['id'].'" aria-controls="navbarSupportedContent" aria-expandelg"false" aria-label="Toggle navigation">'.$info['mail'].'</button></div>';
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))
				{
					echo '<div class="d-none d-sm-block d-md-none col-sm-4"></div><div class="col-3 col-sm-2 col-md-1"><button class="btn btn-success" onclick="var button = document.getElementById(\'form'.$info['id'].'\').submit();"><img src="vue_part/images/save.png"></button></div>';
				}
				if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_compte')) {echo '<div class="col-2 col-sm-2 col-md-1"><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#supprimer'.$info['id'].'">&#10005;</button></div><div class="d-none d-sm-block col-sm-4 d-md-none col-md-3"></div>';}
				?></div><!--end row entete-->

				<div class="navbar-expand-lg"><?php
				echo '<div class="collapse navbar-collapse" id="navbarSupportedContent'.$info['id'].'">';
				?><div class="row"><?php

			}
			
			if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte') && !$autorisation->is_poste($info['id'], 'super-administrateur'))//si on a l'autorisation de supprimer un compte && qu'on est pas sur la ligne du super-admin
			{
				foreach ($user->obtenir_autorisations_utilisateur_par_id($info['id']) as $information)
				{
					?>
					<div class="col-6 col-lg-3 order-lg-1 th"><form id="form<?= $info['id'] ?>" method="post" action="../scripts/modifier_autorisation.php?id=<?php echo $info['id'] ?>">
						<label class="cursor-pointer" for="creer_compte<?php echo $info['id'] ?>">Créer cpte</label></div>
						<div class="col-6 col-lg-3 order-lg-6 td"><input id="creer_compte<?php echo $info['id'] ?>" type="checkbox" name="creer_compte" <?php if($information['creer_compte']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-2 th"><label class="cursor-pointer" for="modifier_compte<?php echo $info['id'] ?>">Modifier cpte</label></div>
						<div class="col-6 col-lg-3 order-lg-7 td"><input id="modifier_compte<?php echo $info['id'] ?>" type="checkbox" name="modifier_compte" <?php if($information['modifier_compte']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-3 th"><label class="cursor-pointer" for="supprimer_compte<?php echo $info['id'] ?>">Suppr. un cpte</label></div>
						<div class="col-6 col-lg-3 order-lg-8 td"><input id="supprimer_compte<?php echo $info['id'] ?>" type="checkbox" name="supprimer_compte" <?php if($information['supprimer_compte']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-4 th"><label class="cursor-pointer" for="ajout_document<?php echo $info['id'] ?>">Ajouter doc</label></div>
						<div class="col-6 col-lg-3 order-lg-9 td"><input id="ajout_document<?php echo $info['id'] ?>" type="checkbox" name="ajout_document" <?php if($information['ajout_document']=='oui'){echo 'checked';} ?>></div>
					</div><!--end row-->

					<div class="row">
						<div class="col-6 col-lg-3 order-lg-1 th"><label class="cursor-pointer" for="modifier_document<?php echo $info['id'] ?>">Modifier doc</label></div>
						<div class="col-6 col-lg-3 order-lg-5 td"><input id="modifier_document<?php echo $info['id'] ?>" type="checkbox" name="modifier_document" <?php if($information['modifier_document']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-2 th"><label class="cursor-pointer" for="supprimer_document<?php echo $info['id'] ?>">Suppr. doc</label></div>
						<div class="col-6 col-lg-3 order-lg-6 td"><input id="supprimer_document<?php echo $info['id'] ?>" type="checkbox" name="supprimer_document" <?php if($information['supprimer_document']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-3 th"><label class="cursor-pointer" for="ajouter_categorie<?php echo $info['id'] ?>">Ajouter cat.</label></div>
						<div class="col-6 col-lg-3 order-lg-7 td"><input id="ajouter_categorie<?php echo $info['id'] ?>" type="checkbox" name="ajouter_categorie" <?php if($information['ajouter_categorie']=='oui'){echo 'checked';} ?>></div>
						<div class="col-6 col-lg-3 order-lg-4 th"><label class="cursor-pointer" for="modifier_categorie<?php echo $info['id'] ?>">Modif. cat</label></div>
						<div class="col-6 col-lg-3 order-lg-8 td"><input id="modifier_categorie<?php echo $info['id'] ?>" type="checkbox" name="modifier_categorie" <?php if($information['modifier_categorie']=='oui'){echo 'checked';} ?>></div>
					</div><!--end row-->
				</div><!--end navbar collapse-->
			</div><!--end navbar expand lg-->
		</form><!--end col modif autorisation-->
		<?php
	}
}
else
{
	?><div class="col"></div><div class="col"></div><?php
}
?>

<?php

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
Vous allez supprimer le compte de '.$info['nom'].'.<br>id : '.$info['id'].'<br>mail : '.$info['mail'].'
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
<a class="btn btn-danger" href="../scripts/supprimer_utilisateur.php?id='.$info['id'].'">Supprimer</a>
</div>
</div>
</div>
</div>';
?>
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
			?></div><!--end ligne--><?php
		}//fin else
	}//end foreach
	?>