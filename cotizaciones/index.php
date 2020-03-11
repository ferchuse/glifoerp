<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	$link = Conectarse();
	$menu_activo = "cotizaciones";
	
	$dt_fecha_inicial = new DateTime("first day of this month");
	$dt_fecha_final = new DateTime("last day of this month");
	
	$fecha_inicial = $dt_fecha_inicial->format("Y-m-d");
	$fecha_final = $dt_fecha_final->format("Y-m-d");
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<title>Cotizaciones</title>
		
		<?php include("../styles.php");?>
		
	</head>
	<body>
		<?php include("../menu.php");?>
		
		<pre hidden>
			<?php echo var_dump($movimientos)?>
		</pre>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h3 class="text-center">
						Cotizaciones
						
					</h3>
					<a href="nueva_venta.php?tipo_movimiento=VENTA" class="btn btn-success" >
						<i class="fa fa-plus"></i> Nueva
					</a>
					<hr>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<form id="form_filtros" class="form-inline">
						<div class="form-group mr-2">
							<label for="fecha_inicial">Desde:</label>
							<input type="date" name="fecha_inicial" id="fecha_inicial" class="form-control" value="<?php echo $fecha_inicial;?>">
						</div>
						<div class="form-group mr-2">
							<label for="fecha_final">Hasta:</label>
							<input type="date" name="fecha_final" id="fecha_final" class="form-control" value="<?php echo $fecha_final;?>">
						</div>
						<div class="form-group mr-2">
							<label for="id_clientes">Cliente:</label>
								<?php echo generar_select($link, "clientes", "id_clientes", "alias_clientes", true);?>
						</div>
						<button  class="btn btn-primary">
							<i class="fa fa-search"></i> Buscar
						</button>
						
					</form>
				</div>
			</div>
			<hr>
			
			
			
			<div class="table-responsive" id="lista_registros">
				
				
			</div>
			
			
			
		</div >
		
		
		<?php  include('../scripts.php'); ?>
		<script src="index.js?v=<?= date("d-m-Y-H-i-s")?>"></script>
	</body>
</html>	