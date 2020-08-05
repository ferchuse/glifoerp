<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	$link = Conectarse();
	
	$menu_activo = "clientes";
	
?>
<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Clientes</title>
		
		<?php include("../styles.php"); ?>
		<style>
			.asc::after {
			content: "<i class='fas fa-arrow-down'></i>";
			
			}
		</style>
	</head>
	
	<body>
		<?php include("../menu.php"); ?>
		
		<div class="container-fluid d-print-none">
			<div class="row">
				<div class="col-12 border-bottom mb-3">
					<h3 class="text-center">Clientes <span class="badge badge-success" id="contar_registros">0</span></h3>
				</div>
				
				<div class="row col-12 mb-4">
					<div class="col-12 col-sm-3" >
						<input form="form_fitros" class="buscar  form-control float-left" name="alias_clientes" type="search" id="buscar_clientes" placeholder="Buscar Cliente">
						
					</div>
					<div class="col-sm-7">
						<form class="form-inline" id="form_filtros">
							<input type="hidden" id="sort" name="sort" value="alias_clientes">
							<input type="hidden" id="order" name="order" value="ASC">
							
							
							<label class="mr-sm-2">Vendendor</label>
							<?php echo generar_select($link, "vendedores", "id_vendedores", "nombre_vendedores", true); ?>
							
							
							<button type="submit" class="btn btn-primary" >
								<i class="fa fa-search"></i>
							</button>
							
						</form>
					</div>
					<div class="ml-auto">
						<button type="button" class="btn btn-success float-right" id="btn_nuevo">
							<i class="fa fa-plus"></i> Nuevo
						</button>
					</div>
				</div>
				
				
				
				<div class="text-center table-responsive" id="lista_registros" >
					
				</div>
				
			</div>
		</div>
		
		<div class="d-none d-print-block" id="imprimir_estado_cuenta">
		</div>
		
		<div id="modal_historial" class="modal fade d-print-none" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title text-center">Estado de Cuenta <span id="nombre_historial"></span></h3>
						<button type="button" class="close d-print-none" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						
					</div>
					<div class="modal-footer d-print-none">
						<button type="button" class="btn btn-danger" data-dismiss="modal">
							<i class="fa fa-times"></i> Cerrar
						</button>
						<button type="button" id="btn_imprimir_edo_cuenta" class="btn btn-info" >
							<i class="fa fa-print"></i> Imprimir
						</button>
					</div>
				</div>
			</div>
		</div>	
		
		
		<?php include('../scripts.php'); ?>
		<?php include('form_cargos.php'); ?>
		<?php include('form_clientes.php'); ?>
		<script src="clientes.js?v=<?= date("dmYHis")?>"></script>
		<script src="cargos.js"></script>
		
					</body>
					
				</html>														