<?php $contenu=new functions() ?>
<footer>
	<div class="row justify-content-center background-color-bleumarine">
		<?php $contenu->get_footer_liste('img', 'item-footer mt-3 mb-3', 'footer_item') ?>
	</div>
	<div class="row justify-content-center background-color-bleumarine">
		<div class="col-auto"><?php $contenu->get_content_part('p', 'mt-3 mb-3', 'footer_titre');echo '<p class="text-center">&copy; 2020-'.date('Y').'</p>' ?></div>
	</div>
	<div class="row justify-content-center background-color-bleumarine">
		<div class="col-auto"><p><a href="plan_du_site.php" class="btn btn-outline-light">Plan du site</a> | <a href="mailto:<?php echo MAIL_MESSAGE ?>" class="btn btn-outline-light">Contacter l'administrateur</a></p></div>
	</div>
	<div class="row justify-content-center background-color-bleumarine pb-3">
		<p class="text-center">Plateforme réalisée par <a data-toggle="tooltip" data-placement="top" title="06 49 98 09 82 nathandunand@orange.fr" class="lienND" href="http://nathandunand.fr" target="_blank">Nathan DUNAND</a> durant son stage de seconde année de DUT MMI à la commune des Belleville</p>
	</div>
</footer>
</div><!--fermeture du fluid-container ouvert dans header.php-->

<script src="aos/aos.js" async></script><!--librairie aos-->

<script src="js/jquery-3.3.1.slim.min.js"></script><!--librairie jquery-->
<script src="js/popper.min.js" async></script><!--librairie ajax-->
<script src="bootstrap/js/bootstrap.min.js" async></script><!--On intègre les scripts pour les frameworks-->
</body>
</html>
<?php
$search=new functions();
$search->unset_search();
?>