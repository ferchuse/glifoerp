<?php 
	$permisos = [
	"administrador" => [
	0 => [
	"titulo"=> "Entradas",
	"url"=> "../inventarios/salidas.php?tipo_movimiento=ENTRADA"
	
	]
	]
	];
	
?>
<?php 
	
	include("../facturacion/funciones/timbres_restantes.php");
	
	$timbres_restantes = timbres_restantes($link, '1')["fila"]["folios_restantes_emisores"];
	
	
?>



<nav class="navbar navbar-expand-sm bg-dark navbar-dark mb-2 na ">
	
	<a class="navbar-brand" href="#">
		<img src="../img/logo_small.jpg" class="" width="40px">
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav mr-auto">
				<li class="nav-item">
					<a class="nav-link" href="../clientes">
						<i class="fas fa-users"></i> Clientes
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../ventas/index.php">
						<i class="fas fa-dollar-sign"></i> Ventas
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../compras/index.php">
						<i class="fas fa-dollar-sign"></i> Compras
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../cotizaciones/index.php">
						<i class="fas fa-dollar-sign"></i> Cotizaciones
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="../contratos/index.php">
						<i class="fas fa-signature"></i> Contratos
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-chart-bar"></i> Almacen
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						
						<a class="dropdown-item" href="../inventarios/movimientos.php?tipo_movimiento=ENTRADA">
							<i class="fas fa-arrow-left"></i> Entradas
						</a>
						<a class="dropdown-item" href="../inventarios/movimientos.php?tipo_movimiento=SALIDA">
							<i class="fas fa-arrow-right"></i> Salidas
						</a>
						<a class="dropdown-item" href="../inventarios/inventarios.php">
							<i class="fas fa-boxes"></i> Inventarios
						</a> 
						<a class="dropdown-item" href="../productos/productos.php">
							<i class="fas fa-box-open"></i> Productos
						</a>
						<a class="dropdown-item" href="../catalogos/departamentos.php">
							<i class="fas fa-list"></i> Departamentos
						</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-chart-bar"></i> Catálogos
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						
						<a class="dropdown-item" href="../inventarios/movimientos.php?tipo_movimiento=ENTRADA">
							<i class="fas fa-arrow-left"></i> Departamentos
						</a>
						<a class="dropdown-item" href="../inventarios/movimientos.php?tipo_movimiento=SALIDA">
							<i class="fas fa-arrow-right"></i> Proveedores
						</a>
						<a class="dropdown-item" href="../inventarios/inventarios.php">
							<i class="fas fa-boxes"></i> Egresos
						</a> 
					</div>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="../facturacion/facturas.php">
						<i class="fas fa-qrcode"></i> Facturación
						<span class="badge badge-success"><?php echo $timbres_restantes;?></span>
						
					</a>
				</li>
				
				<li class="nav-item ">
					<a class="nav-link" href="../usuarios/usuarios.php">
						<i class="fas fa-user"></i> Usuarios
					</a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-chart-bar"></i> Reportes
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<a class="dropdown-item" href="../reportes/comisiones.php">Comisiones</a>
						<a class="dropdown-item" href="../reportes/cuentas_por_cobrar.php">Cuentas Por Cobrar</a>
					</div>
				</li>
				
		</ul>
		<ul class="navbar-nav">
			<input type="hidden" id="cookie_id_usuarios" value="<?php echo $_COOKIE["id_usuarios"]?>">
			<input type="hidden" id="cookie_nombre_usuarios" value="<?php echo $_COOKIE["nombre_usuarios"]?>">
			<input type="hidden" id="cookie_permiso_usuarios" value="<?php echo $_COOKIE["permiso_usuarios"]?>">
			
		
			<li class="nav-item">
				<a class="nav-link" href="../emisores/index.php">
					<i class="fas fa-user"></i>	<?php echo $_COOKIE["nombre_usuarios"]?>	
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="../login/logout.php">
					<i class="fas fa-sign-out-alt"></i>	Salir
				</a>
			</li>
		</ul>
	</div> 
</nav>

