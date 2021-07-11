<?php
	// include("login/login_success.php");
	include_once("control/is_selected.php");
	include_once("../conexi.php");
	$link = Conectarse();
	$menu_activo = "facturas";
	
	$year = date("Y");
	$mes = date("n");
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Facturas</title>
		
		<?php include("styles.php");?>
		
	</head>
	<body>
		
		
		<?php include("menu.php");?>
		
		<h3 class="text-center">Facturas</h3>
		
		<div class="container-fluid"  > 
			
			<form class="form-inline hidden-print" id="form_filtros">
				<div class="form-group">
					<label for="id_ciclos" class="text-center">Año:</label>
					<select class="form-control filtro" id="year_facturas" name="year_facturas" >
						<option <?php echo is_selected("2017", $year);?> value="2017">2017</option>
						<option <?php echo is_selected("2018", $year);?> value="2018">2018</option>
						<option <?php echo is_selected("2019", $year);?> value="2019">2019</option>
						<option <?php echo is_selected("2020", $year);?> value="2020">2020</option>
						<option <?php echo is_selected("2021", $year);?> value="2021">2021</option>
					</select>
				</div>
				<div class="form-group">
					<label for="mes_facturas" class="text-center">Mes:</label>
					<select class="form-control filtro" id="mes_facturas" name="mes_facturas" >
						<option value="">Todos</option>
						<option <?php echo is_selected("1", $mes);?> value="1">ENERO</option>
						<option <?php echo is_selected("2", $mes);?> value="2">FEBRERO</option>
						<option <?php echo is_selected("3", $mes);?> value="3">MARZO</option>
						<option <?php echo is_selected("4", $mes);?> value="4">ABRIL</option>
						<option <?php echo is_selected("5", $mes);?> value="5">MAYO</option>
						<option <?php echo is_selected("6", $mes);?> value="6">JUNIO</option>
						<option <?php echo is_selected("7", $mes);?> value="7">JULIO</option>
						<option <?php echo is_selected("8", $mes);?> value="8">AGOSTO</option>
						<option <?php echo is_selected("9", $mes);?> value="9">SEPTIEMBRE</option>
						<option <?php echo is_selected("10", $mes);?> value="10">OCTUBRE</option>
						<option <?php echo is_selected("11", $mes);?> value="11">NOVIEMBRE</option>
						<option <?php echo is_selected("12", $mes);?> value="12">DICIEMBRE</option>
					</select>
				</div>
				
				<div class="checkbox">
					<label ><input type="checkbox"  class="filtro" value="1" name="mostrar_pruebas" id="mostrar_pruebas"> Mostrar Pruebas</label>
				</div>
				
				<div class="form-group">
					<input class="form-control"  id="buscar_cliente" placeholder="Buscar Cliente">	
				</div>
			</form>
			<div class="pull-right">
				<a class="btn btn-success " href="facturas_nueva.php" >
					<i class="fa fa-plus" ></i> Nueva Factura
				</a>	
				<button class="btn btn-primary exportar">
					<i class="fa fa-arrow-right" ></i> Exportar
				</button>	
				<button class="btn btn-info" onclick="window.print()">
					<i class="fa fa-print" ></i> Imprimir
				</button>	
			</div>
			
		</div>
		<hr>
		<div class="container-fluid"  > 
			<div class="row">
				<div class="col-sm-12" >
					<div class="panel panel-primary" >
						<div class="panel-body "  >
							<div class="table-responsive">
								<table class="table table-bordered " id="tabla_reporte">
									<thead> 
										<tr>
											<th>Folio</th>
											<th>Fecha</th>
											<th>Razon Social</th>
											<th>Subtotal</th>
											<th>IVA</th>
											<th>Total</th>
											<th>Saldo</th>
											<th class="hidden-print">Estatus SAT</th>
											<th class="hidden-print">Timbrado </th>
											<th class="hidden-print">Acciones</th>
										</tr>
									</thead>
									<tbody id="lista_facturas"> 
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		
		<form id="form_correo" class="form" >
			<div id="modal_correo" class="modal fade" role="dialog">
				<div class="modal-dialog modal-sm"> 
					<!-- Modal content--> 
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title text-center"></h4>
						</div>
						
						<div class="modal-body">
							
							<div class="form-group">
								<label for="id_niveles">Correo:</label>
								<input  type="text" required name="correo" id="correo" class="form-control minus" >
								<input type="hidden" name="url_xml" id="url_xml" class="form-control" >
								<input type="hidden" name="url_pdf" id="url_pdf" class="form-control" >
							</div>
						</div>
						
						<div class="modal-footer">
							
							<button type="button" class="btn btn-danger" data-dismiss="modal">
								<i class="fa fa-times"></i> Cancelar
							</button>
							<button type="submit" class="btn btn-success">
								<i class="fa fa-envelope" ></i> Enviar
							</button>
							
						</div>
						
					</div>
				</div>
			</div>
		</form>
		
		
		<?php  include('forms/form_pago.php'); ?>
		<?php  include('scripts.php'); ?>
		<script src="js/facturas.js"></script>
		
		
		
	</body>
</html>
