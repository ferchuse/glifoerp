<?php
	include("../login/login_success.php");
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "compras";
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista Compras</title>
		
		<?php include("../styles.php");?>
		
	</head>
  <body>
		
		<?php include("../menu.php");?>
		
		<?php include('tabla_compras.php');?>
		
		
		<?php  include('../scripts.php'); ?>
	</body>
</html>