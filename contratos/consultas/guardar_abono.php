<?php
	header("Content-Type: application/json");
	include ("../../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
		
	
	
	$consulta = "INSERT INTO abonos SET 
	id_clientes = '{$_POST["id_clientes"]}',
	fecha = '{$_POST["fecha"]}',
	concepto = '{$_POST["concepto"]}',
	importe = '{$_POST["importe"]}',
	referencia = '{$_POST["id_cargos"]}'
	
	";
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"]["abono"] = $consulta;
	
	if($result){
		$respuesta["status"]["abono"] = "success";
		$respuesta["mensaje"]["abono"] = "Guardado";
		
		$folio = mysqli_insert_id($link);
		
	}	
	else{
		$respuesta["status"]["abono"] = "error";
		$respuesta["mensaje"]["abono"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	
	$consulta = "UPDATE cargos SET 
	estatus = 'Pagado'
	
	WHERE id_cargos = '{$_POST["id_cargos"]}'
	";
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"]["cargo"] = $consulta;
	
	if($result){
		$respuesta["status"]["cargo"] = "success";
		$respuesta["mensaje"]["cargo"] = "Guardado";
		
		$folio = mysqli_insert_id($link);
		
	}	
	else{
		$respuesta["status"]["cargo"] = "error";
		$respuesta["mensaje"]["cargo"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	
	
	
	echo json_encode($respuesta);
?>