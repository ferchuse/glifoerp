<?php
	
	include("../conexi.php");
	$link = Conectarse();
	$lista_transacciones = [];
	
	
	$consulta = "
	SELECT
	id_ventas AS id_transaccion,
	'CARGO' as tipo,
	'ventas' AS tabla,
	fecha_ventas AS fecha,
	CONCAT('VENTA #', id_ventas, '<br>', lista_conceptos) as concepto,
	total AS importe,
	razon_social_clientes
	FROM
	ventas
	LEFT JOIN clientes USING(id_clientes)
	LEFT JOIN (
	SELECT id_ventas, 
	GROUP_CONCAT(descripcion SEPARATOR '<br>') AS lista_conceptos
	
	FROM ventas_detalle
	GROUP BY id_ventas
	
	) as t_conceptos 
	USING (id_ventas)
	
	WHERE id_clientes = '{$_GET["id_clientes"]}'
	
	UNION
	
	SELECT
	id_abonos AS id_transaccion,
	'ABONO' as tipo,
	'abonos' as tabla,
	fecha,
	concepto,
	importe,
	razon_social_clientes
	FROM
	abonos
	LEFT JOIN clientes USING(id_clientes)
	WHERE id_clientes = '{$_GET["id_clientes"]}'
	
	UNION
	
	SELECT
	id_cargos AS id_transaccion,
	'CARGO' as tipo,
	'cargos' as tabla,
	fecha,
	concepto,
	importe,
	razon_social_clientes
	FROM
	cargos
	LEFT JOIN clientes USING(id_clientes)
	WHERE id_clientes = '{$_GET["id_clientes"]}'
	
	ORDER BY
	fecha 
	";
	
	
	$result = mysqli_query($link,$consulta) or die ("<pre>Error en $consulta". mysqli_error($link). "</pre>");
	
	while($fila = mysqli_fetch_assoc($result)){
		
		$lista_transacciones[] = $fila;
		
	}
?>
<pre hidden>
	<?= $consulta;?>
</pre>


<?php
	if(count($lista_transacciones) > 0){
	?>
	
	<h4 class="d-none d-print-block">Estado de Cuenta <?=$lista_transacciones[0]["razon_social_clientes"] ?>
	</h4>
	<div class="table-responsive">
		<table class="table table-hover ">
			<tr>
				<th class="text-center">Fecha</th>
				<th class="text-center">Concepto</th>
				<th class="text-center">Cargo</th>
				<th class="text-center">Abono</th>
				<th class="text-center">Saldo</th>
				<th class="text-center d-print-none">Acciones</th>
			</tr>
			<?php 
				$cargos= 0;
				$abonos= 0;
				$saldo= 0;
				foreach($lista_transacciones AS $i => $transaccion){
					
				?>
				<tr class="text-center">
					
					<td><?php echo date("d/m/Y", strtotime($transaccion["fecha"]));?></td>
					<td>
						<?php
							switch($transaccion["tabla"]){
							case "ventas":
							?>
							<a target="_blank" href="../ventas/imprimir_ventas.php?id_registro=<?= $transaccion["id_transaccion"] ?>">
								<?php echo $transaccion["concepto"];?>
							</a>
							<?php
								break;
								case "cargos":
							?>
							<a target="_blank" href="imprimir_cargos.php?id_registro=<?= $transaccion["id_transaccion"] ?>">
								<?php echo $transaccion["concepto"];?>
							</a>
							<?php
								break;
								case "abonos":
							?>
							<a target="_blank" href="imprimir_abonos.php?id_registro=<?= $transaccion["id_transaccion"] ?>" class="text-success">
								<?php echo $transaccion["concepto"];?>
							</a>
							<?php
								break;
							}
						?>
						
					</td>
					
					<?php if($transaccion["tipo"] == "CARGO"){
						$cargos+=$transaccion["importe"];
						$saldo+=$transaccion["importe"];
					?>
					<td>$<?php echo number_format($transaccion["importe"]);?></td>
					<td>-</td>
					
					<?php
					}
					else{
						$abonos+=$transaccion["importe"]; 
						$saldo-=$transaccion["importe"]; 
						
					?>
					
					<td>-</td>
					<td>$<?php echo number_format($transaccion["importe"]);?></td>
					
					<?php	
					}
					?>
					
					<td>$<?php echo number_format($saldo);?></td>
					<td class="d-print-none">
						<button class="btn btn-danger btn_borrar_transaccion" 
						data-id_registro="<?php echo $transaccion["id_transaccion"]?>"
						data-tipo="<?php echo $transaccion["tipo"]?>"
						>
							<i class="fa fa-trash"></i>
						</button>
						
					</td>
					
				</tr>
				<?php
				}
			?>
			<tfoot class="h5 text-white bg-secondary text-right">
				<tr>
					<td>TOTALES:</td>
					<td></td>
					<td>$<?php echo number_format($cargos);?></td>
					<td>$<?php echo number_format($abonos);?></td>
					<td>$<?php echo number_format($saldo);?></td>
					
				</tr>
			</tfoot>
		</table>
	</div>
	<?php
	}
	else{
		
		echo "<div class='alert alert-warning'>No hay Transacciones</div>";
	}
?>