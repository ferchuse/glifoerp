<?php
	
	include("../conexi.php");
	$link = Conectarse();
	
	
	$consulta = "SELECT * FROM abonos
	LEFT JOIN clientes USING (id_clientes)
	WHERE id_abonos={$_GET["id_registro"]}";
	
	$result = mysqli_query($link, $consulta);
	
	if(!$result){
		die(mysqli_error($link));
	}
	
	
	while ($fila = mysqli_fetch_assoc($result)) {
		$abono = $fila;
	}
	
	
	$nombre_empresa= "GLIFO MEDIA";
	
?>


<!DOCTYPE html>
<html lang="es">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		
		<title>Nota de Remisión</title>
		
		
		<?php include("../styles.php"); ?>
		<link rel="stylesheet" href="imprimir_movimiento.css">
	</head>
	
	<body>
		<div class="container h4">
			
			<section class="mt-3 ">
				<div class="row">
					
					
					<div class="col-8">
						
						<form id="form_cargos" autocomplete="off" class="is-validated">
							
							
							
							<div class="row">
								<div class="col-3">
									<img class="img-fluid" src="../img/logo.png" alt="">
								</div>
								<div class="col-7">
									<h3 class="text-center">
										Recibo de Pago 
									</h3>
								</div>
							</div>
							
							<div class="row">
								
								
								<div class="col-12">
									<div class="form-group">
										<label >Folio:</label>
										<input  type="number" class="form-control" name="fecha" value="<?= $abono["id_abonos"]?>"> 
									</div><div class="form-group">
									<label >Fecha:</label>
									<input required type="date" class="form-control" name="fecha" value="<?= $abono["fecha"]?>"> 
									</div>
									<div class="form-group">
										<label >Concepto:</label>
										<p><?= $abono["concepto"]?></p>
									</div>
									<div class="form-group">
										<label for="">Importe:</label>
										<input required class="form-control" type="number" name="importe" id="importe" value="<?= $abono["importe"]?>">
									</div>
									<div class="form-group">
										<label for="">Saldo Anterior:</label>
										<input  readonly  class="form-control" type="number" name="saldo_anterior" id="saldo_anterior" value="<?= $abono["saldo_anterior"]?>">
									</div>
									
									<div class="form-group">
										<label for="">Saldo Restante:</label>
										<input readonly class="form-control" type="number" name="saldo_restante" id="saldo_restante" value="<?= $abono["saldo_restante"]?>">
									</div>
									
								</div>
							</div>
						</form>	
					</div>
					
					
					
				</div>
				
				
			</section>
			
			
			<pre hidden>
				<?php print_r($filas)?>
			</pre>
			
		</div>
	</body>
	
</html>							