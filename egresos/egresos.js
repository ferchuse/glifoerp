
$(document).ready(function () {
	
	$('#lista_registros').on("click",".btn_egresos", function(){
		$("#form_egresos")[0].reset();
		
		
		$("#egresos_id_ventas").val($(this).data("id_registro"));
		
		$("#modal_egresos").modal("show");
	});
	$('#form_egresos').submit(guardarEgresos)
	// $('#importe').change(calcula_saldo)
	// $('#importe').keyup(calcula_saldo)
	
	
})

// function calcula_saldo(e) {
// let importe = Number($('#importe').val()); 
// let saldo_anterior = Number($('#saldo_anterior').val()); 
// let saldo_restante;
// console.log("tipo", $('#tipo').val()) ;

// if($('#tipo').val() == "cargos"){

// saldo_restante =  saldo_anterior + importe;
// }
// else{
// saldo_restante = saldo_anterior - importe;
// }

// $("#saldo_restante").val(saldo_restante);
// }

function guardarEgresos(event) {
	event.preventDefault();
	
	let boton = $(this).find(":submit");
	let icono = boton.find(".fas");
	
	boton.prop("disabled", true);
	icono.toggleClass("fa-save fa-spinner fa-spin");
	
	$.ajax({
		url: "../egresos/guardar_egresos.php",
		method: "POST",
		dataType: "JSON",
		data: $("#form_egresos").serialize()
		
		}).done(function(respuesta){
		console.log("respuesta",respuesta);
		if(respuesta.status == "success"){
			
			alertify.success(respuesta.mensaje);
			$("#modal_egresos").modal("hide");
			// window.location.reload(true);
			listarRegistros();
		}
		}).fail(function(xht, error, errnum){
		
		alertify.error("Error", errnum);
		}).always(function(){
		boton.prop("disabled", false);
		icono.toggleClass("fa-save fa-spinner fa-spin");
		
	});
	
}
