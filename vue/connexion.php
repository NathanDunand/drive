<?php
session_start();
include ('../functions/functions.php');
include ('header.php');
$contenu=new functions();
?>
<div class="background-image">
	<div class="row justify-content-center">
		<div class="col-12"><?php $contenu->get_content_part_image('img', 'rounded mx-auto mt-5 d-block logo', 'accueil_logo') ?></div>
		<div class="col-12">
			<div class="mt-15vh"></div>
			<div class="p-5">
				<form method="post" action="../scripts/connect.php">
					<div class="row justify-content-center">
						<div class="col-12 col-md-8 col-lg-6"><input type="text" class="mb-3 form-control" name="login" placeholder="Identifiant (mail)" required></div>
					</div>
					<div class="row justify-content-center">
						<div class="col-12 col-md-8 col-lg-6"><input type="password" class="mb-3 form-control" name="password" placeholder="Mot de passe" required><hr></div>
					</div>
					<div class="row justify-content-center">
						<div class="col-12 col-md-8 col-lg-6"><input type="submit" class="form-control btn btn-info" name="submit" value="Se connecter"></div>
					</div>
				</form>
			</div><!--end bg-connexion-->
		</div>
	</div>
</div><!--end background-->
<?php
include ('footer.php');
?>