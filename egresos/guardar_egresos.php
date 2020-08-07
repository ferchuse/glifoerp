<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$consulta = "INSERT INTO egresos SET 
	id_ventas = '{$_POST["id_ventas"]}',
	fecha_egresos = '{$_POST["fecha_egresos"]}',
	importe = '{$_POST["importe"]}',
	concepto = '{$_POST["concepto"]}',
	area = '{$_POST["area"]}'
	
	
	";
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"] = $consulta;
	
	if($result){
		$respuesta["status"] = "success";
		$respuesta["mensaje"] = "Guardado";
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo json_encode($respuesta);
?>