<?php
session_start();
include ('../functions/functions.php');
$connect=new functions();
include ('header.php');

if(!$connect->is_connected())
{
	$_SESSION['error_message']='Vous devez pour connecter pour effectuer cette action.';
	header('Location: connexion.php');
	exit;
}
if(!$connect->is_autorisation($_SESSION['utilisateur_id'], 'notifier_utilisateur'))
{
	$_SESSION['error_message']='Vous n\'avez pas les droits pour effectuer cette action.';
	header('Location: index.php');
	exit;
}
?>
<div class="text">
	<form method="post" action="../scripts/mail.php">
		<div class="row">
			<div class="col-12 col-md">
				<select id="input-liste" class="mt-3 form-control" name="liste[]" multiple="">
					<option value="administrateur">Administrateur</option>
					<option value="contributeur">Contributeur</option>
					<option value="utilisateur">Utilisateur</option>
				</select>
			</div>
			<div class="col-12 col-md-auto">
				<div class="row justify-content-center">
					<div class="col-auto"><p><h4>OU</h4></p></div>
				</div>
			</div>
			<div class="col-12 col-md">
				<input id="input-destinataire" class="mt-3 form-control" type="text" name="destinataire" placeholder="Adresse mail du/des destinataire(s)" value="<?php if(isset($_SESSION['destinataire'])) echo $_SESSION['destinataire']; ?>">
			</div>
			<div class="col-12"><hr></div>
			<div class="col-12"><input type="text" class="mt-3 form-control" name="objet" placeholder="Objet" value="<?php if(isset($_SESSION['objet'])) echo $_SESSION['objet']; ?>" required></div>
			<div class="col">
				<textarea name="message" class="mt-3 form-control" placeholder="Votre mesage ..." required><?php if(isset($_SESSION['message'])) echo $_SESSION['message']; ?></textarea>
				<hr><input class="btn btn-success btn-lg btn-block mt-3 form-control" type="submit" class="btn btn-info">
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	var element = document.getElementById('input-liste');
	element.addEventListener('click', function() {document.getElementById("input-destinataire").value = "";});

	var element = document.getElementById('input-destinataire');
	element.addEventListener('click', function() {document.getElementById("input-liste").value = "";});
</script>
<?php include ('footer.php'); 
unset($_SESSION['destinataire']);unset($_SESSION['objet']);unset($_SESSION['message']); ?>