<?php
	//busca un termino en la base de datos Version: 7-dic-2017
	header('Content-Type: application/json');
	include("../../conexi.php");
	$link=Conectarse();
	
	$respuesta = array();
	$debug = array();
	
	$term = $_GET["term"];
	
	$tabla = $_GET["tabla"];
	$campo = $_GET["campo"];
	
	$consulta = "SELECT *  FROM $tabla WHERE $campo LIKE '%" . $term . "%'";
	
	
	
	if(isset($_GET["filter_field"])){
		$filter_field = $_GET["filter_field"];
		$filter_value = $_GET["filter_value"];
		
		$consulta.= " AND $filter_field = '$filter_value'";
	}
	if(isset($_GET["campo_2"])){
		$campo_2 = $_GET["campo_2"];
		
		$consulta.= " OR $campo_2 LIKE '%" . $term . "%'";
	}
	if(isset($_GET["order_by"])){
		
		$consulta.= " ORDER BY " . $_GET["order_by"];
	}
	
	
	
	if(isset($_GET["limit"])){
		$limit = $_GET["limit"];
	}
	else{
		$limit = 25;
	}
	
		$consulta.= " LIMIT $limit ";
		
	
	$result_complete = mysqli_query($link, $consulta  )
	or die("Error al ejecutar consulta: $consulta".mysqli_error($link));
	
	$count = 0;
	while($row = mysqli_fetch_assoc($result_complete)) {
		$count++;
		
		
		
		//Si se busca por nombre regresa concatena con los apellidos
		if($campo == 'nombre_alumnos'){
		
			$label = $row[$campo]." ". $row['apellidop_alumnos']." ".$row['apellidom_alumnos'];
			$value= $label;
		}
		else{ 
			
			$label = $row[$campo];
			$value = $row[$campo];
		}
		
		
		
		if(isset($_GET["extra_labels"])){
			$extra_labels = "";
			foreach($_GET["extra_labels"] as $indice=>$columna){
				$extra_labels.= " | " . $row[$columna];
				
			}
			//$extra_labels = implode("|", $_GET["extra_labels"]);
			$label = $row[$campo] . $extra_labels;
			$value= $label;
		}
		
		
		
		$fila = array("value" => $value, "label" => $label, "extras" => $row, "campo" => $campo );
	
		
		array_push($respuesta, $fila);
		
	}
	
	if($count == 0 ){
		
		$respuesta[] =  array("value" => "", "label" => "No se encontraron resultados");
	}
	
	echo(json_encode($respuesta));
	
	

?>