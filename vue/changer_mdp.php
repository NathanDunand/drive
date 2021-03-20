<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
if(!$connect->is_connected())
{
	header('Location: connexion.php');
	exit;
}
if(!isset($_GET['id'])){$id=$_SESSION['utilisateur_id'];}//si on est sur sa propre page, la vérification des droits de l'utilisateur est fait dans le script
else{$id=$_GET['id'];}
include ('header.php');
?>
<h1>Changer le mot de passe</h1>
<p>Pour des raisons de sécurité, le mot de passe n'est pas récupérable, il sera donc totalement supprimé puis modifié par celui que vous aurez indiqué ici.</p>
<form method="post" action="../scripts/changer_mdp.php">
	Votre mot de passe : <input type="password" name="password1" required><br>
	Confirmez votre mot de passe : <input type="password" name="password2" required>
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<input class="btn btn-info" type="submit" value="Valider">
</form>
<?php
include ('footer.php');
?> 