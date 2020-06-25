<?php
	include("../login/login_success.php");
	include("../funciones/generar_select.php");
	include("../conexi.php");
	
	$link = Conectarse();
	$menu_activo = $_GET["tipo_movimiento"];
	
	switch($_GET["tipo_movimiento"]){
		
		case 'ENTRADA':
		$bg = "bg-success";
		$display = "none";
		break;
		case 'SALIDA':
		$bg = "bg-danger";
		$display = "none";
		break;
		case 'VENTA':
		$bg = "bg-info";
		$display = "";
		break;
		
	}
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<style>
			.tabla_totales .row{
			margin-bottom: 10px;
			}
			
			.tab-pane {
			display: block;
			overflow: auto;
			overflow-x: hidden;
			height: 350px;
			width: 100%;
			padding: 10px;				
			}			
		</style> 
		
		
		<style>
			.venta{
			display: <?php echo $display;?>;
			}
		</style> 
		
		
		
		<title>Nueva Venta</title>
		<?php include("../styles.php");?>
	</head>
	<body>
		<?php include("../menu.php");?>
		
		<form id="form_agregar_producto" class="" autocomplete="off">
		</form>
		<div class="container-fluid d-print-none">
			<div class="row">
				<div class="col-sm-1">
					<div class="form-group">
						<label for="">Folio:</label>  	
						<input type="number" class="form-control" id="id_ventas" name="id_ventas" value="<?php echo $_GET["folio_ventas"]?>">
					</div>
				</div>
				<div class="col-2 d-none " >
					<label for="">Código:</label>
					
					<input id="codigo_producto" form="form_agregar_producto"   type="search" class="form-control" placeholder="Presiona Enter para agregar" >
					
				</div>
				<div class="col-sm-3">
					
					<label for="">Descripción del Producto: </label>
					<button class="btn btn-success btn-sm float-right" id="btn_nuevo_producto"> 
						+ Nuevo
					</button>
					
					<input id="buscar_producto"  form="form_agregar_producto" autofocus type="search" class="form-control"  placeholder="Escriba para buscar">
					
				</div>
				
				<div class="col-sm-2 ">
					<div class="form-group">
						<label for="">Fecha:</label>
						<input id="fecha_movimiento"   type="date" class="form-control" value="<?php echo date("Y-m-d")?>" >
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group " >
						<label for="">Vendedor</label>
						<?php echo generar_select($link, "vendedores", "id_vendedores", "nombre_vendedores", false,false, false, 2);?>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="form-group">
						<label for="">Cliente:</label>
						<button class="btn btn-success btn-sm float-right" id="btn_nuevo_cliente"> 
							+ Nuevo
						</button>
						<?php echo generar_select($link, "clientes", "id_clientes", "alias_clientes");?>
					</div>
				</div>
				
				<div class="col-sm-2 d-none">
					<div class="form-group">
						<label for="">Movimiento:</label>
						<input id="tipo_movimiento" type="text" class="form-control" value="<?php echo $_GET["tipo_movimiento"]?>" readonly>
					</div>
				</div>
				
			</div>
			
			
			<div class="row">
				<div class="col-md-12 table-responsive tab-pane">
					
					<table id="tabla_venta" class="table table-bordered table-condensed">
						<thead class="<?php echo $bg;?> text-white">
							<tr>
								<th class="text-center">Cantidad</th>
								<th class="text-center">Descripción</th>
								<th class="text-center venta px-4">Precio  </th>
								<th class="text-center venta px-3">Importe</th>
								<th class="text-center">% Desc</th>
								<th class="text-center">Descuento</th>
								<th class="text-center">Existencia</th>
								<th class="text-center">Acciones</th>
							</tr>
						</thead>
						<tbody >
							
						</tbody>
						<tfoot >
							<tr>
								<td colspan="6">
									<button class="btn btn-success btn-sm" id="btn_nueva_partida"> 
										+ Agregar
									</button>
								</th>
								
								
							</tr>
						</tfoot>
					</table>
					
				</div>
			</div>
			
			<br>
			<section id="footer">
				<form id="form_movimientos" autocomplete="off">
					<div class="row">
						<div class="col-sm-1 col-6 h3 text-right">
							<label for="">Artículos: </label> 
						</div>
						<div class="col-sm-1 col-6 h3">
							<input readonly  id="articulos" type="text" class="form-control input-lg text-right " value="0" name="articulos">
						</div>
						
						
						<div class="col-sm-3">
							<textarea placeholder="Condiciones de Pago" name="condiciones_pago" rows="4" id="condiciones_pago" class="notas form-control mt-2">Se requiere anticipo del 50%.
							Tiempo de Entrega 4 días habiles a partir del anticipo.							
							Precio incluye IVA
							</textarea>
						</div>
						<div class="col-sm-3 col-6 h3 text-right fila_totales">
							
							<label class="venta" for="">Subtotal:</label>  <br>
							<label class="venta" for="">Descuento:</label> <br>
							<label class="venta" for="">Total:</label> <br>
							<label class="venta" for="">Anticipo:</label> <br>
							<label class="venta" for="">Saldo:</label> 
						</div>
						<div class="col-sm-2 col-6 h3 venta fila_totales">
							<input   id="subtotal" type="number" class="form-control input-lg text-right venta" value="0" >
							<input   id="descuento" type="number" class="form-control input-lg text-right venta" value="0">
							<input   id="total" type="number" class="form-control input-lg text-right venta" value="0">
							<input   id="anticipo" type="number" class="form-control input-lg text-right venta" value="0">
							<input   id="saldo" type="number" class="form-control input-lg text-right venta" value="0" >
						</div>
						
						
						<div class="col-sm-2 col-12 text-right">
							<button class="btn btn-success btn-lg btn-block" type="submit" id="cerrar_venta">F12 - Guardar
							</button>
							<label>
								<input checked type="checkbox" name="sumar_importes"
								id="sumar_importes" value="1" > Sumar Importes 
							</label>	
							
						</div>
					</div>
				</form>
			</section>
			
		</div>
		
		<div id="ticket" class="d-none d-print-block">
			
		</div>
		<?php include('../scripts.php'); ?>
		<?php include('modal_cantidad.php'); ?>
		<?php include('../productos/form_productos.php'); ?>
		<?php include('../clientes/form_clientes.php'); ?>
		
		
		<script src="../productos/editar_producto.js?v=<?= date("d-m-Y-h-i-s")?>"></script>
		<script src="../clientes/clientes.js?v=<?= date("d-m-Y-h-i-s")?>"></script>
		<script src="nueva_venta.js?v=<?= date("d-m-Y-h-i-s")?>"></script>
		
	</body>
</html>				