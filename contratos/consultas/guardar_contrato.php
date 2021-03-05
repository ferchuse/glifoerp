<?php
	header("Content-Type: application/json");
	include ("../../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	// $meses = [
	// 1 => "ENERO",
	// 2 => "FEBRERO",
	// 3 => "MARZO",
	// 4 => "ABRIL",
	// 5 => "MAYO",
	// 6=> "JUNIO",
	// 7 => "JULIO",
	// 8 => "AGOSTO",
	// 9 => "SEPTIEMBRE",
	// 10 => "OCTUBRE",
	// 11 => "NOVIEMBRE",
	// 12 => "DICIEMBRE"
	
	// ];
	
	$meses = [
	"ENERO",
	"FEBRERO",
	"MARZO",
	"ABRIL",
	"MAYO",
	"JUNIO",
	"JULIO",
	"AGOSTO",
	"SEPTIEMBRE",
	"OCTUBRE",
	"NOVIEMBRE",
	"DICIEMBRE"
	
	];
	
	$consulta = "INSERT INTO contratos SET 
	id_clientes = '{$_POST["id_clientes"]}',
	fecha_inicial = '{$_POST["fecha_inicial"]}',
	fecha_final = '{$_POST["fecha_final"]}',
	periodicidad = '{$_POST["periodicidad"]}',
	concepto = '{$_POST["concepto"]}',
	num_pagos = '{$_POST["num_pagos"]}'
	
	";
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"]["contrato"] = $consulta;
	
	if($result){
		$respuesta["status"]["contrato"] = "success";
		$respuesta["mensaje"]["contrato"] = "Guardado";
		
		$folio = mysqli_insert_id($link);
		
	}	
	else{
		$respuesta["status"]["contrato"] = "error";
		$respuesta["mensaje"]["contrato"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	
	
	//INSERTA CARGO POR CADA MES
	
	for($i = 0 ; $i < $_POST["num_pagos"] ; $i++ ){
		
		
		$fecha_cargo = date("Y-m-d", strtotime($_POST["fecha_inicial"] . " +$i MONTH"));
		// $fecha_cargo = date("Y-m-d", strtotime("+$i MONTH", strtotime($_POST["fecha_inicial"])));
		// $num_mes=date("n",strtotime("$fecha_inicio"));
		// $año=date("o",strtotime("$fecha_inicio"));
		// $dia=date("d",strtotime("$fecha_inicio"));
		// $fecha_arr=$dia.$meses[$num_mes-1].$año;
		
		$concepto = $_POST["concepto"] ." " .$meses[$i]. " ". date("Y", strtotime("+$i MONTH", strtotime($_POST["fecha_inicial"]))); 
		
		$consulta = "INSERT INTO cargos SET 
		id_contratos = '$folio',
		id_clientes = '{$_POST["id_clientes"]}',
		fecha = '{$fecha_cargo}',
		concepto = '{$concepto}',
		importe = '{$_POST["importe"]}',
		tipo_cargo = 'Recurrente',
		estatus = 'Inactivo'
		
		";
		$result = mysqli_query($link, $consulta);
		
		$respuesta["consulta"]["cargos"] = $consulta;
		
		if($result){
			$respuesta["status"]["cargos"]  = "success";
			$respuesta["mensaje"]["cargos"]  = "Guardado";
			
		}	
		else{
			$respuesta["status"]["cargos"]  = "error";
			$respuesta["mensaje"]["cargos"]  = "Error $consulta  ".mysqli_error($link);		
		}
		
	}
	
	echo json_encode($respuesta);
?>