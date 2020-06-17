<?php
	include("../login/login_success.php");
	
	include_once("../conexi.php");
	$link = Conectarse();
	$menu_activo = "configuracion";
	
	$q_emisor = "SELECT * FROM emisores WHERE id_emisores = 1";
	
	$result_emisor = mysqli_query($link,$q_emisor );
	
	if($result_emisor){
		while($fila_emisor = mysqli_fetch_assoc($result_emisor)){
			extract($fila_emisor);
			
		}		
	}
	else{
		
	}
	
	
?>
<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Configuración</title>
		
		<?php include("../styles.php");?>
		
	</head>
	<body>
		
		<div class="container-fluid">
			<?php include("../menu.php");
				
			
			?>
		</div>
		
		<h3 class="text-center">Configuración de Facturación</h3>
		
		
		<div class="container"  > 
			<div class="row">
				<div class="col-sm-12"  >
					<form id="form_emisores" class="form" >
						
						<input  type="hidden" name="id_emisores" id="id_emisores" value="<?php echo $id_emisores;?>">
						
						<div class="form-group">
							<label for="id_niveles">RAZON SOCIAL:</label>
							<input  type="text" required name="razon_social_emisores" id="correo" class="form-control" value="<?php echo $razon_social_emisores;?>">
						</div>
						<div class="form-group">
							<label for="id_niveles">RFC:</label>
							<input  type="text" required name="rfc_emisores" id="rfc_emisores" class="form-control" value="<?php echo $rfc_emisores;?>">
						</div>
						<div class="form-group">
							<label for="id_niveles">Contraseña:</label>
							<input  type="password"  placeholder="Ingresa tu contraseña" name="password" id="password" class="form-control" value="<?php echo $password;?>">
							<input  type="password"  placeholder="Repite la contraseña" id="pass_emisores2" class="form-control" >
						</div>
						<div class="form-group">
							<label for="id_niveles">Régimen:</label>
							<input id="" required name="regimen_emisores" class="form-control" value="<?php echo $regimen_emisores;?>"> 
							
						</div>
						
						<div class="form-group">
							<label for="lugar_expedicion_emisores">Lugar de Expedición:</label>
							<input  type="text" required name="lugar_expedicion_emisores" id="lugar_expedicion_emisores" class="form-control" value="<?php echo $lugar_expedicion_emisores;?>">
						</div>
						<div class="form-group">
							<label for="serie_emisores">Serie:</label>
							<input  type="text" required name="serie_emisores" id="serie_emisores" class="form-control" value="<?php echo $serie_emisores;?>">
						</div>
						<div class="form-group">
							<label for="serie_emisores">Folio:</label>
							<input  type="text" required name="folio_emisores" id="folio_emisores" class="form-control" value="<?php echo $folio_emisores;?>">
						</div>
						<div class="form-group">
							<label for="serie_emisores">Serie Pagos:</label>
							<input  type="text"  name="serie_pago" id="serie_pago" class="form-control" value="<?php echo $serie_pago;?>">
						</div>
						<div class="form-group">
							<label for="serie_emisores">Folio Pagos:</label>
							<input  type="text"  name="folio_pago" id="folio_pago" class="form-control" value="<?php echo $folio_pago;?>">
						</div>
						<div class="form-group">
							<label for="id_niveles">Certificado:</label>
							<input  type="text"  name="url_certificado_emisores" id="url_certificado_emisores" class="form-control" >
						</div>
						<div class="form-group">
							<label for="id_niveles">LLave Privada:</label>
							<input  type="text"  name="url_llave_privada_emisores" id="url_llave_privada_emisores" class="form-control" >
						</div>
						<div class="form-group">
							<label for="id_niveles">Logotipo:</label>
							<input  type="text"  name="url_logo" id="url_llave_privada_emisores" class="form-control" >
						</div>
						<button class="btn btn-success pull-right">
							<i class="fa fa-save"></i>
							Guardar
						</button>
					</form>
				</div>
			</div>
		</div>
		
		
		
		
		<?php  include('../scripts.php'); ?>
		<script src="emisores.js"></script>
		
		
		
	</body>
</html>
