<?php
session_start();
include ('../functions/functions.php');
$verify=new functions();
$_POST['code']=htmlspecialchars($_POST['code']);

file_put_contents('../vue/bootstrap/css/css_additionnel.css', $_POST['code']);
$verify->redirect('administration.php');
?>