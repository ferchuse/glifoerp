<?php
	include("../login/login_success.php");
	include("../conexi.php");
	include("../funciones/generar_select.php");
	$link = Conectarse();
	$menu_activo = "producto";
	
	if ($_COOKIE["permiso_usuarios"] == "vendedor") {
		$permiso = "hidden";
		} else {
		$permiso = "";
	}
	
	$consulta_inventario = "SELECT 
	SUM(costo_proveedor * existencia_productos) AS costo_inventario,
	SUM(precio_menudeo * existencia_productos) AS precio_venta
	
	FROM productos";
	$result_inventario = mysqli_query($link, $consulta_inventario);
	while ($fila = mysqli_fetch_assoc($result_inventario)) {
		$fila_inventario[] = $fila;
	}
	
?>
<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
			#respuesta_rep {
			color: red;
			}
		</style>
		<title>Productos</title>
		
		<?php include("../styles.php"); ?>
		
	</head>
	
	<body>
		
		<?php include("../menu.php"); ?>
		
		<div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="col-md-12 input-group">
							<h5>
								Costo Inventario: $
								<?php echo number_format($fila_inventario[0]["costo_inventario"],0); ?>
								
								Precio de Venta:
								
								$ <?php echo number_format($fila_inventario[0]["precio_venta"],0); ?>
								
								Ganancia Esperada:
								
								$ <?php echo number_format($fila_inventario[0]["precio_venta"] - $fila_inventario[0]["costo_inventario"],0); ?>
								
							</h5>
							
						</div>
					<h2 class="text-center">
						
						Productos
						<small>
							<span id="cantidad_productos" class="badge badge-success"></span>
						</small>
						<button type="button" class="btn btn-success float-right" <?php echo $permiso; ?> id="btn_nuevo">
							<i class="fa fa-plus"></i> Nuevo
						</button>
						
					</h2>
					<hr>
					<section class="bg-light sticky-top">
						<form action="productos.php" id="form_filtros" class="form-inline ">
							<input type="hidden" id="sort" name="sort" value="descripcion_productos">
							<input type="hidden" id="order" name="order" value="ASC">
							
							<div class="form-group mx-1 d-none d-sm-inline-flex">
								<label for="id_departamentos">Departamento:</label>
								<?php echo generar_select($link, "departamentos", "id_departamentos", "nombre_departamentos", true) ?>
							</div>
							<div class="form-group mx-1 d-none d-sm-inline-flex">
								<label for="existencia_productos">Existencias:</label>
								<select class="form-control" name="existencia_productos">
									<option value="">TODAS</option>
									<option value="minimo">DEBAJO DEL MÍNIMO</option>
								</select>
							</div>
							<div class="form-group">
								<label class="d-none d-sm-block" for="buscar">Buscar:</label>
								<input class="form-control buscar" id="buscar" type="search">
							</div>
							<div class="form-check form-check-inline ml-2">
								<input class="estatus_productos form-check-input" type="checkbox" id="inactivos" name="estatus_productos" value="INACTIVO">
								<label class="form-check-label" for="inactivos">Mostrar Inactivos</label>
							</div>
							<button class="btn btn-success d-none" type="submit">
								<i class="fas fa-search"></i>
							</button>
						</form>
						
						<div class="row text-white font-weight-bold  p-2 m-1 border d-none d-sm-flex">
							<div class="col-sm-4">
								<a class="sort" href="#!" data-columna="descripcion_productos">
									Descripción
								</a>
							</div>
							<div class="col-sm-2">
								<a class="sort" href="#!" data-columna="existencia_productos">
									Existencia
								</a>
							</div>
							<div class="col-sm-2 " >
								<a class="sort" href="#!" data-columna="departamento">
									Departamento
								</a>
							</div>
							<div class="col-sm-2 " <?php echo $permiso; ?>>
								<a class="sort" href="#!" data-columna="ubicacion">
									Ubicación
								</a>
							</div>
							<div class="col-sm-2" <?php echo $permiso; ?>>
								Acciones
							</div>
						</div>
					</section>
					
					<div id="bodyProductos">
						<div class="row">
							<div class="col-12 text-center">
								<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php include('form_productos.php'); ?>
			<?php include('../scripts.php'); ?>
			<script src="productos.js?v=<?= date("d-m-Y-h-i-s")?>"></script>
			<script src="editar_producto.js?v=<?= date("d-m-Y-h-i-s")?>"></script>
			<script src="https://unpkg.com/sticky-table-headers"></script>
		</body>
		
	</html>						