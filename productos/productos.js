function listaProductos() {
	console.log("listaProductos() ");
	let tableTemplate;
	let semaforo, badge = '';
	let $boton = $("#form_filtros").find(":submit");
	let $icono = $boton.find(".fa");
	$boton.prop("disabled", true)
	$icono.toggleClass("fa-search fa-spinner fa-spin");
	$.ajax({
		url: 'lista_productos.php',
		data: $("#form_filtros").serializeArray()
		}).done(function (respuesta) {
		
		
		$('#bodyProductos').html(respuesta)
		contarProductos();
		$("#tabla_productos").stickyTableHeaders();
		$boton.prop("disabled", false)
		$icono.toggleClass("fa-search fa-spinner fa-spin");
		
		//----FILTROS DE BUSCAR------
		$(".buscar_codigo").keyup( function filtro_buscar() {
			var indice = $(this).data("indice");
			var valor_filtro = $(this).val();
			var num_rows = buscar(valor_filtro, 'tabla_productos', indice);
			if (num_rows == 0) {
				$('#mensaje').html("<div class='alert alert-warning text-center'><strong>No se ha encontrado.</strong></div>");
				} else {
				$('#mensaje').html('');
			}
			
		});
		
		$(".buscar").on("keyup", function buscarFila(event) {
			var value = $(this).val().toLowerCase();
			console.log("buscando", value);
			$("#bodyProductos .row").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			});
		});
		
		
		$('.btn_eliminar').click(confirmaEliminar);
		
		$('.btn_editar').click( editarRegistro);
		
		
	});
}



$(document).ready(function () {
	$('.sort').click(ordenarTabla);
	
	
	$("#form_filtros select").change(function() {
		
		$("#form_filtros").submit();
	});
	
	$("#form_filtros").submit(function () {
		listaProductos();
		return false;
	})
	
	if($("#cookie_permiso_usuarios").val() == "vendedor"){
		var permiso = "d-none";
	}
	else{
		var permiso = "d-sm-flex";
	}
	
	
	
	listaProductos();
	
	$('#btn_nuevo').click(function () {
		$('#form_productos')[0].reset();
		$('h3.modal-title').text('Nuevo Producto');
		$('#modal_productos').modal('show');
	});
	//--------CHECAR DUPLICADOS------
	// $('#codigo_productos').keyup(function () {
	// var producto = $(this).val();
	// $.ajax({
	// url: 'control/checar_repetidos.php',
	// method: 'POST',
	// dataType: 'JSON',
	// data: { producto: producto }
	// }).done(function (respuesta) {
	// if (respuesta.repetidos > 0) {
	// $('#btn_formAlta').prop('disabled', true);
	// $('#respuesta_rep').text('(Existentente)');
	// } else {
	// $('#btn_formAlta').prop('disabled', false);
	// $('#respuesta_rep').text('');
	// }
	// });
	// });
	//-------ALTA DE PRODUCTOS-----
	
	
	
});

function editarRegistro() {
	$('#form_productos')[0].reset();
	var boton = $(this);
	var icono = boton.find('.fa');
	icono.toggleClass('fa-pencil fa-spinner fa-spin fa-floppy-o');
	boton.prop('disabled', true);
	var id_registro = boton.data('id_producto');
	$.ajax({
		url: '../funciones/fila_select.php',
		method: 'GET',
		dataType: 'JSON',
		data: { 
			tabla: 'productos',
			id_campo: 'id_productos',
			id_valor: id_registro
		}
		}).done(function (respuesta) {
		if (respuesta.encontrado == 1) {
			$.each(respuesta["data"], function (name, value) {
				
				$.each(respuesta["data"], function (name, value) {
					$("#form_productos").find("#" + name).val(value);
				});
				
			});
			$('h3.modal-title').text('Editar Producto');
			$('#modal_productos').modal('show');
		}
		icono.toggleClass('fa-pencil fa-spinner fa-spin fa-floppy-o');
		boton.prop('disabled', false);
	});
	
}


function contarProductos() {
	
	$('#cantidad_productos').html($("#bodyProductos .row:visible").length);
	
}




function buscar(filtro, table_id, indice) {
	// Declare variables 
	var filter, table, tr, td, i;
	filter = filtro.toUpperCase();
	table = document.getElementById(table_id);
	tr = table.getElementsByTagName("tr");
	
	// Loop through all table rows, and hide those who don't match the search query
	for (i = 0; i < tr.length; i++) {
		td = tr[i].getElementsByTagName("td")[indice];
		if (td) {
			if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
				tr[i].style.display = "";
				} else {
				tr[i].style.display = "none";
			}
		}
	}
	contarProductos();
	var num_rows = $(table).find('tbody tr:visible').length;
	return num_rows;
}


function confirmaEliminar() {
	var boton = $(this);
	var icono = boton.find('.fa');
	var fila = boton.closest('.row');
	boton.prop('disabled', true);
	icono.toggleClass('fa-trash fa-spinner fa-spin fa-floppy-o');
	var id_registro = boton.data('id_producto');
	function eliminarProductos() {
		$.ajax({
			url: '../funciones/fila_delete.php',
			method: 'POST',
			dataType: 'JSON',
			data: { tabla: 'productos', id_campo: 'id_productos', id_valor: id_registro }
			}).done(function (respuesta) {
			if (respuesta.estatus == 'success') {
				alertify.success('Se ha eliminado correctamente');
				fila.fadeOut(2000);
				} else {
				alertify.error('No se ha podido eliminar');
			}
			}).always(function () {
			boton.prop('disabled', false);
			icono.toggleClass('fa-trash fa-spinner fa-spin fa-floppy-o');
		});
	}
	alertify.confirm('Confirmacion', '¿Desea eliminarlo?', eliminarProductos, function () {
		icono.toggleClass("fa-trash fa-spinner fa-spin fa-floppy-o");
		boton.prop('disabled', false);
	});
}

function ordenarTabla() {
	console.log("ordenarTabla");
	$(".sort").removeClass("asc desc");
	
	if(	$("#order").val() ==  "ASC"){
		$("#order").val("DESC");
		$(this).addClass("asc");
		$(this).removeClass("desc");
		
	}
	else{
		$("#order").val("ASC");
		$(this).addClass("desc");
		$(this).removeClass("asc");
	}
	
	$("#sort").val($(this).data("columna"));
	$('#form_filtros').submit();
}