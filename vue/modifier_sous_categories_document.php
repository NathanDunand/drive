<?php
session_start();
include ('../functions/functions.php');

$function=new functions();

if(!$function->is_autorisation($_SESSION['utilisateur_id'], 'modifier_document'))
{
	header('Location: index.php');
	exit();
}

include ('header.php');
?>
<form method="post" action="../scripts/modifier_sous_categories_document.php">
	Choisissez le nom des nouvelles sous-catégories de ce document<br>
	<select name="sous_categories[]" multiple="" required="">
		<?php foreach ($function->lister_sous_categories() as $categorie => $info) {
			echo '<option value="'.$info['id'].'">'.$info['nom'].'</option>';
		} ?>
	</select>
	<input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
	<input type="submit" name="" value="Modifier" class="btn btn-success">
</form>
<?php include ('footer.php'); ?>