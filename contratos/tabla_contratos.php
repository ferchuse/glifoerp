<?php
	include("../conexi.php");
	$link = Conectarse();
	
	$meses = [
	1 => "ENERO",
	2 => "FEBRERO",
	3 => "MARZO",
	4 => "ABRIL",
	5 => "MAYO",
	6=> "JUNIO",
	7 => "JULIO",
	8 => "AGOSTO",
	9 => "SEPTIEMBRE",
	10 => "OCTUBRE",
	11 => "NOVIEMBRE",
	12 => "DICIEMBRE"
	
	];
	
	
	$lista_clientes = [];
	
	$consulta = "
	SELECT
	*
	FROM contratos 
	LEFT JOIN clientes USING(id_clientes)
	
	
	";
	
	for($i = 1; $i <= 12 ; $i++){
		
		$consulta.="
		LEFT JOIN (
		SELECT 
		id_clientes,
		importe as importe_$i,
		estatus as estatus_$i,
		fecha as fecha_$i,
		link_pago as link_pago_$i
		
		FROM cargos
		WHERE MONTH(fecha) = $i
		AND YEAR(fecha) = 2021
		
		) as t_mes_$i
		USING (id_clientes)
		
		";
		
	}
	
	
	
	
	$consulta.="
	ORDER BY
	{$_GET["sort"]} {$_GET["order"]}
	";
	
	
	$result = mysqli_query($link, $consulta) or die("<pre>Error en $consulta" . mysqli_error($link) . "</pre>");
	
	while ($fila = mysqli_fetch_assoc($result)) {
		
		$lista_clientes[] = $fila;
	}
?>
<pre >
	<?php
		$importe_mes[$i]
		
		// echo $consulta; ?>
</pre>

<table class="table table-hover" id="tabla_registros">
	<thead class="">
		<tr>
			<th class="text-center"><a class="sort" href="#!" data-columna="razon_social_clientes">Cliente</a> </th>
			
			<?php
				for($i = 1; $i <= 12 ; $i++){
				?>
				
				<th class="text-center">
					<?php echo $meses[$i] ?>
				</th>
				<?php
				}
			?>
			
			<th class="text-center">Acciones</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$importe_mes=[];
			foreach ($lista_clientes as $i_fila => $cliente) {
				
			?>
			<tr class="text-center">
				<td>
					<?php echo $cliente["razon_social_clientes"] ?>
				</td>
				
				
				
				<?php
					for($i = 1; $i <= 12 ; $i++){
					$importe_mes[$i] += $cliente["importe_$i"];
					?>
					<td>
						$<?php 
							
							
							echo number_format($cliente["importe_$i"])."<br>"; 
							
							switch($cliente["estatus_$i"]){
								
								case "Pendiente":
								$badge = "danger";
								break;
								case "Inactivo":
								$badge = "secondary";
								break;
								case "Pagado":
								$badge = "success";
								break;
								
							}
							
							echo "<span class='badge badge-$badge'>{$cliente["estatus_$i"]}</span>"; 
							echo "<br>"; 
							echo date("d/M", strtotime($cliente["fecha_$i"]))."<br>"; 
							echo "<a href='{$cliente["link_pago_$i"]}' >Link</a>"; 
							
							
							
							
						?>
						
						</td>
						
						<?php
					}
				?>
				
				
				<td>
				<button class="btn btn-success btn_cargos" data-id_registro="<?php echo $cliente["id_clientes"] ?>" data-saldo="<?php echo $cliente["saldo"] ?>">
						+ <i class="fa fa-dollar-sign"></i> Cargo
					</button>
					
				</td>
				
			</tr>
			<?php
			}
		?>
	</tbody>
	<tfoot>
		<tr class="text-center bg-info text-white h5">
			
			<td  class=""> <?php echo count($lista_clientes); ?> </td>
			
			<?php
				
				foreach ($importe_mes as $i => $total) {
					echo "<td>$".number_format($total)."</td>";
				}
			?>
			
			
			
			<td></td>
			
		</tr>
	</tfoot>
</table>



