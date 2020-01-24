<?php 
header("Content-Type: application/json");
include('../../conexi.php');
$link = Conectarse();
$respuesta = array();


$consulta = "DELETE ventas,
 ventas_detalle
FROM
	ventas
INNER JOIN ventas_detalle USING (id_ventas)
WHERE
	id_ventas = '{$_POST['id_registro']}'";

$respuesta['consulta'] = $consulta;

if(mysqli_query($link,$consulta)){
	$respuesta['estatus'] = 'success';
}else{
	$respuesta['estatus'] = 'error';
	$respuesta['error'] = 'Error en DB'.mysqli_error($link);
}

echo json_encode($respuesta);
?>