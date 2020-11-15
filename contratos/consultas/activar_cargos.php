<?php
	// header("Content-Type: application/json");
	include ("../../conexi.php");
	include ("../../lib/mpago/vendor/autoload.php");
	include ("../../lib/sendinblue/vendor/autoload.php");
	
	$link = Conectarse();
	$respuesta = Array();
	$lista_cargos = Array();
	
	
	// $token = "TEST-2599583807479799-071201-647536aad8d58734906bd5ae1ea91d39-88027643"; //TEST GLIFO
	
	// $token = "APP_USR-2599583807479799-071201-57bd8dadef36a95264421bf038610bd6-88027643"; //PROD GLIFO
	$token = "TEST-3710248158721318-103003-8334d320761e1338f9c10925c43c1841-665671200"; //TEST MERCHENT
	
	$fecha_actual = date("Y-m-d");
	$fecha_actual = date("2021-01-17");
	$fecha_actual = "2021-01-17";
	
	
	
	$consulta = "SELECT * FROM cargos 
	LEFT JOIN clientes USING(id_clientes)
	WHERE estatus = 'Inactivo'
	AND fecha  <= '{$fecha_actual}'
	
	";
	
	$result = mysqli_query($link, $consulta);
	
	$respuesta["consulta"] = $consulta;
	
	if($result){
		while($fila = mysqli_fetch_assoc($result)){
			
			$lista_cargos[] = $fila;
		}
		
	}	
	else{
		$respuesta["status"] = "error";
		$respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
	}
	
	echo "<pre>";
	print_r($lista_cargos);
	echo "</pre>";
	
	//Por cada Cargo crear link y actualizar columna link_pago Enviar correo
	
	MercadoPago\SDK::setAccessToken($token);
	
	foreach($lista_cargos as $cargo){
		
		
		echo $cargo["concepto"]."<br>";
		echo $cargo["importe"]."<br>";
		echo $link_pago ;
		
		// Crea un objeto de preferencia
		$preference = new MercadoPago\Preference();
		
		// Crea un ítem en la preferencia 
		$item = new MercadoPago\Item();
		$item->title = $cargo["concepto"];
		$item->quantity = 1;
		$item->unit_price =  $cargo["importe"];
		
		// $preference->back_urls = array(
		// "success" => "https://cumbresdemexico.com.mx/2020/web/mi_cuenta.php",
		// "failure" => "https://cumbresdemexico.com.mx/2020/web/mi_cuenta.php",
		// "pending" => "https://cumbresdemexico.com.mx/2020/web/mi_cuenta.php" 
		// );
		
		$preference->statement_descriptor =   $cargo["concepto"];
		$preference->external_reference = $cargo["id_cargos"];
		// $preference->auto_return = "approved"; 
		$preference->items = array($item);
		$preference->notification_url = "https://glifo.mx/app/webhook_mpago.php";
		$preference->save();
		
		echo "<pre hidden>"; print_r($preference); echo "</pre>";
		// $preference->status();
		$link_pago =  $preference->init_point;
		
		
		echo $cargo["concepto"]."<br>";
		echo $cargo["importe"]."<br>";
		echo $link_pago ;
		
		
		
		
		// $consulta = "UPDATE cargos SET 
		// estatus = 'Pendiente'
		// link_pago = '$link_pago'
		
		// WHERE id_cargos = {$cargo["id_cargos"]}
		
		// ";
		
		// $result = mysqli_query($link, $consulta);
		
		// $respuesta["consulta"] = $consulta;
		
		// if($result){
			// $respuesta["status"] = "success";
			// $respuesta["mensaje"] = "Guardado";
			
			// $folio = mysqli_insert_id($link);
			
		// }	
		// else{
			// $respuesta["status"] = "error";
			// $respuesta["mensaje"] = "Error $consulta  ".mysqli_error($link);		
		// }
		
		
		
		
		
		//Enviar por correo notificacion de cargo pendiente y link de pago 
		
		
		
	}
	
	
	
	
	
	echo json_encode($respuesta);
?>	