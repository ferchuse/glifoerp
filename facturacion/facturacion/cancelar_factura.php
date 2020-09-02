<?php
	
	// error_reporting(E_ERROR);                        
	// include_once "../../sdk2.php";
	// $datos['PAC']['usuario'] = "DEMO700101XXX";
	// $datos['PAC']['pass'] = "DEMO700101XXX";
	// $datos['modulo']="cancelacion2018"; 
	// $datos['accion']="cancelar";                                                  
	// $datos["produccion"]="NO"; 
	
	// $datos["uuid"]="25d57a90-77cc-4fe2-acf6-67a3c2f2508d";
	// $datos["rfc"] ="LAN7008173R5";
	// $datos["password"]="12345678a";
	// $datos["b64Cer"]="../../certificados/lan7008173r5.cer";
	// $datos["b64Key"]="../../certificados/lan7008173r5.key";
	// $res = mf_ejecuta_modulo($datos);
	
	
	
	header("Content-Type: application/json");
	error_reporting(E_ERROR);
	session_start();
	date_default_timezone_set('America/Mexico_City');
	
	include_once("../../conexi.php");
	include_once "sdk2.php";
	
	$link = Conectarse();
	$respuesta = array();
	
	function getEmisor($link,$id_emisores ){
		$respuesta = "";
		$query = "SELECT * FROM emisores 
		WHERE id_emisores = '$id_emisores'
		";
		
		$result = mysqli_query($link,$query) ;
		
		if(!$result){
			return "<option value=''>Ocurrio un error".mysqli_error($link)."</option>"; 
		}
		else{
			while($fila = mysqli_fetch_assoc($result)){
				$respuesta = $fila;
			}
		}
		return $respuesta; 
	}
	
	$emisor = getEmisor($link, 1);
	
	
	
	$rfc = $emisor["rfc_emisores"];
	
	$datos['cancelar']='SI';
	$datos['PAC']['usuario'] = $rfc;
	$datos['PAC']['pass'] =  $emisor["password"];
	$datos['modulo']="cancelacion2018"; 
	$datos['accion']="cancelar";   
	
	
	$datos["produccion"]="SI"; 
	
	
	$datos["uuid"] = $_POST["uuid"];
	$datos["rfc"] = $rfc;
	$datos["password"] = "estaGab2";
	$datos["b64Cer"] = "certificados/$rfc.cer";
	$datos["b64Key"] = "certificados/$rfc.key";
	
	$respuesta["datos"]= $datos;
	
	$respuesta["respuesta_pac"]= mf_ejecuta_modulo($datos);
	
	
	if($respuesta["respuesta_pac"]["codigo_mf_numero"] == 0){
		
		$mensaje_original_pac_json =  json_decode($respuesta["respuesta_pac"]["mensaje_original_pac_json"] , true);
		
		$acuse = $mensaje_original_pac_json["CancelarCSDResult"];
		
		// Actualizar estatus de Factura a CANCELADO
		$update_factura	= "UPDATE facturas SET 
		cancelada = 1, 
		motivo_cancelacion = '".$_POST["motivo_cancelacion"]."' 
		WHERE id_facturas = '".$_POST["id_facturas"]."'";
		
		if(mysqli_query($link, $update_factura)){
			$respuesta["update_factura"]["estatus"]  = "success";
			$respuesta["update_factura"]["mensaje"]  = "CFDI CANCELADO CORRECTAMENTE";
			$respuesta["update_factura"]["query"]  = $update_factura;
		}
		else{
			$respuesta["update_factura"]["estatus"]  = "error";
			$respuesta["update_factura"]["mensaje"]  = mysqli_error($link);
			
		}
		
		
		if(!file_put_contents("acuses/".$_POST["folio_facturas"].'.xml',$acuse )){
			$respuesta["acuse"]["estatus"]  = "success";
			$respuesta["acuse"]["mensaje"]  = "Acuse Creado Correctamente";
			$respuesta["acuse"]["ruta"]  = "acuses/".$_POST["folio_facturas"].'.xml';
		}
		else{
			$respuesta["acuse"]["estatus"]  = "error";
		}
	}
	
	echo json_encode($respuesta);
	
	
	
?>
