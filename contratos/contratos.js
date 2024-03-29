
var boton, icono;


function onLoad() {
	console.log("onLoad");
	$("#btn_nuevo").click(function nuevo() {
		console.log("nuevo");
		$('#form_contrato')[0].reset();
		$("#modal_contrato").modal("show");
	});
	
	$("#lista_registros").on("click", ".badge-danger", mostrarAbono);
	
	$("#form_filtros").submit(listarRegistros);
	
	$("#form_filtros").submit();
	
	$("#form_abonos").submit(guardarAbono);
	
	
	$(".buscar").keyup(buscarFila);
	$(".buscar").change(buscarFila);
	
	
	$('#form_contrato').submit(guardarContrato);
	
}
function listarRegistros(event) {
	event.preventDefault();
	boton = $(this).find(":submit");
	icono = boton.find("i");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
	
	$.ajax({
		"url": "tabla_contratos.php",
		"data": $("#form_filtros").serialize()
	}).done(alCargar);
}


function alCargar(respuesta) {
	$("#lista_registros").html(respuesta);
	
	$('.buscar').prop("disabled", false);
	
	// $('.btn_editar').click(editarCliente);
	$('.btn_historial').click(cargarHistorial);
	$('.sort').click(ordenarTabla);
	
	$('.btn_cargos').click( function () {
		$('#modal_cargos').modal('show');
		$('#cargos_id_clientes').val($(this).data('id_registro'));
		$('#saldo_anterior').val($(this).data('saldo'));
		$('#tipo').val("cargos");
		$("#titulo").text("Cargo");
		
	});
	
	$('.btn_abonos').click(function () {
		$('#modal_cargos').modal('show');
		$('#cargos_id_clientes').val($(this).data('id_registro'));
		$('#saldo_anterior').val($(this).data('saldo'));
		$('#tipo').val("abonos");
		$('#titulo').text("Abono");
		
	});
	
	
	
	contarRegistros("tabla_registros");
	
	boton.prop("disabled", false);
	icono.toggleClass("fa-search fa-spinner fa-spin");
	
}


function mostrarAbono(event) {
	console.log("mostrarAbono()")
	$("#id_cargos").val($(this).data("id_cargos"))
	$("#abono_concepto").val($(this).data("concepto"))
	$("#abono_importe").val($(this).data("importe"))
	$("#abono_id_clientes").val($(this).data("id_clientes"))
	$("#modal_abonos").modal("show")
}	


function ordenarTabla() {
	$(this).toggleClass("asc desc");
	console.log("ordenarTabla");
	
	if(	$("#order").val() ==  "ASC"){
		$("#order").val("DESC");
	}
	else{
		$("#order").val("ASC");
	}
	
	$("#sort").val($(this).data("columna"));
	$('#form_filtros').submit();
}

function contarRegistros(id_tabla) {
	console.log("contarRegistros", $("#"+id_tabla+" tbody tr:visible"));
	
	$("#contar_registros").text($("#"+id_tabla+" tbody tr:visible").length);
}


$(document).ready(onLoad);

function cargarHistorial() {
	
	let boton = $(this);
	let icono = boton.find(".fas");
	let id_clientes = boton.data("id_registro");
	let nombre = boton.data("nombre");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-history fa-spinner fa-spin");
	
	$.ajax({
		url: "modal_historial.php",
		data: {
			"id_clientes": id_clientes
		}
		
		}).done(function (respuesta) {
		
		$("#historial").html(respuesta);
		$("#modal_historial").modal("show");
		$(".btn_borrar_transaccion").click(borrarTransaccion);
		$("#nombre_historial").text(nombre);
		
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-history fa-spinner fa-spin");
		
	});
	
	
}


function borrarTransaccion() {
	console.log("borrarTransaccion");
	let boton = $(this);
	let icono = boton.find(".fas");
	let id_transaccion = boton.data("id_registro");
	let tipo = boton.data("tipo");
	let tabla = tipo == "CARGO" ? "cargos" : "abonos";
	let id_campo = tipo == "CARGO" ? "id_cargos" : "id_abonos";
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-trash fa-spinner fa-spin");
	
	$.ajax({
		url: "../funciones/fila_delete.php",
		method: "POST",
		dataType: "JSON",
		data: {
			"tabla": tabla,
			"id_campo": id_campo,
			id_valor: id_transaccion
			
		}
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		
		boton.closest("tr").remove();
		
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-trash fa-spinner fa-spin");
		
	});
	
	
}

function guardarAbono(event) {
	event.preventDefault();
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "consultas/guardar_abono.php",
		dataType: "JSON",
		method: "POST",
		data: $("#form_abonos").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta["status"]["abono"] == "success") {
			
			$("#modal_abonos").modal("hide");
			$("#form_filtros").submit();
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
	
}


function guardarContrato(event) {
	
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "consultas/guardar_contrato.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_contrato").serialize()
		
		}).done(function (respuesta) {
		console.log("respuesta", respuesta);
		if (respuesta.status["contrato"] == "success") {
			
			alertify.success(respuesta.mensaje);
			$("#modal_contrato").modal("hide");
			listarRegistros();
		}
		}).fail(function (xht, error, errnum) {
		
		alertify.error("Error", errnum);
		}).always(function () {
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}

function buscarFila(event) {
	var value = $(this).val().toLowerCase();
	console.log("buscando", value);
	$("#lista_registros tbody tr").filter(function() {
		$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
	});
}	