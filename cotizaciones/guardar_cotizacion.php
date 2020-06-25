<?php 
	include("../conexi.php");
	$link = Conectarse();
	// $tipo_cambio = 19.57;
	 $tipo_cambio = 1;
	
	//TODO Separar por funciones
	
	
	$insertar_venta = "
	
	INSERT INTO cotizaciones SET
	id_cotizaciones = '{$_POST["id_ventas"]}',
	fecha_cotizaciones = '{$_POST["fecha_ventas"]}',
	hora_cotizaciones = '{$_POST["hora_ventas"]}',
	id_usuarios = '{$_COOKIE['id_usuarios']}',
	estatus_ventas = 'APROBACIÓN PENDIENTE',
	id_vendedores = '{$_POST['id_vendedores']}',
	id_clientes = '{$_POST['id_clientes']}',
	subtotal = '{$_POST['subtotal']}',
	descuento = '{$_POST['descuento']}',
	total = '{$_POST['total']}',
	condiciones_pago = '{$_POST['condiciones_pago']}',
	articulos = '{$_POST['articulos']}',
	sumar_importes = '{$_POST['sumar_importes']}'
	
	ON DUPLICATE KEY UPDATE
	
	id_cotizaciones = '{$_POST["id_ventas"]}',
	fecha_cotizaciones = '{$_POST["fecha_ventas"]}',
	hora_cotizaciones = '{$_POST["hora_ventas"]}',
	id_usuarios = '{$_COOKIE['id_usuarios']}',
	estatus_ventas = 'APROBACIÓN PENDIENTE',
	id_vendedores = '{$_POST['id_vendedores']}',
	id_clientes = '{$_POST['id_clientes']}',
	subtotal = '{$_POST['subtotal']}',
	descuento = '{$_POST['descuento']}',
	total = '{$_POST['total']}',
	condiciones_pago = '{$_POST['condiciones_pago']}',
	articulos = '{$_POST['articulos']}',
	sumar_importes = '{$_POST['sumar_importes']}'
	";
	
	
	$respuesta['insertar_venta'] = $insertar_venta;
	
	$result_movimiento = mysqli_query($link, $insertar_venta);
	
	if($result_movimiento){
		$respuesta['estatus_movimiento'] = 'success';
		$respuesta['mensaje_movimiento'] = 'Cotización Guardada';
		if($_POST["id_ventas"] == ''){
			$folio = mysqli_insert_id($link);
		}
		else{
			$folio = $_POST["id_ventas"];
		}
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
		
		
		// $respuesta['insertar_anticipo'] = $insertar_anticipo;
		
		// $result_anticipo = mysqli_query($link, $insertar_anticipo);
		
		// if($result_anticipo){
			// $respuesta['estatus_anticipo'] = 'success';
			// $respuesta['mensaje_anticipo'] = 'Anticipo Guardado';
		// }
		// else{
			// $respuesta['estatus_anticipo'] = 'error';
			// $respuesta['mensaje_anticipo'] = "Error en insertar_anticipo :".mysqli_error($link);
		// }
		
	}
	
	//Borra los productos anteriores si la venta ya existe
	if($_POST["id_ventas"] != ''){
		
		$borrar_productos = "DELETE FROM cotizaciones_detalle WHERE id_cotizaciones = '{$_POST["id_ventas"]}';
		";
		
		$result_borrar = mysqli_query( $link, $borrar_productos );
		
		$respuesta["result_borrar"] = $result_borrar.": ".mysqli_error($link) ;
	}
	
	//INSERTA LOS Producto
	
	foreach($_POST['productos'] as $indice => $producto){
		
		$ganancia = ($producto["precio"] - ($producto["costo_proveedor"] * $tipo_cambio)) *  $producto["cantidad"];
		// $respuesta["ganancia"][] = $ganancia_pesos;
		
		
		
		//INSERTA productos de cada movimiento
		
		$exist_nueva = $producto["existencia_anterior"] + $producto["cantidad"];
		
		$insertar_producto = "INSERT INTO cotizaciones_detalle SET
		id_cotizaciones = 	'$folio', 
		id_productos = 	'{$producto["id_productos"]}', 
		descripcion = 	'{$producto["descripcion"]}',
		notas = 	'{$producto["notas"]}',
		ganancia = 	'{$ganancia}',
		cantidad = 	'{$producto["cantidad"]}',
		importe = 	'{$producto["importe"]}',
		precio = 	'{$producto["precio"]}'
		";
		
		
		
		$result_productos = mysqli_query( $link, $insertar_producto );
		
		$respuesta["result_productos"] = $result_productos.": ".mysqli_error($link) ;
		
		$respuesta["insertar_producto"][] = $insertar_producto ;
		
		
		// actualiza existencias si es nueva venta
		if($_POST["id_ventas"] == ''){
			$update_existencia = "UPDATE productos SET existencia_productos = '$exist_nueva''
			WHERE id_productos = '{$producto["id_productos"]}'	"; 
			
			$result_existencia = mysqli_query( $link, $update_existencia );
			
			$respuesta["result_existencia"] = $result_existencia ;
		}
		
	}
	
	echo json_encode($respuesta);
?>				