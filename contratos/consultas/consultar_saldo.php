<?php
	header("Content-Type: application/json");
	header("Access-Control-Allow-Origin: *");
	include ("../../conexi.php");
	include ("../../lib/mpago/vendor/autoload.php");
	include ("../../lib/sendinblue/vendor/autoload.php");
	
	$link = Conectarse();
	$respuesta = Array();
	$lista_cargos = Array();
	$saldo_pendiente = 0;
	
	
	
	$consulta = "SELECT * FROM cargos 
	LEFT JOIN clientes USING(id_clientes)
	WHERE 
	estatus = 'Pendiente'
	
	AND id_clientes = '{$_GET["id_clientes"]}'
	";
	
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"] = $consulta;
	
	if($result){
		while($fila = mysqli_fetch_assoc($result)){
			
			$respuesta["lista_cargos"][] = $fila;
			$saldo_pendiente += $fila["importe"];
			
		}
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	
	
	echo json_encode($respuesta);
?>	