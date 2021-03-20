<?php
session_start();
include ('../functions/functions.php');
$function=new functions();

include ('header.php');

?>
<div class="text">
<h2>Pages</h2><?php
foreach($function->lister_dossier('vue') as $fichier)
{
	echo $fichier.'<br>';
}

?><h2>Catégories</h2><?php
foreach($function->lister_categories() as $categorie)
{
	echo '<ul style="list-style: none;">'.$categorie['nom'];
	foreach ($function->lister_sous_categories_par_id_categorie($categorie['id']) as $key => $sous_cat)
	{
		echo '<li class="ml-5">'.$sous_cat['nom'].'</li>';
	}
	?></ul><?php
}
?>
</div>
<?php
include ('footer.php');
?>