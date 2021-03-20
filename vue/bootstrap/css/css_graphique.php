<?php
header('content-type: text/css');
include('../../../functions/functions.php');
$style=new functions();
?>
/*REGLE DE PERSONNALISATION GRAPHIQUE*/
:root {
	--bleumarine: <?php $style->get_style_part('couleur1', 'valeur') ?>;
	--rouge: <?php $style->get_style_part('couleur2', 'valeur') ?>;
	--dore: <?php $style->get_style_part('couleur3', 'valeur') ?>;
	--tableau1: <?php $style->get_style_part('tableau1', 'valeur') ?>;
	--tableau2: <?php $style->get_style_part('tableau2', 'valeur') ?>;
	--suppression: <?php $style->get_style_part('suppression', 'valeur') ?>;
	--suppression-hover: <?php $style->get_style_part('suppression-hover', 'valeur') ?>;
	--modification: <?php $style->get_style_part('modification', 'valeur') ?>;
	--modification-hover: <?php $style->get_style_part('modification-hover', 'valeur') ?>;
	--telechargement: <?php $style->get_style_part('telechargement', 'valeur') ?>;
	--telechargement-hover: <?php $style->get_style_part('telechargement-hover', 'valeur') ?>;
	--couleur-h1: <?php $style->get_style_part('couleur-h1', 'valeur') ?>;
	--taille-h1: <?php $style->get_style_part('taille-h1', 'valeur') ?>pt;
	--couleur-h2: <?php $style->get_style_part('couleur-h2', 'valeur') ?>;
	--taille-h2: <?php $style->get_style_part('taille-h2', 'valeur') ?>pt;
	--couleur-h3: <?php $style->get_style_part('couleur-h3', 'valeur') ?>;
	--taille-h3: <?php $style->get_style_part('taille-h3', 'valeur') ?>pt;
	--couleur-h4: <?php $style->get_style_part('couleur-h4', 'valeur') ?>;
	--taille-h4: <?php $style->get_style_part('taille-h4', 'valeur') ?>pt;
	--couleur-h5: <?php $style->get_style_part('couleur-h5', 'valeur') ?>;
	--taille-h5: <?php $style->get_style_part('taille-h5', 'valeur') ?>pt;
	--couleur-h6: <?php $style->get_style_part('couleur-h6', 'valeur') ?>;
	--taille-h6: <?php $style->get_style_part('taille-h6', 'valeur') ?>pt;
	--menu-item: <?php $style->get_style_part('menu-item', 'valeur') ?>;
	--menu-item-hover: <?php $style->get_style_part('menu-item-hover', 'valeur') ?>;
	--classique: <?php $style->get_style_part('classique', 'valeur') ?>;
	--classique-hover: <?php $style->get_style_part('classique-hover', 'valeur') ?>;
	--validation: <?php $style->get_style_part('validation', 'valeur') ?>;
	--validation-hover: <?php $style->get_style_part('validation-hover', 'valeur') ?>;
	--placement-banner: <?php $style->get_style_part('placement-banner', 'valeur') ?>;
	/*syntaxe : background-color: var(--dore);*/
}

/*TABLEAUX*/
.ligne:nth-of-type(odd){background-color: var(--tableau1);}
.ligne:nth-of-type(even){background-color: var(--tableau2);}

.background-color-bleumarine{background-color: var(--bleumarine); color: white;}
.background-color-rouge{background-color: var(--rouge);border-radius: 0.5rem;}
.background-color-dore{background-color: var(--dore);border-radius: 0.5rem;}

/*BOUTONS*/
/*transition au survol*/
.btn{transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}

.btn-danger{background-color: var(--suppression);border-color: var(--suppression);}
.btn-danger:hover{background-color:var(--suppression-hover);border-color: var(--suppression-hover);}
.btn-danger:focus{background-color:var(--suppression)!important;border-color:var(--suppression)!important;}

.content-modify{background-color: var(--modification);border-color: var(--modification);}
.content-modify:hover{background-color:var(--modification-hover);border-color: var(--modification-hover);}
.content-modify:focus{background-color:var(--modification)!important;border-color:var(--modification)!important;}

.content-download{background-color: var(--telechargement);border-color:var(--telechargement);}
.content-download:hover{background-color:var(--telechargement-hover);border-color: var(--telechargement-hover);}
.content-download:focus{background-color:var(--telechargement)!important;border-color:var(--telechargement)!important;}

.btn-info{background-color: var(--classique);border-color:var(--classique);}
.btn-info:hover{background-color:var(--classique-hover);border-color: var(--classique-hover);}
.btn-info:focus{background-color:var(--classique)!important;border-color:var(--classique)!important;}

.btn-success{background-color: var(--validation);border-color:var(--validation);}
.btn-success:hover{background-color:var(--validation-hover);border-color: var(--validation-hover);}
.btn-success:focus{background-color:var(--validation)!important;border-color:var(--validation)!important;}

/*TITRES*/
h1{color:var(--couleur-h1);font-size:var(--taille-h1);}
h2{color:var(--couleur-h2);font-size:var(--taille-h2);}
h3{color:var(--couleur-h3);font-size:var(--taille-h3);}
h4{color:var(--couleur-h4);font-size:var(--taille-h4);}
h5{color:var(--couleur-h5);font-size:var(--taille-h5);}
h6{color:var(--couleur-h6);font-size:var(--taille-h6);}

/*MENU*/
/*transition au survol*/
.navbar-light .navbar-nav .nav-link{transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
.dropdown-item:focus, .dropdown-item{transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;}
.menu{background-color: var(--bleumarine)!important;}
.navbar-light .navbar-nav .nav-link{color:var(--menu-item);}/*éléments du menu*/

.dropdown-item:focus, .dropdown-item{color:var(--menu-item);}/*éléments du menu dropdown*/
.dropdown-item:focus, .dropdown-item:hover{color:var(--menu-item-hover);}/*éléments du menu dropdown*/
.dropdown-item:focus, .dropdown-item:hover{background-color:transparent;}
.navbar-light .navbar-nav .nav-link:hover{color:var(--menu-item-hover);}

/*ACCEUIL*/
.encart-banner{width: 100vw; height:30vh;background-repeat:no-repeat;background-size: cover;background-image: url("../../<?php $style->get_source('banniere_site') ?>");background-position:right 50% bottom <?php $style->get_style_part('placement-banner', 'valeur') //texte nous donne la valeur de décalage de la bannière pour l'ajuster au site ?>%;}