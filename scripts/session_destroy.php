<?php
session_start();
session_destroy();
include ('../functions/functions.php');
$verify=new functions();
$verify->redirect('connexion.php');
exit;
?>