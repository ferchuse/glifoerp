<?php
	header("Content-Type: application/json");
	include ("../conexi.php");
	$link = Conectarse();
	$respuesta = Array();
	
	$guardarProductos = "INSERT INTO productos SET 
	
	
	id_productos = '{$_POST["id_productos"]}',
	existencia_productos = '{$_POST["existencia_productos"]}',
	codigo_productos = '{$_POST['codigo_productos']}',
	costo_proveedor = '{$_POST['costo_proveedor']}',
	costo_unitario = '{$_POST['costo_unitario']}',
	precio_menudeo = '{$_POST['precio_menudeo']}',
	precio_mayoreo = '{$_POST['precio_mayoreo']}',
	descripcion_productos = '{$_POST['descripcion_productos']}',
	unidad_productos = '{$_POST['unidad_productos']}',
	ganancia_menudeo_porc = '{$_POST['ganancia_menudeo_porc']}',
	min_productos = '{$_POST["min_productos"]}',
	id_departamentos = '{$_POST["id_departamentos"]}',
	ubicacion = '{$_POST["ubicacion"]}',
	piezas = '{$_POST["piezas"]}',
	estatus_productos = '{$_POST["estatus_productos"]}'
	
	ON DUPLICATE KEY UPDATE 
	
	existencia_productos = '{$_POST["existencia_productos"]}',
	codigo_productos = '{$_POST['codigo_productos']}',
	costo_proveedor = '{$_POST['costo_proveedor']}',
	costo_unitario = '{$_POST['costo_unitario']}',
	precio_menudeo = '{$_POST['precio_menudeo']}',
	precio_mayoreo = '{$_POST['precio_mayoreo']}',
	descripcion_productos = '{$_POST['descripcion_productos']}',
	unidad_productos = '{$_POST['unidad_productos']}',
	ganancia_menudeo_porc = '{$_POST['ganancia_menudeo_porc']}',
	min_productos = '{$_POST["min_productos"]}',
	id_departamentos = '{$_POST["id_departamentos"]}',
	piezas = '{$_POST["piezas"]}',
	ubicacion = '{$_POST["ubicacion"]}',
	estatus_productos = '{$_POST["estatus_productos"]}'
	;
	
	";
	if(mysqli_query($link,$guardarProductos)){
		$respuesta['estatus'] = "success";
		$id_producto = mysqli_insert_id($link);
		}else{
		$respuesta['estatus'] = "error";
		$respuesta['mensaje'] = "Error en ".$guardarProductos.mysqli_error($link);
	}
	
	echo json_encode($respuesta);
?>