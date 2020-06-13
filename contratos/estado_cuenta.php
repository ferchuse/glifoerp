<?php
	include("../../paginas/login/login_check.php");
	$nombre_pagina = "abonar_unidades";
	$id= "id_abonarunidades";
	$tabla = "abonar_unidades"; 
	
	include("../../conexi.php");
	include("../../funciones/generar_select.php");
	include("../../funciones/is_selected.php");
	include("../../funciones/pagos_atrasados.php");
	$link = Conectarse();
	$fecha_inicial = date("Y-m-01");
	
	$consulta_venta= "SELECT * 
	, importe - enganche- descuento - COALESCE(t_saldo.suma_abonos, 0) AS saldo_actual,
	COALESCE(t_saldo.suma_abonos, 0) AS suma_abonos,
	COALESCE(t_saldo.abonos_pagados, 0) AS abonos_pagados
	
	FROM ventas 
	LEFT JOIN(
	SELECT 
	id_ventas,
	COUNT(id_ventas) AS abonos_pagados,
	COALESCE(SUM(monto_abonos), 0) AS suma_abonos 
	FROM abonos
	WHERE id_ventas = '{$_GET[id_ventas]}'
	AND zona = '{$_GET[zona]}'
	) AS t_saldo
	USING(id_ventas)
	WHERE id_ventas = '{$_GET[id_ventas]}'
	AND zona = '{$_GET[zona]}'
	
	
	";
	
	$result_venta = mysqli_query($link, $consulta_venta) 
	or die("Error en <pre>$consulta_venta</pre>". mysqli_error($link));
	
	while($fila = mysqli_fetch_assoc($result_venta)){
		
		$fila_venta = $fila ;
		
	}
	
	
	$consulta_abonos= "SELECT * FROM abonos WHERE id_ventas = '{$_GET[id_ventas]}' AND zona = '{$_GET[zona]}' ORDER BY fecha_abonos	";
	
	$result_abonos = mysqli_query($link, $consulta_abonos) 
	or die("Error en <pre>$consulta_abonos</pre>". mysqli_error($link));
	
	while($fila = mysqli_fetch_assoc($result_abonos)){
		
		$fila_abono[] = $fila ;
		
	}
	
	$consulta_incidencias= "SELECT * FROM incidencias WHERE id_ventas = '{$_GET[id_ventas]}' AND zona = '{$_GET[zona]}' ORDER BY fecha_incidencias	";
	
	$result_incidencias = mysqli_query($link, $consulta_incidencias) 
	or die("Error en <pre>$consulta_incidencias</pre>". mysqli_error($link));
	
	while($fila = mysqli_fetch_assoc($result_incidencias)){
		
		$fila_incidencias[] = $fila ;
		
	}
	
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title >Estado de Cuenta</title>
		<?php include('../../styles.php')?>
	</head>
	<body id="page-top">
		<?php include("../../navbar.php")?>
		<div id="wrapper" class="d-print-none">
			<?php include("../../menu.php")?>	
			<div id="content-wrapper">		
				<div class="container-fluid">
					<?php
						
					?>
				</div>
				<div class="container-fluid">
					
					<!-- Breadcrumbs-->
					<ol class="breadcrumb">
						<li class="breadcrumb-item">
							<a href="#">Ventas</a>
						</li>
						<li class="breadcrumb-item">Estado de Cuenta </li>
						
					</ol> 
					<pre hidden  > 
						<?php echo $consulta_venta	;?>
					</pre>
					
					<pre hidden > 
						<?php echo var_dump($fila_abono)	;?>
					</pre>
					
					<?php if(count($fila_venta) == 0 ){
						
						die("<div class='alert alert-danger'>Cuenta ${_GET["id_ventas"]} No encontrada</div>");
					}?>
					<div class="d-print-block" id="reporte_impresion">
						<legend>
							Cuenta <b></b>
							<a class="btn btn-info btn-sm d-print-none float-right" href="impresion/imprimir_venta_david.php?zona=<?php echo $fila_venta["zona"];?>&id_ventas=<?php echo $fila_venta["id_ventas"];?>">
								<i class="fas fa-print"></i> Imprimir
							</a>
						</a>
					</legend>
					
				</div>
				<?php 
					if($fila_venta["estatus_ventas"] == 'CANCELADO'){
						echo "<div class='alert alert-danger'> CANCELADA </div>";
						
					}
					
				?>
				
				
				<!-- Formulario Estado de Cuenta-->
				<form id="form_edicion" disabled>
					<!-- Formulario-->
					<div class="row"> 
						<!-- Datos Cliente y Vendedor!-->
						<div class="col-6">
							<div class="form-group mb-2">
								<label for="zona">Zona:</label>
								<?php echo generar_select($link, "zonas", "zona", "zona", false, false, true, $_GET["zona"]) ?>
							</div>
							<input name="zona_anterior" value="<?php echo $fila_venta["zona"]?>" hidden>
							<label >Cuenta:</label>
							<input readonly class="form-control mb-2" name="id_ventas" id="id_ventas" value="<?php echo $fila_venta["id_ventas"] ?>">
							<label >Vendedor:</label>
							<input readonly class="form-control mb-2" name="vendedor" id="vendedor" value="<?php echo $fila_venta["vendedor"] ?>">
							<label >Día de Venta:</label>
							<input class="form-control mb-2" type="date" name="fecha_ventas" id="fecha_ventas" value="<?php echo $fila_venta["fecha_ventas"] ?>">
							<label >Cliente:</label>
							<input class="form-control mb-2" name="cliente" id="cliente" value="<?php echo $fila_venta["cliente"] ?>">
							<label >Teléfono:</label>
							<input class="form-control mb-2" name="telefono" id="telefono" value="<?php echo $fila_venta["telefono"] ?>">
							<label >Membresía:</label>
							<input class="form-control mb-2" name="membresia" id="membresia" value="<?php echo $fila_venta["membresia"] ?>">
							<label >Calle:</label>
							<input class="form-control mb-2" name="calle" id="calle" value="<?php echo $fila_venta["calle"] ?>">
							<label >Entre Calles::</label>
							<input class="form-control mb-2" name="entre_calles" id="entre_calles" value="<?php echo $fila_venta["entre_calles"] ?>">
							<label >A Lado De:</label>
							<input class="form-control mb-2" name="lado" id="lado" value="<?php echo $fila_venta["lado"] ?>">
							<label >Frente:</label>
							<input class="form-control mb-2" name="frente" id="frente" value="<?php echo $fila_venta["frente"] ?>">
							<label >Colonia:</label>
							<input class="form-control mb-2" name="colonia" id="colonia" value="<?php echo $fila_venta["colonia"] ?>">
							<label >Municipio:</label>
							<input class="form-control mb-2" name="municipio" id="municipio" value="<?php echo $fila_venta["municipio"] ?>">
							<label >Fachada:</label>
							<input class="form-control mb-2" name="fachada" id="fachada" value="<?php echo $fila_venta["fachada"] ?>">
							<label >Coordenadas:</label>
							<input class="form-control mb-2" name="ubicacion" id="ubicacion" value="<?php echo $fila_venta["ubicacion"] ?>">
						</div>
						<!-- Datos Producto & Venta!-->
						<div class="col-6">
							<label >Producto:</label>
							<input readonly class="form-control mb-2" name="nombre_productos" id="nombre_productos" value="<?php echo $fila_venta["nombre_productos"] ?>">
							<label >Importe:</label>
							<input class="form-control mb-2" name="importe" id="importe" value="<?php echo $fila_venta["importe"] ?>">
							<label >Descuento:</label>
							<input class="form-control mb-2" name="descuento" id="descuento" value="<?php echo $fila_venta["descuento"] ?>">
							<label >Enganche:</label>
							<input class="form-control mb-2" name="enganche" id="enganche" value="<?php echo $fila_venta["enganche"] ?>">
							<label >Fecha de Primer Abono:</label>
							<input class="form-control mb-2" type="date" name="fecha_enganche" id="fecha_enganche" value="<?php echo $fila_venta["fecha_enganche"] ?>">
							<label >Abono:</label>
							<input class="form-control mb-2" name="abono" id="abono" value="<?php echo $fila_venta["abono"] ?>">
							<label >Día de Cobro:</label>
							<select class="form-control mb-2" name="dia_cobranza" id="dia_cobranza" value="<?php echo $fila_venta["dia_cobranza"] ?>">
								<option value="" >Elige..</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "LUNES")?>>LUNES
								</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "MARTES")?>>MARTES</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "MIÉRCOLES")?>>MIÉRCOLES</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "JUEVES")?>>JUEVES</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "VIERNES")?>>VIERNES</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "SÁBADO")?>>SÁBADO</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "DOMINGO")?>>DOMINGO</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "SEMANAL")?>>SEMANAL</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "QUINCENAL")?>>QUINCENAL</option>
								<option <?php echo is_selected($fila_venta["dia_cobranza"] , "MENSUAL")?>>MENSUAL</option>
							</select>
							<label >Programado:</label>
							<select class="form-control mb-2" name="programado" id="programado" >
								<option value="" >Elige..</option>
								<option <?php echo is_selected($fila_venta["programado"] , "LUNES")?>>LUNES
								</option>
								<option <?php echo is_selected($fila_venta["programado"] , "MARTES")?>>MARTES</option>
								<option <?php echo is_selected($fila_venta["programado"] , "MIÉRCOLES")?>>MIÉRCOLES</option>
								<option <?php echo is_selected($fila_venta["programado"] , "JUEVES")?>>JUEVES</option>
								<option <?php echo is_selected($fila_venta["programado"] , "VIERNES")?>>VIERNES</option>
								<option <?php echo is_selected($fila_venta["programado"] , "SÁBADO")?>>SÁBADO</option>
								<option <?php echo is_selected($fila_venta["programado"] , "DOMINGO")?>>DOMINGO</option>
							</select>
							<label >Período:</label>
							<select class="form-control mb-2" name="periodo" id="periodo">
								<option <?php echo is_selected($fila_venta["periodo"] , "SEMANAL")?>>SEMANAL</option>
								<option <?php echo is_selected($fila_venta["periodo"] , "QUINCENAL")?>>QUINCENAL</option>
								<option <?php echo is_selected($fila_venta["periodo"] , "MENSUAL")?>>MENSUAL</option>
							</select>
							<label >Saldo Actual:</label>
							<input class="form-control mb-2" readonly value="<?php echo $fila_venta["saldo_actual"] ?>">
							<label >A Cuenta:</label>
							<input class="form-control mb-2" readonly value="<?php echo $fila_venta["suma_abonos"] ?>">
							<label >Núm. Abonos:</label>
							<input class="form-control mb-2" readonly value="<?php echo $fila_venta["abonos_pagados"] ?>">
							<label >Observaciones:</label>
							<input class="form-control mb-4" name="observaciones" id="observaciones" value="<?php echo $fila_venta["observaciones"] ?>">
							<!-- Buttons Cancelar & Guardar-->
							<?php 
								if ($fila_venta["estatus_ventas"] != "CANCELADO"){?>
								
								
								<button type="button" class="mt-3 btn btn-danger" data-toggle="modal" data-target="#modal_cancel">
									<i class="fas fa-times mr-1"></i>
									Cancelar
								</button>
								<button type="submit" class="mt-3 btn btn-success">
									<i class="fas fa-save mr-1"></i>
									Guardar
								</button>
								
								
								<?php	
									
								}
								
							?>
							
							
							
							
							
							
						</div>
					</div>
				</form>
				<!-- Fin Formulario Estado de Cuenta-->
				
				
				<!-- Modal Cancelar -->
				<div class="modal" id="modal_cancel" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Confirmar</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<!-- Modal Body -->
							<div class="modal-body">
								<h5 class="mt-1 mb-3">¿Estás seguro que deseas cancelar esta venta?</h5>
								<div class="custom-control custom-checkbox">
									<input value="SI" type="checkbox" class="mb-3 custom-control-input" id="devolver">
									<label class="mb-2 custom-control-label" for="devolver">
										Devolver producto al inventario.
									</label>
								</div>
							</div>
							<!-- Modal Footer -->
							<div class="modal-footer">
								<button type="button" class="btn btn-danger" data-dismiss="modal">
									<i class="fas fa-times"></i>
								</button>
								<button type="button" id="cancelar_venta" class="btn btn-success" data-dismiss="modal">
									<i class="fas fa-check"></i>
								</button>
							</div> 
						</div> 
					</div>
				</div>
				<!-- Final Modal Cancelar -->
				
				<hr>
				
				
				<section>
					<legend >
						Abonos:
					</legend>
					
					<!-- Button Modal Agregar -->
					<button type="button" class="mb-3 btn btn-primary" data-toggle="modal" data-target="#modal_abonos" >
						<i class="fas fa-plus mr-1"></i>
						Agregar
					</button>
					
					<!-- Modal Agregar-->
					<div class="modal" id="modal_abonos">
						<div class="modal-dialog">
							<div class="modal-content">
								
								<!-- Modal Header -->
								<div class="modal-header">
									<h4 class="modal-title">Agregar</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								
								<!-- Modal Body -->
								<div class=" modal-body">
									<form id="form_abonos" method="post" action="control/guardar_abono.php" autocomplete="off">
										
										<div class="form-group">
											<label >Zona:</label>
											<input class="form-control mb-2" required readonly name="zona" id="" value="<?php echo $fila_venta["zona"] ?>">
										</div>
										<div class="form-group">
											<label >Cuenta:</label>
											<input readonly class="form-control mb-2" name="id_ventas" id="" value="<?php echo $fila_venta["id_ventas"] ?>">
										</div>
										<div class="form-group">
											<label >Usuario:</label>
											<input type="text" class="form-control mb-2" name="usuario" id="usuario" value="<?php echo $_SESSION["id_usuarios"]?>">
										</div>
										<div class="form-group">
											<label >Fecha:</label>
											<input type="date" class="form-control mb-2" name="fecha" id="fecha" value="<?php echo date("Y-m-d")?>">
										</div>
										<div class="form-group">
											<label >Hora:</label>
											<input type="time" class="form-control mb-2" name="hora" id="hora" value="<?php echo date("H:i")?>">
										</div>
										<div class="form-group">
											<label >Folio Recibo:</label>
											<input type="number" required class="form-control mb-2" name="folio" id="folio" value="">
										</div>
										<div class="form-group">
											<label >Abono:</label>
											<input type="number" class="form-control mb-2" name="abono" id="abono" value="<?php echo $fila_venta["abono"] ?>">
										</div>
										
									</form>
								</div>
								
								<!-- Modal Footer -->
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
									<button form="form_abonos" id="btn_agregar" type="submit" class="btn btn-success">
										<i class="mr-1 fas fa-save"></i>
										Guardar
									</button>
								</div>
								
							</div>
						</div>
					</div>
					
					
					<div class="table-responsive" id="tabla_abonos">
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>Num</th>
									<th>Fecha</th>
									<th>Hora</th>
									<th>Folio</th>
									<th>Abono</th>
									<th>-</th>
								</tr>
							</thead>
							<tbody> 
								
								<?php 
									if(count($fila_abono) > 0){
										foreach($fila_abono as $i=>$fila){
											$totales[0]+= $fila["monto_abonos"];
										?>
										<tr>
											<td><?php echo $i +1;?></td>
											<td><?php echo date("d/m/Y", strtotime($fila["fecha_abonos"]));?></td>
											<td><?php echo date("H:i", strtotime($fila["fecha_abonos"]));?></td>
											<td><?php echo $fila["id_abonos"]?></td>
											<td class="text-right"><?php echo $fila["monto_abonos"]?></td>
											
											<td class="text-center">
												<button 
												class="btn btn-danger borrar"
												data-fecha="<?php echo $fila["fecha_abonos"]?>"
												data-id_ventas="<?php echo $fila["id_ventas"]?>" 
												data-zona="<?php echo $fila["zona"]?>" 
												data-tabla="abonos" 
												>
													
													<i class="fas fa-trash"></i>
													
												</button>
												
												
												<!-- Botón Imprimir Abono -->
												<a target="_blank" class="btn btn-info d-print-none" 
												href="impresion/imprimir_recibo.php?
												zona=<?php echo $fila_venta["zona"];?>
												&fecha_abonos=<?php echo $fila["fecha_abonos"]?>
												&id_ventas=<?php echo $fila_venta["id_ventas"];?>
												&id_abonos=<?php echo $fila["id_abonos"]?>
												">
													<i class="fas fa-print"></i>
												</a>
											</td>
											
										</tr>
										<?php
										}
									}
								?>
							</tbody>
							<tfoot>	
								<tr class="h6 bg-secondary text-light">
									<td colspan="4"> <?php echo count($fila_abono) ?> Registros</td>
									
									<td class="text-right">$<?php echo  number_format($totales[0]);?></td>
									
									<td class="bg-secondary"></td>
									
								</tr>
							</tfoot>
						</table>
					</div>
				</section>
				
				<section>
					<legend >
						Incidencias:
					</legend>
					
					
					<div class="table-responsive" id="tabla_abonos">
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>Num</th>
									<th>Fecha</th>
									<th>Hora</th>
									<th>Motivo</th>
									<th>Comentario</th>
									<th>-</th>
									
								</tr>
							</thead>
							<tbody> 
								
								<?php 
									if(count($fila_incidencias) > 0){
										foreach($fila_incidencias as $i=>$fila){
											$totales[0]+= $fila["monto_abonos"];
										?>
										<tr>
											<td><?php echo $i +1;?></td>
											<td><?php echo date("d/m/Y", strtotime($fila["fecha_incidencias"]));?></td>
											<td><?php echo date("H:i", strtotime($fila["fecha_incidencias"]));?></td>
											<td class="text-right"><?php echo $fila["motivo"]?></td>
											<td class="text-right"><?php echo $fila["comentario"]?></td>
											
											<td class="text-center">
												<button 
												class="btn btn-danger borrar"
												data-fecha="<?php echo $fila["fecha_incidencias"]?>" 
												data-id_ventas="<?php echo $fila["id_incidencia"]?>"
												data-zona="<?php echo $fila["zona"]?>"  
												data-tabla="incidencias"  
												>
													
													<i class="fas fa-trash"></i>
													
												</button>
											</td>
											
										</tr>
										<?php
										}
									}
								?>
							</tbody>
							<tfoot>	
								<tr class="h6 bg-secondary text-light">
									<td colspan="7"> <?php echo count($fila_incidencias) ?> Registros</td>
									
									
								</tr>
							</tfoot>
						</table>
					</div>
				</section>
				
				
				
				<section>
					<legend >
						Plan de Pagos:
					</legend>
					<div class="table-responsive" id="tabla_pagos">
						<table class="table table-bordered table-condensed">
							<thead>
								<tr>
									<th>Número</th>
									<th>Fecha Planeada</th>
									<th>Fecha Limite de Pago</th>
									<th hidden>Acumulado Planeado</th>
									<th>Pagado</th>
									
								</tr>
							</thead>
							<tbody> 
								<?php 
									$dias=["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]; 
									$dias_ingles = ["LUNES" => "monday", "MARTES" => "tuesday", "MIÉRCOLES" => "wednesday", "JUEVES" => "thursday", "VIERNES" => "friday", "SÁBADO" => "saturday", "DOMINGO" => "sunday"];
									$meses_cortos = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
									$atrasados = 0; 
									$acumulado_planeado = 0; 
									
									switch($fila_venta["periodo"]){
										
										case "SEMANAL":
										$tamaño_periodo = 1;
										$periodo = "week";
										break;
										case "QUINCENAL":
										$tamaño_periodo = 15;  
										$periodo = "days";
										break;
										case "MENSUAL":	
										$tamaño_periodo = 1;
										$periodo = "months";
										break;
										
									}
									
									switch($fila_venta["dia_cobranza"]){
										
										case "QUINCENAL":
										
										if(date("j", strtotime($fila_venta["fecha_ventas"])) > 16){
											$fecha_inicial = strtotime("first day of next month"); 
										}
										else{
											$fecha_inicial = strtotime(date("Y-m-16",strtotime($fila_venta["fecha_ventas"]) ));
										}
										break;
										case "MENSUAL":
										$fecha_inicial = strtotime("first day of next month"); 
										
										break;
										default:
										$siguiente_dia = $dias_ingles[$fila_venta["dia_cobranza"]];
										// $fecha_inicial = strtotime("next $siguiente_dia ", strtotime($fila_venta["fecha_ventas"]));
										$fecha_inicial = strtotime($fila_venta["fecha_enganche"]);
										
									}
									$fecha_inicial = strtotime($fila_venta["fecha_enganche"]);
									
									
									$cantidad_pagos = $fila_venta["importe"] / $fila_venta["abono"];
									$incremento= $tamaño_periodo;
									$fecha_inicial = strtotime(" - $incremento $periodo" , $fecha_inicial);
									
									
									for ($i = 1; $i <= $cantidad_pagos; $i++) {
								 		
										$segundos = strtotime(" + $incremento $periodo" , $fecha_inicial);
										$acumulado_planeado+= $fila_venta["abono"];
										$incremento+= $tamaño_periodo;
										
										$segundos_limite = strtotime(" + 7 days" , $segundos);
										
										
										if($acumulado_planeado <= $fila_venta["suma_abonos"]){
											if($segundos_limite < strtotime("now")){
												$estatus_abono = '<i class="text-success fas fa-check"></i>';
											}
											else {
												$estatus_abono = '-';
											}
										}
										else{
											if($segundos_limite < strtotime("now")){
												
												$estatus_abono = '<i class="text-danger fas fa-times"></i>';
												$atrasados++;
												
											}
											else {
												$estatus_abono = '-';
											}
										}
										
										
									?> 
									
									<tr class="<?php //echo $periodo_actual;?>"> 
										<td><?php echo $i;?></td>
										<td><?php echo  $dias[date("w", $segundos)]. " ". date("d/m/Y", $segundos);?></td>
										<td><?php echo  $dias[date("w", $segundos_limite)]. " ". date('d/m/Y', $segundos_limite);?></td>
										
										<td><?php echo  $estatus_abono;?> </td>
										<td hidden >
											acumulado_planeado : <?php echo  $acumulado_planeado;?> <br> 
											$fila_venta["suma_abonos"]) : <?php echo  $fila_venta["suma_abonos"];?> <br> 
											segundos_limite : <?php echo  $segundos_limite;?> <br> 
											fecha_abonos : <?php echo strtotime($fila["fecha_abonos"]);?> <br> 
											fecha_inicial : <?php echo $fecha_inicial;?> <br> 
											segundos fecha_ventas : <?php echo strtotime($fila_venta["fecha_ventas"]);?> <br> 
											fecha_ventas : <?php echo date("Y-m-d",strtotime($fila_venta["fecha_ventas"]));?> <br> 
											
											
											estatus_abono : <?php echo  $estatus_abono;?> <br> 
										</td>
										
									</tr>
									<?php
										
										
									}
									
									
									
								?>
								
							</tbody>
							<tfoot>	
								<tr class="h6 bg-secondary text-light">
									<td colspan="3" ></td>
									<td >
										<?php echo (pagos_atrasados($fila_venta)) ?> PAGOS ATRASADOS </br>
										DEUDA: <?php echo pagos_atrasados($fila_venta) * $fila_venta["abono"]?>
										
										
									</td>
									
									
								</tr>
							</tfoot>
						</table>
					</div>
				</section>
				
				
				
				
			</div>
			
			
			<!-- Sticky Footer -->
			<?php include("../../footer.php")?>
			
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- /.content-wrapper -->
</div>
<!-- /#wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
	<i class="fas fa-angle-up"></i>
</a>		

<div class="d-print-block  p-2 " id="ticket" >
</div>

<?php
	//include("impresion/imprimir_venta_david.php");
	include("../../scripts.php")
?>
<script>
	
	
	$(".borrar").click(confirmaCancelacion);
	
	
	function confirmaCancelacion(event){
		console.log("confirmaCancelacion()");
		let boton = $(this);
		let icono = boton.find(".fas");
		var id_registro = $(this).data("id_registro");
		var fila = boton.closest('tr');
		
		alertify.confirm('Confirmación', '¿Deseas eliminarlo?', cancelarRegistro , function(){});
		
		
		function cancelarRegistro(){
			
			boton.prop("disabled", true);
			icono.toggleClass("fa-trash fa-spinner fa-spin");
			
			return $.ajax({
				url: "control/borrar_registro.php",
				dataType:"JSON",
				data:{
					"id_ventas" : boton.data("id_ventas"),
					"zona" : boton.data("zona"),
					"fecha" : boton.data("fecha"),
					"tabla" : boton.data("tabla")
				}
				}).done(function (respuesta){
				if(respuesta.result == "success"){
					alertify.success("Eliminado");
					fila.fadeOut(500);
				}
				else{
					alertify.error(respuesta.result);
					
				}
				
				}).always(function(){
				boton.prop("disabled", false);
				icono.toggleClass("fa-trash fa-spinner fa-spin");
				
			});
		}
	}		
	
	console.log("zona", $("#zona").val())
	$('#form_abonos').on('submit', function guardarAbono(event){
		
		var form = $("#form_abonos");
		let boton = $("#btn_agregar");
		let icono = boton.find('.fas');
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-spin ');
		$.ajax({
			url: form.prop("action"),
			method: 'POST',
			dataType: 'JSON',
			data: form.serialize()
			}).done(function(respuesta){
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-spin ');
			console.log(respuesta.abono)
			console.log(respuesta["abono"])
			
			if(respuesta.estatus ==  "correcto"){
				
				alertify.success(respuesta.mensaje)
				
				$("#modal_abonos").modal('hide')
				window.location.reload()
			}
			else{
				alertify.error(respuesta.mensaje)
				
			}
			
		});
		boton.prop('disabled',true);
		
		return false;
		
	})
	
	
	;
	$('#form_edicion').on('submit', function guardarRegistro(event){
		event.preventDefault();
		
		let form = $("#form_edicion");
		let boton = form.find(":submit");
		let icono = boton.find('.fa');
		
		boton.prop('disabled',true);
		icono.toggleClass('fa-save fa-spinner fa-spin ');
		
		$.ajax({
			url: 'control/update_ventas.php',
			method: 'POST',
			dataType: 'JSON',
			data: $("#form_edicion").serializeArray()
				
			}).done(function(respuesta){
			if(respuesta.estatus == "success"){
				alertify.success(respuesta.mensaje);
				location.href= "estado_cuenta.php?zona="+$("#zona").val()+"&id_ventas="+ $("#id_ventas").val()
			}
			else{
				
				alertify.error(respuesta.mensaje);
			}
			
			
			}).always(function(){
			
			boton.prop('disabled',false);
			icono.toggleClass('fa-save fa-spinner fa-spin ');
			
		});
		
	});
	
	
	$("#cancelar_venta").click(function cancelar_venta(){
		
		var devolver = $("#devolver").prop("checked");
		
		peticion = $.ajax({
			
			'url': 'control/cancelar_venta.php',
			'data': {
				"id_ventas": $('#id_ventas').val(), //999999
				"devolver": devolver,
				"nombre_productos": $('#nombre_productos').val()
				
			}
			
			
			
		});
		
		peticion.done(function peticion_exitosa(respuesta){
			
			alertify.success("Venta Cancelada");
			
			window.location.reload(); 
		});
		
	});
</script>
</body>
</html>
