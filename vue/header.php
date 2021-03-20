<?php $connect=new functions();
$contenu=new functions()?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/png" href="vue_part/images/favicon.png"/>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php $contenu->get_titre_site() ?></title>
	<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
	<link href="bootstrap/css/css_graphique.php" type="text/css" rel="stylesheet"/>
	<link href="bootstrap/css/css_bootstrap2.css" rel="stylesheet"/>
	<link href="bootstrap/css/css_additionnel.css" rel="stylesheet"/>
	<!-- <link href="https://fonts.googleapis.com/css?family=Quicksand&display=swap" rel="stylesheet">  -->
	<link href="aos/aos.css" rel="stylesheet"><!--librairie aos-->
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
	rel="stylesheet"><!--icon google-->
	<script src="js/lazysizes.min.js"></script>

	<script src="js/jquery.min.js"></script><!--librairie js pour le tooltip (info-bulle au survol)-->
</head>
<body>
	<?php if(isset($_SESSION['utilisateur_id']))://si on est connecté -> sur connexion.php ?>
		<nav class="navbar navbar-light bg-light navbar-expand-lg menu sticky-top">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav">	
					<div class="row menutest">
						<li class="col col-lg-1 order-1 order-lg-1 item-menu">
							<?php $contenu->get_content_part('a', 'nav-link nav-item ', 'header_index')?>
						</li>			
						<?php
						foreach ($contenu->lister_categories() as $categorie => $info)
						{
							if(empty($contenu->lister_sous_categories_par_id_categorie($info['id'])))
							{
								?><li class="col-12 col-lg-auto order-3 order-lg-2 item-menu"><a class="nav-link" href="page.php?id=<?php echo $info['id'] ?>"><?php echo $info['nom']; ?></a></li><?php
							}
							else
							{
								?>
								<li class="dropdown item-menu col-12 col-lg-auto order-4 order-lg-3">
									<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown<?php echo $info['id'] ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $info['nom'] ?></a>
									<div class="dropdown-menu menu" aria-labelledby="navbarDropdown<?php echo $info['id'] ?>">
										<a class="dropdown-item" href="page.php?id=<?php echo $info['id'] ?>"><h6><?php echo $info['nom'] ?></h6></a>
										<div class="dropdown-divider"></div>
										<?php foreach ($contenu->lister_sous_categories_par_id_categorie($info['id']) as $sous_cat): ?>
											<a class="dropdown-item" href="page.php?id=<?php echo $sous_cat['id'] ?>&type=sous-cat"><?php echo $sous_cat['nom'] ?></a>
										<?php endforeach; ?>
									</div>
								</li>
								<?php
							}
						} 
						?>
						<li class="col col-lg-auto order-2 order-lg-4 item-menu">
							<?php $contenu->get_content_part('a', 'nav-link nav-item', 'header_agendamunicipal')?>
						</li>
						<?php if($connect->is_autorisation($_SESSION['utilisateur_id'], 'ajout_document')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'modifier_categorie')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'creer_compte')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'notifier_utilisateur')||$connect->is_autorisation($_SESSION['utilisateur_id'], 'notifier_utilisateur')||$connect->is_poste($_SESSION['utilisateur_id'], 'super-administrateur'))
						{
							?><li class="col col-lg-auto order-2 order-lg-4 item-menu"><?php $contenu->get_content_part('a', 'nav-link nav-item', 'header_administration') ?></li><?php
						} ?>
						<li class="col col-lg-auto order-2 order-lg-4 item-menu">
							<?php $contenu->get_content_part('a', 'nav-link nav-item', 'header_moncompte')?>
						</li>
						<li class="col col-lg-auto order-2 order-lg-4 item-menu">
							<?php $contenu->get_content_part('a', 'nav-link nav-item', 'header_sitemairie')?>
						</li>
					</div>
				</ul>	
			</div>
		</nav>
	<?php endif; ?>
	<div class="fluid-container"><!--ouverture du conainter-->

		<!--Messages d'alertes-->
		<?php
		if(!isset($_SESSION['success_message']))
		{
			$_SESSION['success_message']='';
		}
		if(!isset($_SESSION['error_message']))
		{
			$_SESSION['error_message']='';
		}
		if($_SESSION['success_message']!='')
		{
			echo '<div data-aos="fade-down" class="alert alert-success alert-dismissible fade show" role="alert">
			<strong>'.$_SESSION["success_message"].'</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>';
		}
		if($_SESSION["error_message"]!='')
		{
			echo '<div data-aos="fade-right" class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>'.$_SESSION["error_message"].'</strong>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			</div>';
		}
		$messages=new functions(); $messages->unset_messages(); //suppression des messages d'erreurs ?>