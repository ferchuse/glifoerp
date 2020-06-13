<form id="form_contratos" autocomplete="off" class="was-validated">
	<div id="modal_contratos" class="modal fade" role="dialog">
		<div class="modal-dialog ">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title text-center">Nuevo Contrato</h3>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="text" hidden id="id_clientes" name="id_clientes">
					<div class="row">
						<div class="col-sm-12">
							<div class="form-group">
								<label for="razon_social_clientes">Cliente:</label>
								<?php echo generar_select($link, "clientes", "id_clientes", "razon_social_clientes")?>
							</div>
							<div class="form-group">
								<label for="concepto">Concepto:</label>
								<input  class="form-control" type="text" name="concepto" id="concepto">
							</div>
							<div class="form-group">
								<label for="importe_total">Importe:</label>
								<input  class="form-control" type="number" step="any " name="importe_total" id="importe_total">
							</div>
							<div class="form-group">
								<label for="fecha_inicial">Fecha Inicial:</label>
								<input  class="form-control" type="date"  name="fecha_inicial" id="fecha_inicial">
							</div>
							<div class="form-group">
								<label for="fecha_final">Fecha Final:</label>
								<input  class="form-control" type="date"  name="fecha_final" id="fecha_final">
							</div>
							<div class="form-group">
								<label for="periodicidad">Periodicidad:</label>
								<select class="form-control" name="periodicidad" id="periodicidad">
									<option >Semanal</option>
									<option >Quincenal</option>
									<option >Mensual</option>
								</select>
							</div>
							<div class="form-group">
								<label for="telefono">Num de Pagos:</label>
								<input   class="form-control" type="number" name="telefono" id="telefono">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i> Cerrar</button>
					<button type="submit" class="btn btn-success" id="btn_formAlta">
						<i class="fa fa-save"></i> Guardar
					</button>
				</div>
			</div>
		</div>
	</div>
</form>	