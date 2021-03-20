<?php
session_start();
include ('../functions/functions.php');

$utilisateur=new functions();
$connect=new functions();
$autorisation=new functions();
$contenu=new functions();

if(!$connect->is_connected())
{
	$_SESSION['error_message']='Vous devez vous connecter pour effectuer cette action.';
	header('Location: connexion.php');
	exit;
}

include ('header.php');

$id=$_SESSION['utilisateur_id'];//valeur par défaut

	if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))//si la personne peut modifier son compte
	{
		$modifier_information='<a class="btn btn-info btn-lg btn-block" href="../vue/update_utilisateur.php?id='.$_SESSION['utilisateur_id'].'">Modifier utilisateur</a>';
	}
	else
	{
		$modifier_information='';
	}
	$modifier_information='';
	?>
	<div class="row justify-content-center">
		<div class="col-auto mt-3 mb-3"><?php $contenu->get_content_part('h2', 'mt-3 mb-3', 'compte_titre_informations') ?></div>
	</div>
	<div class="row justify-content-center">
		<div class="col-auto"><a class="btn btn-info btn-lg btn-block mb-3" href="../scripts/session_destroy.php">Se déconnecter</a></div>
	</div>

	<?php
	foreach ($utilisateur->obtenir_information_utilisateur_par_id($id) as $information) {

		?><div class="row text"><?php
		echo '<div class="col-12 col-lg"><p><strong>Nom</strong> : '.$information['nom'].'</p></div>';
		echo '<div class="col-12 col-lg"><p><strong>Mail (identifiant)</strong> : '.$information['mail'].'</p></div>';
		?><div class="col-12 col-lg"><a class="btn btn-secondary" data-toggle="collapse" href="#collapseListe" role="button" aria-expanded="false" aria-controls="collapseExample">Liste(s)</a> 
			<div class="collapse" id="collapseListe">
				<div class="card card-body">
					<?php
					$information['poste']=explode('|', $information['poste']);
					$information['poste']=array_filter($information['poste']);
					?><ul class="list-group list-group-flush"><?php
					foreach ($information['poste'] as $one)
					{
						if($one=='super-administrateur')
						{
							echo $one;
							break;
						}
						?><li class="list-group-item"><?php
						echo $utilisateur->obtenir_nom_categories_utilisateurs_par_id($one);
						?></li><?php
					}
					?></ul>
				</div><!--end card-body-->
			</div><!--end collapse-->
		</div>
		<div class="col-12 col-lg"><form method="post" action="../scripts/modifier_notification.php">
			<label for="notification" class="cursor-pointer" onClick="this.form.submit()"><p>Accepter les notifications par mail</label></p><?php
		if($information['notification']=='oui')//si l'utilisateur accepte les notifications (par défaut il les acceptent
		{
			?><div class="col"><input id="notification" type="checkbox" name="notification" checked onClick="this.form.submit()"></div><?php
		}
		else
		{
			?><div class="col"><input id="notification" type="checkbox" name="notification" onClick="this.form.submit()"></div><?php
		}
		?>
		<small>si la case est cochée, vous acceptez les notifications</small></form>
	</div><!--end col-->
	</div><!--end row--><?php
} ?>

<div class="row justify-content-center">
	<div class="col-auto"><?php $contenu->get_content_part('h2', 'mt-3 mb-3', 'compte_titre_message') ?></div>
</div><!--end row-->
<div class="row justify-content-center">
	<div class="col-auto"><?php $contenu->get_content_part('small', '', 'compte_texte_message') ?></div>
</div>
<div class="text">
	<form method="post" action="../scripts/message.php">
		<input class="mt-3 form-control" type="text" name="objet" placeholder="Objet du message" required>
		<hr>
		<textarea class="form-control" placeholder="Ecrivez votre message ici ..." name="message" required></textarea>
		<input class="mt-3 mb-3 btn btn-success btn-lg btn-block" type="submit" value="Envoyer">
	</form>
</div><!--end row-->

<div class="row justify-content-center">	
	<div class="col-auto"><a href="changer_mdp.php" class="btn btn-info btn-lg btn-block mb-3">Changer le mot de passe</a></div>
	<?php
		if(empty($modifier_information))//si variable vide
		{}
		else{echo '<div class="col-12">'.$modifier_information.'</div>';}
		?>
	</div>
	<?php
	include ('footer.php');
	?>