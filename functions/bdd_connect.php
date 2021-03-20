<?php
//les variables sont incluses depuis config.php
$bdd = new PDO('mysql:host='.HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET.'', ''.DB_LOGIN.'', ''.DB_PASSWORD.'');
?>