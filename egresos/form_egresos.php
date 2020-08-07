<form id="form_egresos" autocomplete="off" class="is-validated">
	<div id="modal_egresos" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center">Nuevo <span id="titulo"></span></h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						
						
						<div class="col-12">
							<div class="form-group">
								<label >Venta:</label>
								<input  type="number" readonly class="form-control" id="egresos_id_ventas" name="id_ventas" >
						
							</div>
							<div class="form-group">
								<label >Fecha:</label>
								<input required type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d")?>"> 
							</div>
							<div class="form-group">
								<label >Area:</label>
								<select class="form-control" id="area" name="area">
									<option >MERCANCIA</option>
									<option >VIATICOS</option>
									<option >NOMINA</option>
									<option >COMISION</option>
								</select>
							</div>
							<div class="form-group">
								<label >Concepto:</label>
								<input required type="text" class="form-control" name="concepto" placeholder="">
							</div>
							<div class="form-group">
								<label for="">Importe:</label>
								<input required class="form-control" type="number" name="importe" id="importe">
							</div>
							
							
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success" >
						<i class="fa fa-save"></i> Guardar
					</button>
				</div>
			</div>
		</div>
	</div>
</form>	