<?php 
	
	include('../../conexi.php');
	$link = Conectarse();
	
	
	$consulta = "SELECT
	*
	FROM
	ventas
	LEFT JOIN clientes USING(id_clientes)
	LEFT JOIN vendedores ON ventas.id_vendedores = vendedores.id_vendedores
	WHERE
	DATE(fecha_ventas) BETWEEN '{$_GET["fecha_inicial"]}'
	AND '{$_GET["fecha_final"]}'
	
	";
	
	if($_GET["id_clientes"] != ""){
		
		$consulta.="AND ventas.id_clientes' = '{$_GET["id_clientes"]}'";
		
	}
	
	
	$consulta.=" ORDER BY id_ventas DESC ";
	
	$result = mysqli_query($link,$consulta) or die ("<pre>Error en $consulta". mysqli_error($link). "</pre>");
	
	while($fila = mysqli_fetch_assoc($result)){
		
		$movimientos[] = $fila;
		
	}
	
	$registros = count($movimientos);
	
	
	
	
?>

<table class="table table-hover table-condensed table-bordered">
	<tr>
		<th class="text-center">Folio</th>                                                      
		<th class="text-center">Fecha</th>
		<th class="text-center">Cliente</th>
		<th class="text-center">Total</th>
		<th class="text-center">Estatus</th>
		<th class="text-center">Facturada</th>
		<th class="text-center">Acciones</th>
	</tr>
	<?php 
		$total = 0;
		foreach($movimientos AS $i => $fila){
			$total+=$fila["total"];
		?>
		<tr class="text-center">
			<td><?php echo $fila["id_ventas"];?></td>
			<td><?php echo date("d/m/Y", strtotime($fila["fecha_ventas"]));?></td>
			<td><?php echo $fila["razon_social_clientes"];?></td>
			<td class="text-right"><?php echo number_format($fila["total"],2);?></td>
			<td>
				
				<select class="form-control estatus_ventas" data-id_registro="<?php echo $fila["id_ventas"];?>">
					<option <?php echo $fila["estatus_ventas"] == "APROBACIÓN PENDIENTE" ? "selected" : "";?>>
						APROBACIÓN PENDIENTE 
					</option>
					<option <?php echo $fila["estatus_ventas"] == "PEDIDO SURTIDO" ? "selected" : "";?>>PEDIDO SURTIDO </option>
					<option <?php echo $fila["estatus_ventas"] == "ENTREGADO A CLIENTE" ? "selected" : "";?>>ENTREGADO A CLIENTE </option>
					<option <?php echo $fila["estatus_ventas"] == "SURTIDO PARCIAL" ? "selected" : "";?>>SURTIDO PARCIAL </option>
					<option <?php echo $fila["estatus_ventas"] == "CANCELADA" ? "selected" : "";?>>CANCELADA</option>
					
				</select>
				
				
			</td>
			<td><?php echo $fila["facturada"] == 0 ? "NO" : "SI";?></td>
			
			
			<td>
				<a href="imprimir_ventas.php?id_registro=<?php echo $fila["id_ventas"];?>" class="btn btn-sm btn-info btn_imprimir" target="_blank" 
				>
					<i class="fas fa-print" ></i> Reimprimir
				</a>
				
				<a href="../facturacion/facturas_nueva.php?id_ventas=<?php echo $fila["id_ventas"];?>" class="btn btn-sm btn-primary" target="_blank" 
				
				>
					<i class="fas fa-dollar-sign" ></i> Facturar
				</a>
				
				<a href="../inventarios/nuevo_movimiento.php?tipo_movimiento=SALIDA&tabla=ventas&folio=<?php echo $fila["id_ventas"]?>" class="btn btn-sm btn-success convertir_a_salida" type="button" 
				
				>
					<i class="fas fa-arrow-right" ></i>  Vale de Salida
				</a>
				
				
				<a href="nueva_venta.php?tipo_movimiento=VENTA&tabla=ventas&folio_ventas=<?php echo $fila["id_ventas"]?>" class="btn btn-sm btn-warning " type="button" 
				
				>
					<i class="fas fa-edit" ></i>  Editar
				</a>
				
				<button class="btn btn-sm btn-danger btn_borrar" type="button" 
				data-id_registro="<?php echo $fila["id_ventas"]?>"
				data-tabla="ventas" 
				data-id_campo="id_ventas" 
				>
					<i class="fas fa-trash" ></i> Eliminar
				</button>
				
			</td>
			
		</tr>
		<?php
			
		}
		
	?>
	<tfoot class="bg-secondary text-white"> 
		<tr class="text-left">
			<td colspan="2"><b><?php echo $registros;?> Registro(s)</b></td>
			<td colspan=""><b>Total:</b></td>
			<td class="text-right"><b>$ <?= number_format($total,2);?></b></td>
			<td colspan="3"></td>
		</tr>
	</tfoot>
</table>