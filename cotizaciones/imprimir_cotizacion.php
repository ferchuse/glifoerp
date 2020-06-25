<?php
	
	include("../conexi.php");
	$link = Conectarse();
	$empresa = "Glifo Media";
	
	
	$consulta = "SELECT * FROM cotizaciones
	LEFT JOIN vendedores USING (id_vendedores)
	LEFT JOIN clientes USING (id_clientes)
	WHERE id_cotizaciones ={$_GET["id_registro"]}";
	
	$result = mysqli_query($link, $consulta);
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$filas[] = $fila;
	}
	
	$consulta_detalle = "SELECT
	*
	FROM
	cotizaciones_detalle
	
	WHERE
	id_cotizaciones = {$_GET["id_registro"]}
	";
	
	$result_detalle = mysqli_query($link, $consulta_detalle);
	
	while ($fila = mysqli_fetch_assoc($result_detalle)) {
		$fila_detalle[] = $fila;
	}
	
	// echo "<pre>".$consulta."</pre>";
	// echo "<pre>".$consulta_detalle."</pre>";
	
?>


<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		<title>Cotización</title>
		
		
		<?php include("../styles.php"); ?>
		
	</head>
	
	<body>
		<div class="container h4">
			<section class="mt-3 ">
				<div class="row">
					
					
					<div class="col-9">
						<h3 class="text-center">
							<strong><?= $empresa?></strong>
						</h3>
						
						<h3 class="text-center">
							<strong>Cotización</strong>
						</h3>
						
						<div class="row">
							<div class="col-sm-3"><strong>Folio:</strong></div>
							<div class="col-sm-8"><?php echo $filas[0]["id_cotizaciones"] ?></div>
						</div>
						
						<div class="row">
							<div class="col-sm-3"><strong>Fecha:</strong></div>
							<div class="col-sm-8"><?php echo date("d/m/Y", strtotime($filas[0]["fecha_cotizaciones"])); ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3"><strong>Cliente:</strong></div>
							<div class="col-sm-8"><?php echo($filas[0]["razon_social_clientes"]); ?></div>
						</div>
						<div class="row">
							<div class="col-sm-3"><strong>Vendedor:</strong></div>
							<div class="col-sm-8"><?php echo($filas[0]["nombre_vendedores"]); ?></div>
						</div>
						
						
					</div>
					
					
					
					<div class="col-3 text-right">
						<img class="img-fluid" src="../img/logo.png" alt="">
					</div>
				</div>
				
				<div class="mt-3 row">
					<table id="tabla_venta" class="col-12 table table-hover table-bordered table-condensed">
						
						<thead class="bg-info">
							
							<tr>
								<th class="text-center">Cantidad</th>
								<th class="text-center">Descripción</th>
								<th class="text-center">Precio</th>
								<th class="text-center">Importe</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								foreach ($fila_detalle as $i => $producto) { ?>
								<tr>
									<td class="text-center">
										<?php echo number_format($producto["cantidad"]) ?>
									</td>
									<td class="">
										<b><?php echo $producto["descripcion"] ?></b> <br>
										<?php echo nl2br($producto["notas"]) ?>
										
										
										
									</td>
									<td class="text-center"><?php echo number_format($producto["precio"]) ?></td>
									<td class="text-center"><?php echo number_format($producto["importe"]) ?></td>
								</tr>
								<?php 
								}
							?>
						</tbody>
					</table>
				</div>
			</section>
			
			<section class="mt-5 lead">
				<div class="row">
					<div class="col-sm-2 col-6 h3 text-right">
						<label for="">Artículos: </label> 
					</div>
					<div class="col-sm-2 col-6 h3">
						<?php echo $filas[0]["articulos"]?>
					</div>
					
					
					
					<?php if($filas[0]["descuento"] > 0 ){?>
						<div class="col-sm-6 col-6 h3 text-right ">
							Subtotal:
						</div>
						<div class="col-sm-2 col-6 h3 text-right">
							$<?php echo number_format($filas[0]["subtotal"],2)?>
						</div>
						
						<div class="offset-sm-4 col-sm-6 colcol-6 h3 text-right ">
							Descuento:
						</div>
						<div class="col-sm-2 col-6 h3 text-right">
							$<?php echo number_format($filas[0]["descuento"],2)?>
						</div>
						
						<div class="offset-sm-4 col-sm-6 col-6 h3 text-right ">
							Total:
						</div>
						<div class="col-sm-2 col-6 h3 text-right">
							$<?php echo number_format($filas[0]["total"],2)?>
						</div>
						
						<?php
							
						}
						else{ ?>
						
						
						<div class="col-sm-6 col-6 h3 text-right ">
							Total:
						</div>
						<div class="col-sm-2 col-6 h3 text-right">
							$<?php echo number_format($filas[0]["total"],2)?>
						</div>
						<?php
							
							
						}
						
					?>
					
				</div>
				</section>
				<pre ><?php echo $filas[0]["condiciones_pago"]?></pre>
				
				</div>
				</body>
				
					</html>											