<?php
	
	include("../../conexi.php");
	$link = Conectarse();
	$total = 0;
	$registros= [];
	$total_comisiones = 0;
	
	$consulta = "SELECT
	*
	FROM
	abonos
	LEFT JOIN clientes USING(id_clientes)
	WHERE 
	DATE(fecha) BETWEEN '{$_POST['fecha_inicio']}'
	AND '{$_POST['fecha_fin']}'  
	
	";
	$result = mysqli_query($link, $consulta);
	
	if($result){
		while($fila = mysqli_fetch_assoc($result)){
			$registros[] = $fila;
		}
	}
	else{ 
		die("Error en la consulta $consulta". mysqli_error($link));
	}
?>
<pre hidden>
	<?php echo $consulta;?>
</pre>
<hr>
<?php if(count($registros) > 0){?>
	
	<table class="table table-striped table-hover">
		<thead>
			<tr class="success">
				<th>Folio</th>
				<th>Fecha</th>
				<th>Cliente</th>
				<th>Concepto</th>
				<th>Importe</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			
			<?php 
				
				foreach($registros AS $i=>$fila){	
					$total+=  $fila["importe"];
				?>
				<tr class="clickable" data-id_vendedores="<?php echo $fila["id_vendedores"];?>">
					<td><?php echo $fila["id_abonos"] ?></td> 
					<td class="text-right"><?php echo date("d-m-Y",strtotime($fila["fecha"])) ?></td> 
					<td class="text-right">
						
						<?php 
							echo $fila["razon_social_clientes"] . "<br>";
							if($fila["alias_clientes"] != $fila["razon_social_clientes"]){
								echo "(". $fila["alias_clientes"].")";
							}
							
							
						?>
						
					</td> 
					<td class="text-right"><?php echo $fila["concepto"] ?></td> 
					<td class="text-right"><?php echo $fila["importe"] ?></td> 
				</tr>
				<?php
				}
			?>
		</tbody>
		<tfoot class="bg-primary text-white">
			<tr class="">
				<td  colspan="4"><b>TOTALES</b></td> 
				<td class="text-right"><b>$<?php echo number_format($total) ?></b></td> 
			</tr>
			</tfoot>
			</table>
			<?php
			}
			else{
				
				echo "<div class='alert alert-warning'>No hay Ventas en este periodo</div>";
			}
?>