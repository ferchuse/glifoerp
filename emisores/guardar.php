<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$guardar = "INSERT INTO emisores SET 
	
	
	id_emisores = '{$_POST["id_emisores"]}',
	razon_social_emisores = '{$_POST["razon_social_emisores"]}'
	
	
	ON DUPLICATE KEY UPDATE 
	
	razon_social_emisores = '{$_POST["razon_social_emisores"]}',
	rfc_emisores = '{$_POST['rfc_emisores']}',
	password = '{$_POST['pass_emisores']}',
	serie_emisores = '{$_POST['serie_emisores']}',
	folio_emisores = '{$_POST['folio_emisores']}'
	";
	
	$respuesta['consulta'] = "$guardar";
	if(mysqli_query($link,$guardar)){
		$respuesta['estatus'] = "success";
		$respuesta['estatus'] = "Guardado Correctamente";
		
		}else{
		$respuesta['estatus'] = "error";
		$respuesta['mensaje'] = mysqli_error($link);
	}
	
	echo json_encode($respuesta);
?>