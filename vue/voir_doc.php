<?php
session_start();
include ('../functions/functions.php');
$function=new functions();
$_GET['id']=$function->verify_entry($_GET['id']);

if($function->obtenir_nom_categorie_par_id($function->obtenir_categorie_par_id_document($_GET['id']))!='Actualité' && stristr($_SERVER["HTTP_REFERER"], 'index'))//si c'est un document qui n'est pas de la catégorie Actualité
{
	$function->document_vue($_GET['id']);
}
if(!isset($_GET['id']))//évite qu'on rentre n'importe quoi en GET
{
	header('Location: index.php');
	exit;
}
if(!$function->is_connected())
{
	header('Location: connexion.php');
	exit;
}
include ('header.php');
$document=$function->lister_document($_GET['id'], $_SESSION['utilisateur_id']);
if(empty($document))//s'il n'y a aucun doc dans tab c'est que la personne n'a pas le droit de visualiser le doc
{
	$_SESSION['error_message']='Vous n\'avez pas le droit de visualiser ce document.';
	$function->redirect('index.php');
	exit;
}
foreach ($document as $doc => $tab)
{
	foreach ($tab as $info):
		echo '<object data="'.$info['emplacement'].'" type="application/pdf" embed="false">
		<p><h4>Si vous ne parvenez pas à voir le document, cliquez ici : <a class="btn-info btn" href="'.$info['emplacement'].'">'.$info['libelle'].'</a></h4>';
		?><br><br>
		<h6>Pour régler ce problème vous pouvez :</h6><br>
		<ul>
			<li>Activez la visionneuse PDF dans les paramètres de votre naviguateur :
				<ul>
					<li><a href="https://support.mozilla.org/fr/kb/voir-fichiers-pdf-firefox-sans-telecharger#w_utiliser-une-autre-visionneuse-pdf" target="_blank">Aide Firefox</a></li>
					<li><a href="https://supportalbusair.uservoice.com/knowledgebase/articles/1174534-activer-la-visualisation-des-pdf-sous-google-chrom" target="_blank">Aide Chrome</a></li>
					<li><a href="https://support.apple.com/fr-fr/guide/safari/ibrw1090/mac" target="_blank">Aide Safari</a></li>
				</ul>
			</li>
			<li>Changer de naviguateur (ayez une petite pensée pour les développeurs web : désinstallez Internet Explorer et ne l'utilisez plus <b><u>jamais</u></b> !). Privilégiez des naviguateurs plus performants comme <a href="https://www.mozilla.org/fr/firefox/new/" target="_blank">Firefox</a> ou <a href="https://www.google.fr/chrome/?brand=CHBD&gclid=CjwKCAjw5vz2BRAtEiwAbcVIL2KKqP8JCj0t309n_moi64gU_LXZ5Saf7bjzKq1_rUP3RfZZSX4X0xoCm9gQAvD_BwE&gclsrc=aw.ds" target="_blank">Chrome</a>.</li>
			<li>Mettez à jour votre naviguateur.</li>
			<li>Si après tout ça vous n'arrivez toujours pas à visualiser le PDF sur le site, vous pouvez toujours le télécharger.</li>
			<li>Demander au petit "geek" de la famille, à ce stade là je ne peux plus rien faire pour vous.</li>
		</ul>
	</p>
</object>
<?php
endforeach;
}
include ('footer.php');