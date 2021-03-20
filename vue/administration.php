<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
$autorisation=new functions();
$contenu=new functions();
include ('header.php');

if(!$connect->is_connected())
{
	$_SESSION['error_message']='Vous devez pour connecter pour effectuer cette action.';
	header('Location: connexion.php');
	exit;
}

if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'ajout_document'))//si la personne peut ajouter un document
{
	?><a class="btn btn-info" href="../vue/ajouter_document.php">Ajouter un document</a><?php
}
?><div class="row"><?php
if($connect->is_autorisation($_SESSION['utilisateur_id'], 'modifier_categorie')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'ajouter_categorie')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_categorie'))
{
	?><div class="col"><a href="categories.php" class="btn btn-info">Gestion des catégories de documents</a></div>
	<div class="col"><a href="listes.php" class="btn btn-info">Gestion des listes d'utilisateurs</a></div><?php
}
if($autorisation->is_autorisation($_SESSION['utilisateur_id'], 'creer_compte'))//si on est connecté ou qu'on a pas l'autorisation de créér un compte
{
	?><div class="col"><a href="inscription.php" class="btn btn-info">Créer un nouveau compte</a></div><?php
}
if($connect->is_autorisation($_SESSION['utilisateur_id'], 'notifier_utilisateur'))
{
	?><div class="col"><a href="mail.php" class="btn btn-info">Envoyer un mail</a></div><?php
}
if($connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))
{
	?>
	<div class="col">
		<a class="btn btn-info" href="personnaliser_css.php">Personnaliser l'apparence du site</a>
		<small>Pour une personnalisation plus complète, préférez CSS additionnel</small>
	</div>
	<div class="col">
		<a class="btn btn-info" href="ajouter_css.php" target="_blank">CSS additionnel</a>
	</div>
	<div class="col">
		<a class="btn btn-info" data-toggle="collapse" href="#configuration_avancee" role="button" aria-expanded="false" aria-controls="configuration_avancee">Configuration avancée</a></div>
		<div class="col-12">
			<div class="collapse" id="configuration_avancee">
				<div class="row justify-content-center">
					<div class="col-auto">
						<div class="row">
							<form method="post" action="../scripts/modifier_configuration.php">
								<div class="col-12">Mail de l'administrateur : <input type="text" value="<?php echo $contenu->get_configuration('MAIL_MESSAGE') ?>" name="MAIL_MESSAGE" required><br><small>Cette adresse mail est utilisée pour contacter l'administrateur du site via le pied de page, elle réceptionne aussi les messages envoyés depuis ce site via l'onglet "Mon Compte".</small></div>
								<div class="col-12">Sujet des mails envoyés pour notifier lors d'un ajout d'un document : <input type="text" value="<?php echo $contenu->get_configuration('OBJET_NOTIFICATION') ?>" name="OBJET_NOTIFICATION" required></div>
								<div class="col-12">Message des mails envoyés pour notifier lors d'un ajout d'un document : <textarea name="MESSAGE_NOTIFICATION" required><?php echo $contenu->get_configuration('MESSAGE_NOTIFICATION') ?></textarea></div>
								<div class="col-12"><input type="submit" class="btn btn-success btn-lg btn-block mt-3 form-control" value="Valider"></div>
							</form>
							<div class="col-12"><h3>Favicon</h3><?php $contenu->get_content_part_image('img', 'vignette', 'favicon') ?></div>
							<div class="col-12"><h3>Titre du site</h3><?php $contenu->get_content_part('p', '', 'titre_site') ?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	?></div><!--end row--><?php
	if($connect->is_autorisation($_SESSION['utilisateur_id'], 'supprimer_compte')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'modifier_compte'))
	{
		include ('vue_part/lister_utilisateurs.php');
	}

	include ('footer.php');
	?>
