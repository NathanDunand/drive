<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
include ('header.php');
?>
<div class="row justify-content-center">
	<div class="col-auto"><?php $contenu->get_content_part('h2', '', 'header_agendamunicipal') ?></div>
</div>
<div class="row justify-content-center">
	<div class="col-auto">
		<div class="row">
			<div class="col-12 mb-5">
				<?php $contenu->get_content_part('p', '', 'iframe_agendamunicipal') ?>
			</div>
		</div> <!-- end agenda -->
	</div><!-- end col-auto -->
</div><!-- end row justify-content-center-->

	<?php //
	if($connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur')): ?>
		<div class="row">
			<div class="col-12"><a class="btn btn-info" href="https://calendar.google.com/calendar/b/2/embedhelper?src=agenda.elus.lesbelleville%40gmail.com&ctz=Europe%2FParis" target="_blank">Modifier le style du calendrier Google</a>
			</div>
			<div class="col-12"><h3>Comment modifier le style du calendrier Google ?</h3></div>
			<div class="col-12">
				<p>
					<ol>
						<li>Cliquez sur le lien juste au dessus</li>
						<li>Sélectionnez les paramètres que vous souhaitez intégrer au calendrier (couleur, contenu à afficher ...)</li>
						<li>Copiez l'iframe et collez-le au dessus du bouton</li>
						<li>Validez, c'est prêt !</li>
					</ol>
				</p>
			</div>
		</div>
	<?php endif ?> 
	<?php include ('footer.php');