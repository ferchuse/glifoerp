<?php 
	include("../conexi.php");
	$link = Conectarse();
	$tipo_cambio = 19.57;
	
	//TODO Separar por funciones
	
	
	$insertar_venta = "
	
	INSERT INTO ventas SET
	id_ventas = '{$_POST["id_ventas"]}',
	fecha_ventas = '{$_POST["fecha_ventas"]}',
	hora_ventas = '{$_POST["hora_ventas"]}',
	id_usuarios = '{$_COOKIE['id_usuarios']}',
	estatus_ventas = 'APROBACIÓN PENDIENTE',
	id_vendedores = '{$_POST['id_vendedores']}',
	id_clientes = '{$_POST['id_clientes']}',
	subtotal = '{$_POST['subtotal']}',

	iva = '{$_POST['iva']}',
	total = '{$_POST['total']}',
	articulos = '{$_POST['articulos']}'
	
	ON DUPLICATE KEY UPDATE
	
	id_ventas = '{$_POST["id_ventas"]}',
	fecha_ventas = '{$_POST["fecha_ventas"]}',
	hora_ventas = '{$_POST["hora_ventas"]}',
	id_usuarios = '{$_COOKIE['id_usuarios']}',
	estatus_ventas = 'APROBACIÓN PENDIENTE',
	id_vendedores = '{$_POST['id_vendedores']}',
	id_clientes = '{$_POST['id_clientes']}',
	subtotal = '{$_POST['subtotal']}',
	
	iva = '{$_POST['iva']}',
	total = '{$_POST['total']}',
	articulos = '{$_POST['articulos']}'
	";
	
	
	$respuesta['insertar_venta'] = $insertar_venta;
	
	$result_movimiento = mysqli_query($link, $insertar_venta);
	
	if($result_movimiento){
		$respuesta['estatus_movimiento'] = 'success';
		$respuesta['mensaje_movimiento'] = 'Venta Guardada';
		$folio = mysqli_insert_id($link);
		$respuesta['folio'] = $folio;
	}
	else{
		$respuesta['estatus_movimiento'] = 'error';
		$respuesta['mensaje_movimiento'] = "Error en $insertar_venta :".mysqli_error($link);
	}
	
	
	//SI EL anticipo > 0 agregar un abono 
	
	
	if($_POST["anticipo"] > 0){
		
		$insertar_anticipo = "INSERT INTO abonos SET
		fecha = '{$_POST["fecha_ventas"]}',
		id_clientes = '{$_POST["id_clientes"]}',
		importe = '{$_POST["anticipo"]}',
		concepto = 'ANTICIPO VENTA #$folio'
		";
		
		
		$respuesta['insertar_anticipo'] = $insertar_anticipo;
		
		$result_anticipo = mysqli_query($link, $insertar_anticipo);
		
		if($result_anticipo){
			$respuesta['estatus_anticipo'] = 'success';
			$respuesta['mensaje_anticipo'] = 'Anticipo Guardado';
		}
		else{
			$respuesta['estatus_anticipo'] = 'error';
			$respuesta['mensaje_anticipo'] = "Error en insertar_anticipo :".mysqli_error($link);
		}
		
	}
	
	//INSERTA LOS Producto
	
	foreach($_POST['productos'] as $indice => $producto){
		
		$ganancia = ($producto["precio"] - ($producto["costo_proveedor"] * $tipo_cambio)) *  $producto["cantidad"];
		// $respuesta["ganancia"][] = $ganancia_pesos;
		
		
		
		//INSERTA productos de cada movimiento
		
		$exist_nueva = $producto["existencia_anterior"] + $producto["cantidad"];
		
		$insertar_producto = "INSERT INTO ventas_detalle SET
		id_ventas = 	'$folio', 
		id_productos = 	'{$producto["id_productos"]}', 
		descripcion = 	'{$producto["descripcion"]}',
		ganancia = 	'{$ganancia}',
		cantidad = 	'{$producto["cantidad"]}',
		importe = 	'{$producto["importe"]}',
		precio = 	'{$producto["precio"]}'
		";
		
		
		
		$result_productos = mysqli_query( $link, $insertar_producto );
		
		$respuesta["result_productos"] = $result_productos.": ".mysqli_error($link) ;
		$respuesta["insertar_producto"][] = $insertar_producto ;
		
		
		// actualiza existencias
		
		$update_existencia = "UPDATE productos SET existencia_productos = '$exist_nueva''
		WHERE id_productos = '{$producto["id_productos"]}'	"; 
		
		$result_existencia = mysqli_query( $link, $update_existencia );
		
		$respuesta["result_existencia"] = $result_existencia ;
		
	}
	
	echo json_encode($respuesta);
	?>			