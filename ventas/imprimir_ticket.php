<?php
	
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "compras";
	
	
	$consulta = "SELECT * FROM ventas
	LEFT JOIN vendedores USING (id_vendedores)
	LEFT JOIN ventas_detalle USING (id_ventas)
	LEFT JOIN productos USING (id_productos)
	LEFT JOIN clientes USING (id_clientes)
	WHERE id_ventas={$_GET["id_registro"]}";
	
	$result = mysqli_query($link, $consulta);
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$filas[] = $fila;
	}
	
	$consulta_detalle = "SELECT
	cantidad,
	descripcion,
	precio,
	importe
	FROM
	ventas_detalle
	WHERE
	id_ventas = {$_GET["id_registro"]}
	";
	
	$result_detalle = mysqli_query($link, $consulta_detalle);
	
	while ($fila = mysqli_fetch_assoc($result_detalle)) {
		$fila_detalle[] = $fila;
	}
	
	$nombre_empresa= "GLIFO MEDIA";
	
?>


<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		<title>Nota de Remisión</title>
		
		
		<link rel="stylesheet" 
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
		
		
		
		<style>
			
			@media print{
			
			.productos{
			font: 13px "Times New Roman", Times, serif;
			}
			
			.ticket{
			width:9.6cm !important;
			}
			
			.direccion{
			font-size:14px;
			}
			
			
			@page {
			
			size:10cm !important;
			@top-left {
			content: "Título";
			}
			@top-right {
			content: "Pág. " counter(page);
			}
			}
			}
			
			
		</style>
	</head>
	
	<body >
		<div class="container-fluid "  >
			<div class="ticket"  >
				
				<div class="text-center m-3">
					<img src="logo_fix.jpg" class="img-fluid">
				</div>
				
				<div class="text-center" >
					<strong>FIX ZUMPANGO</strong> <br> <br>
					<div class="direccion">
						95/24 México, S de R. L de C.V.<br>
						AV MELCHOR OCAMPO 36 CP 55600, BARRIO SAN JUAN, <br>
						ZUMPANGO, ESTADO DE MEXICO, MÉXICO <BR><BR>
						R.F.C. NMQ050902HWD<BR>
						Mail: zumpango@fixferreterias.com <BR>
						Telefono: 59-1100-1294 <BR><BR>
						
						<strong>ANTICIPO 16678</strong>
						
					</div>
				</div>
				
				
				
				<div class="mt-3 row">
					<table id="" class="table-condensed productos" style="width:95%">
						
						<thead class="">
							
							<tr>
								<th class="text-center">CANT</th>
								<th class="text-center">CODIGO</th>
								<th class="text-center">DESCRIPCIÓN</th>
								<th class="text-center">PU</th>
								<th class="text-center">IMPORTE</th>
							</tr>
						</thead>
						<tbody>
							
							<tr style="border-bottom: solid 1px">
								<td>1</td>
								<td>24121</td>
								<td>ESMU-112P  Escalera de Mu</td>
								<td>$2409.49</td>
								<td>$2409.49</td>
							</tr>
							
						</tbody>
						<tfoot style="font-size: 14px;">
							<tr >
								<td></td>
								<td></td>
								<td class="text-right">Subtotal</td>
								<td colspan="2" class="text-right">$2,409.49</td>
							</tr>
							<tr>
								<td></td>
								<td></td>
								<td></td>
								<td class="text-right">IVA</td>
								<td colspan="2" class="text-right"> $385.51</td>
							</tr>
						</tfoot>
					</table>
					<h4 >TOTAL A PAGAR: &nbsp&nbsp&nbsp $2,795.00</h4 ><br>
					
					DOS MIL SETESCIENTOS NOVENTA Y CINCO CON 00/100
					<br>
					Metodo de Pago
					
					<div class="col-8"><b>Efectivo </b> 	</div>
					<div class="col-4"><b>$2,795.00 </b> 	</div>
					
					<div class="col-8"><b>Total Recibido </b>	</div>
					<div class="col-4"><b>$2,800.00 </b> 	</div>
					
					<div class="col-8"><b>Cambio </b>	</div>
					<div class="col-4"><b>$5.00 </b> 	</div>
					
					
					Fecha 17/02/2020 08:35:28 a.m. <br>
					CLIENTE FERNANDO GUZMAN AGUADO <br>
					RFC: GUAF880601NA6 <br>
					Lista de Precios 3 <br>
					Email : sistemas@glifo.mx <br>
					
					
					<div class="text-center"> FACTURA ELECTRONICA</div> <br>
					
					
					USO CFDI: G03- gastos en general <br>
					Método de Pago:PUE-Pago en una sola exhibición <br>
					Forma de Pago: 01-Efectivo <br>
					
					
					Folio: FAAA33F5-5BFD-5F47-A433-7FF6D72C0142 <br>
					
					FECHA Y HORA DE CERTIFICACIÓN DEL CFDI:  <br>
					2020-02-017T09:20:57 <br>
					CADENA ORIGINAL DEL COMPLEMENTO DE CERTIFICACIÓN DEL SAT 
					||1.0|FAAA33F5-5BFD-5F47-A433-7FF6D72C0142|2020-02-07T17:20:57|AcwM1WoDC8TWeXIXKfw4l+OY1Be5fpkxTmLSsWEWfNf3Y3PtwlS9ysN2qWrfGZ0v
					QChkgHeg4wO1kUv2pOOzOndvoc2bEGtvz33iPQARBlf2hzY7ry474E7cWNH87IH5LzrNRZZz2x4MtkVmdR6HhNkD2UzT8WH32NJOwSjYGejriNWZyL/zR0Di0
					37tKETKD4yg/yAZonPlnNZrgerwGfy/PWksiSwqMYtvzKeMAy7CO7EY5fU1i+ybxFQJXTBDA55khHyq1cOgeI9CQm0LGcUZ588ffrXUvuWL02kculMNe0kGirEtbg
					Pcc07y3Kzi9rSHTRwVBywHE5t59AnNtA==|00001000000502000436||
					<br>
					<br>
					Cajero: Jesus Armando<br>
					ARTICULOS :1<br>
					<br>
					http://www.truper.com.mx/csat.php<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>____
					
				</div>
			</div>
		</div>
	</body>
	
</html>																