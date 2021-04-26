<form id="form_abonos" autocomplete="off" class="is-validated">
	<div id="modal_abonos" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center">Nuevo Abono</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-12">
							<div class="form-group">
								<label for="">Id Cargo:</label>
								<input  readonly  class="form-control" type="number" name="id_cargos" id="id_cargos">
							</div>
							<div class="form-group">
								<label for="">Id Cliente:</label>
								<input  readonly  class="form-control" type="number" name="id_clientes" id="abono_id_clientes">
							</div>
							<div class="form-group">
								<label >Fecha:</label>
								<input required type="date" class="form-control" name="fecha" value="<?php echo date("Y-m-d")?>"> 
							</div>
							<div class="form-group">
								<label >Concepto:</label>
								<input required type="text" class="form-control" name="concepto" id="abono_concepto" placeholder="Abono">
							</div>
							<div class="form-group">
								<label for="">Importe:</label>
								<input required class="form-control" type="number" name="importe" id="abono_importe">
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