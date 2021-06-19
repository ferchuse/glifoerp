
$("#form_reportes").submit(listarAbonos);

$(".btn_comisiones").click(function(){
	$("#filtro_id_vendedores").val($(this).data("id_vendedores"));
	$("#filtro_vendedores").val($(this).data("nombre_vendedores"));
	$("#reporte_comisiones").collapse("show");
	$("#lista_ventas").html("");
});

function listarAbonos(event){
	event.preventDefault();
	console.log("listarAbonos()");
	let $boton = $(this).find(":submit");
	let $icono = $(this).find(".fas");
	
	$boton.prop("disabled", true);
	$icono.toggleClass("fa-search fa-spinner fa-spin");			
	
	$.ajax({ 
		"url": "consultas/lista_abonos.php",
		"method": "POST",
		"data": $("#form_reportes").serialize()
		}).done( function alTerminar (respuesta){					
		
		$("#listar_registros").html(respuesta);
		// $(".clickable").click(detalleComisiones);
		
		}).always(function(){
		
		$boton.prop("disabled", false);
		$icono.toggleClass("fa-search fa-spinner fa-spin");	
	});
}


