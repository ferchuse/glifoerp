
var producto_elegido ;


function mostrarNuevoProducto(){
	
	$("#modal_productos").modal("show");
	
}
function calculaSaldo(){
	
	let total = Number($("#total").val());
	let anticipo = Number($(this).val());
	
	let saldo= total - anticipo;
	$("#saldo").val(saldo);
	
	
}

function nuevoCliente() {
	console.log("nuevoCliente");
	$('#form_clientes')[0].reset();
	
	$("#modal_clientes").modal("show");
	
}


function	listarClientes(){
	
	console.log("listarClientes( js)");
	
	$.ajax({
		url : "../funciones/generar_select.php",
		data:{
			"tabla": "clientes",
			"pk": "id_clientes",
			"label": "nombre_clientes"
		}
		
		}).done(function(respuesta){
		
		$("#id_clientes").html(respuesta);
		
		
	})
	
}

$(document).ready( function onLoad(){
	
	if($("#id_ventas").val() != ""){
		console.log("Editar Venta")
		cargarVenta({
			"tabla": "ventas",
			"pk": "id_ventas",
			"folio": $("#id_ventas").val(),
			"tabla_productos": "ventas_detalle"
		});
		
	}
	
	if($("#tabla_copia").val() != ""){
		console.log("Copiar Registro")
		cargarVenta({
			"tabla": $("#tabla_copia").val(),
			"pk": "id_cotizaciones",
			"folio": $("#folio_copia").val(),
			"tabla_productos": "cotizaciones_detalle"
		});
		
	}
	
	
	$(window).on('beforeunload', function(){
		return '¿Estás seguro que deseas salir?';
	});
	
	$("#btn_nueva_partida").click( function agregarPartida(){
		agregarProducto({cantidad: 1, descripcion_productos: "" , saldo : 0})
	});
	
	$("#btn_nuevo_cliente").click(nuevoCliente);
	
	$("#anticipo").keyup(calculaSaldo);
	$('.bg-info').keydown(navegarFilas);
	$("#modal_granel").on("shown.bs.modal",  ()=> { 
    $("#cantidad").focus();
	});
	$('#form_granel').submit(agregarGranel);
	$('#form_agregar_producto').submit(function(event){
		
		event.preventDefault();
	});
	
	$(document).on('keydown', disableFunctionKeys);
	
	alertify.set('notifier','position', 'top-right');
	
	
	$("#btn_nuevo_producto").click( mostrarNuevoProducto);
	
	$(".buscar").keyup( buscarDescripcion);
	
	//Autocomplete Productos https://github.com/devbridge/jQuery-Autocomplete
	$("#buscar_producto").autocomplete({
		serviceUrl: "../control/productos_autocomplete.php",   
		onSelect: function(eleccion){
			console.log("Elegiste: ",eleccion);
			// if(eleccion.data.unidad_productos == 'KG'){
			if(true){
				$("#precio_mayoreo").val(eleccion.data.precio_mayoreo);
				$("#precio_menudeo").val(eleccion.data.precio_menudeo);
				$("#precio").val(eleccion.data.precio_menudeo);
				
				$("#modal_granel").modal("show");
				$("#importe").val(eleccion.data.precio_menudeo * 1);
				producto_elegido = eleccion.data;
				$("#buscar_producto").val("");
			}
			else{
				agregarProducto(eleccion.data)
				
			}
			
			// $("#tel_clientes").val(eleccion.data.tel_clientes)
		},
		autoSelectFirst	:true , 
		showNoSuggestionNotice	:true , 
		noSuggestionNotice	: "Sin Resultados"
	});
	
	
	$('#codigo_producto').keypress( function buscarCodigo(event){
		if(event.which == 13){
			console.log("buscarCodigo()");
			var input = $(this);
			input.prop('disabled',true);
			input.toggleClass('cargando');
			var codigo_productos = $(this).val();
			$.ajax({
				url: "buscar_producto.php",
				dataType: "JSON",
				method: 'POST',
				data: {
					"campo": "codigo_productos",
					"valor": codigo_productos
				}
				}).done(function terminabuscarCodigo(respuesta){
				
				if(respuesta.numero_filas >= 1){
					console.log("Producto Encontrado");
					producto_elegido = respuesta.fila;
					
					if(producto_elegido.unidad_productos == 'PZA'){//Si el producto se vende por pieza
						
						producto_elegido.importe= producto_elegido.precioventa_menudeo_productos;
						producto_elegido.cantidad=1 ;
						agregarProducto(producto_elegido);
						
					}
					else if(producto_elegido.unidad_productos == 'KG'){ //Si el producto se vende a granel
						$('#modal_granel').modal('show');
						$("#cantidad").focus();
						$('#unidad_granel').val(1);
						$('#costo_granel').val(producto_elegido.precio_menudeo);
						$('#costoventa_granel').text('$ '+ producto_elegido.precioventa_menudeo_productos);
						
					}
					
				}
				else{
					alertify.error('Código no Encontrado');
				}
				
				}).always(function(){
				
				input.toggleClass('cargando');
				input.prop('disabled',false);
				input.focus();
			});
		} 
	});
	
	
	
	$("#cantidad").on("keyup", calcularGranel)
	$("#importe").on("keyup", calcularGranel);
	
	$("input").focus( function selecciona_input(){
		
		$(this).select();
	});
	
	
	$('#form_movimientos').submit( guardarVenta);
	$('#btn_cotizacion').click( guardarCotizacion);
	
}); 



function cargarVenta(parametros){
	console.log("cargarVenta");
	
	$.ajax({
		url: "../ventas/cargar_venta.php",
		data: parametros,
		dataType: "JSON"
	})
	.done(renderProductos);
	
}




function renderProductos(respuesta){
	console.log("renderProductos");
	console.log("venta", respuesta);
	console.log("productos", respuesta.productos);
	var productos_html = "";
	
	
	$.each(respuesta.productos, function(i, producto){
		productos_html+= `<tr class="">
		<td >
		<input hidden class="id_productos"  value="${producto['id_productos']}">
		
		<input hidden class="precio_mayoreo" value='${producto['precio_mayoreo']}'>
		<input hidden class="ganancia_porc" value='${producto['ganancia_menudeo_porc']}'>
		
		<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
		</td>
		
		<td class="w-25">
		<input  class="descripcion form-control"  value='${producto['descripcion']}'>
		<textarea placeholder="Descripción detallada" name="notas" class="notas form-control mt-2">${producto['notas']}</textarea>
		</td>
		
		<td class="text-center venta">
		<input type="number"  step="any" class="precio form-control text-right"  value='${producto['precio']}'>
		</td>	
		<td class="text-center venta">
		<input type="number" readonly step="any" class="importe form-control text-right">
		</td>
		<td class="">	
		<input type="number" class="descuento form-control"   value='0'> 
		</td>
		<td class="">	
		<input class="cant_descuento form-control"  > 
		</td>
		
		<td class="w-25">	
		<input class="existencia_anterior form-control" readonly  value='${producto['saldo']}'> 
		<input type="number" class="costo_proveedor" value='${producto['costo_proveedor']}'>
		</td>
		<td class="text-center">
		<button title="Eliminar Producto" class="btn btn-danger btn_eliminar">
		<i class="fa fa-trash"></i>
		</button> 
		</td>
		</tr>`;
		
		
		
		
		
	});
	
	
	
	
	$("#tabla_venta tbody").append(productos_html);
	
	console.log("datos Venta:",  respuesta.ventas)
	//Imprime datos de la Venta
	$("#id_vendedores").val(respuesta.ventas[0].ventas_id_vendedores);
	// $("#fecha_movimiento").val(respuesta.ventas[0].fecha_ventas);
	$("#span_id_clientes").text(respuesta.ventas[0].id_clientes);
	$("#id_clientes").val(respuesta.ventas[0].id_clientes);
	$("#buscar_clientes").val(respuesta.ventas[0].razon_social_clientes);
	
	//Asigna Callbacks de eventos
	// $(".mayoreo").change(aplicarMayoreoProducto);
	$(".cantidad").keyup(sumarImportes);
	$(".cantidad").change(sumarImportes);
	
	$("input").focus( function selecciona_input(){
		$(this).select();
	});
	
	$(".descuento").change(calcularDescuento);
	$(".descuento").keyup(calcularDescuento);
	
	$(".cant_descuento ").change(sumarImportes);
	$(".cant_descuento ").keyup(sumarImportes);
	
	$(".precio").keyup(sumarImportes);
	$(".precio").change(sumarImportes);
	
	$(".btn_eliminar").click(eliminarProducto);
	
	sumarImportes();
	
}




function calcularGranel(event){
	let precio = Number($("#precio").val());
	let cantidad = Number($("#cantidad").val());
	console.log("target",event.target.id)
	
	let importe = precio * cantidad;
	
	if(event.target.id == 'cantidad'){ 
		
		$("#importe").val(importe.toFixed(2))
	}
	else{
		importe = Number($("#importe").val());
		cantidad = importe / precio;
		
		$("#cantidad").val(cantidad.toFixed(3))
		
	}
	console.log("importe",importe )
}

function agregarGranel(event){
	event.preventDefault();
	
	producto_elegido.cantidad = $("#cantidad").val();
	$("#modal_granel").modal("hide");
	agregarProducto(producto_elegido);
	
	
}

function buscarProducto(campobd,tablabd,id_campobd){
	return $.ajax({
		url: 'control/buscar_normal.php',
		method: 'POST',
		dataType: 'JSON',
		data:{campo: campobd, tabla:tablabd, id_campo: id_campobd}
	});
}

function agregarProducto(producto){
	console.log("agregarProducto()", producto);
	
	//Buscar por id_productos, si se encuentra agregar 1 unidad sino agregar nuevo producto
	console.log("Buscando id_productos = ", producto.id_productos);
	var $existe= $(".id_productos[value='"+producto.id_productos+"']");
	console.log("existe", $existe);
	
	// if($existe.length > 0){
	if(false){
		console.log("El producto ya existe");
		let cantidad_anterior = Number($existe.closest("tr").find(".cantidad").val());
		console.log("cantidad_anterior", cantidad_anterior)
		cantidad_nueva = cantidad_anterior+ 1;
		console.log("cantidad_nueva", cantidad_nueva)
		
		$existe.closest("tr").find(".cantidad").val(cantidad_nueva);
	}
	else{
		if(!producto['cantidad']){
			
			producto['cantidad'] = 1;
		}
		console.log("El producto no existe, agregarlo a la tabla");
		$fila_producto = `<tr class="">
		<td >
		<input hidden class="id_productos"  value="${producto['id_productos']}">
		
		<input hidden class="precio_mayoreo" value='${producto['precio_mayoreo']}'>
		<input hidden class="ganancia_porc" value='${producto['ganancia_menudeo_porc']}'>
		<input hidden class="costo_proveedor" value='${producto['costo_proveedor']}'>
		<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
		</td>
		
		<td class="w-25">
		
		<input  class="descripcion form-control"  value='${producto['descripcion_productos']}'>
		</td>
		<td class="text-center venta">
		<input type="number"  step="any" class="precio form-control text-right"  value='${producto['precio_menudeo']}'>
		</td>	
		<td class="text-center venta">
		<input type="number" readonly step="any" class="importe form-control text-right">
		</td>
		<td class="">	
		<input type="number" class="descuento form-control"   value='0'> 
		</td>
		<td class="">	
		<input class="cant_descuento form-control"  > 
		</td>
		<td class="">	
		<input class="existencia_anterior form-control" readonly  value='${producto['saldo']}'> 
		</td>
		<td class="text-center">
		<button title="Eliminar Producto" class="btn btn-danger btn_eliminar">
		<i class="fa fa-trash"></i>
		</button> 
		</td>
		</tr>`;
		
		resetFondo();
		
		$("#tabla_venta tbody").append($fila_producto);
		
		
		
		//Asigna Callbacks de eventos
		
		$(".descuento").change(calcularDescuento);
		$(".descuento").keyup(calcularDescuento);
		
		$(".cant_descuento ").change(sumarImportes);
		$(".cant_descuento ").keyup(sumarImportes);
		
		$(".cantidad").keyup(sumarImportes);
		$(".cantidad").change(sumarImportes);
		
		$(".precio").keyup(sumarImportes);
		$(".precio").change(sumarImportes);
		
		$("input").focus(function(){
			$(this).select();
		});
		$(".btn_eliminar").click(eliminarProducto);
		$("#buscar_producto").val("");
		
		
	}
	
	alertify.success("Producto Agregado")
	
	sumarImportes();
	
	$("#buscar_producto").val("");
	$("#buscar_producto").focus();
}

function calcularDescuento(){
	$fila =  $(this).closest("tr");
	//Si event .target has class .descuento calcula descuento sino calcula porcentaje
	let porc_descuento = $(this);
	sumarImportes();
}

function guardarCotizacion(event){
	event.preventDefault();
	console.log("guardarVenta");
	
	if($("#id_vendedores").val() == "" || $("#id_clientes").val() == ""){
		
		alertify.error("Elige un Vendedor y el Cliente");
		return false;
	}
	
	if($("#tabla_venta tbody tr").length != 0){
		var boton = $(this);
		var icono = boton.find('.fa');
		boton.prop('disabled',true);
		icono.toggleClass('fa fa-usd fa fa-spinner fa-pulse fa-fw');
		
		let productos = [];
		
		
		$("#tabla_venta tbody tr").each(function(index, item){
			productos.push({
				"id_productos": $(item).find(".id_productos").val(),
				"cantidad": $(item).find(".cantidad").val(),
				"precio": $(item).find(".precio").val(),
				"descripcion": $(item).find(".descripcion").val(),
				"importe": $(item).find(".importe").val(),
				"existencia_anterior": $(item).find(".existencia_anterior").val(),
				"costo_proveedor": $(item).find(".costo_proveedor").val()
				
			})
		});
		
		$("#cerrar_venta").prop("disabled", true);
		
		$.ajax({
			url: '../cotizaciones/guardar_cotizacion.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				id_ventas: $('#id_ventas').val(),
				fecha_ventas: $('#fecha_movimiento').val(),
				tipo_movimiento: $('#tipo_movimiento').val(),
				id_usuarios: $('#id_usuarios').val(),
				id_turnos: $('#id_turnos').val(),
				articulos: $('#articulos').val(),
				id_vendedores: $('#id_vendedores').val(),
				id_clientes: $('#id_clientes').val(),
				total: $("#total").val(),
				anticipo: $("#anticipo").val(),
				saldo: $("#saldo").val(),
				productos: productos
			}
			}).done(function(respuesta){
			if(respuesta.estatus_movimiento == "success"){
				$("#id_ventas").val(respuesta.folio)
				alertify.success('Cotización Guardada');
				imprimirCotizacion( respuesta.folio)
				//ir a editar cotizacion despues de imprimir
			}
			}).fail(function(xhr, error, ernum){
			alertify.error("Ocurrio un error:"+ error + ernum );
			}).always(function(){
			$("#cerrar_venta").prop("disabled", false);
			
			boton.prop('disabled',false);
			icono.toggleClass('fa fa-usd fa fa-spinner fa-pulse fa-fw');
		});
	}
	else{
		alertify.error('No hay productos');
	}
	
}
function guardarVenta(event){
	event.preventDefault();
	console.log("guardarVenta");
	
	if($("#id_vendedores").val() == "" || $("#id_clientes").val() == ""){
		
		alertify.error("Elige un Vendedor y el Cliente");
		return false;
	}
	
	if($("#tabla_venta tbody tr").length != 0){
		var boton = $(this);
		var icono = boton.find('.fa');
		boton.prop('disabled',true);
		icono.toggleClass('fa fa-usd fa fa-spinner fa-pulse fa-fw');
		
		let productos = [];
		
		
		$("#tabla_venta tbody tr").each(function(index, item){
			productos.push({
				"id_productos": $(item).find(".id_productos").val(),
				"cantidad": $(item).find(".cantidad").val(),
				"precio": $(item).find(".precio").val(),
				"descripcion": $(item).find(".descripcion").val(),
				"notas": $(item).find(".notas").val(),
				"importe": $(item).find(".importe").val(),
				"existencia_anterior": $(item).find(".existencia_anterior").val(),
				"costo_proveedor": $(item).find(".costo_proveedor").val()
				
			})
		});
		
		$("#cerrar_venta").prop("disabled", true);
		
		$.ajax({
			url: 'guardar_ventas.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				id_ventas: $('#id_ventas').val(),
				fecha_ventas: $('#fecha_movimiento').val(),
				tipo_movimiento: $('#tipo_movimiento').val(),
				id_usuarios: $('#id_usuarios').val(),
				id_turnos: $('#id_turnos').val(),
				articulos: $('#articulos').val(),
				id_vendedores: $('#id_vendedores').val(),
				id_clientes: $('#id_clientes').val(),
				total: $("#total").val(),
				anticipo: $("#anticipo").val(),
				saldo: $("#saldo").val(),
				productos: productos
			}
			}).done(function(respuesta){
			if(respuesta.estatus_movimiento == "success"){
				$("#id_ventas").val(respuesta.folio)
				alertify.success('Venta Guardada');
				imprimirTicket( respuesta.folio)
				// window.location.reload(true);
				
			}
			}).fail(function(xhr, error, ernum){
			alertify.error("Ocurrio un error:"+ error + ernum );
			}).always(function(){
			$("#cerrar_venta").prop("disabled", false);
			
			boton.prop('disabled',false);
			icono.toggleClass('fa fa-usd fa fa-spinner fa-pulse fa-fw');
		});
	}
	else{
		alertify.error('No hay productos');
	}
	
}


function eliminarProducto(){
	$(this).closest("tr").remove();
	sumarImportes();
}
$("input").focus(function(){
	$(this).select();
	
});


function sumarImportes(event){
	console.log("sumarImportes()");
	
	let subtotal = 0;
	let descuento = 0;
	let total = 0;
	let articulos = 0;
	let importe = 0;
	let ahorro = 0;
	let porc_descuento = 0;
	let total_descuento = 0;
	let anticipo = Number($("#anticipo").val());
	
	$("#tabla_venta tbody tr").each(function(indice, item ){
		let fila = $(this);
		let descuento = Number(fila.find(".descuento").val());
		let cant_descuento = Number(fila.find(".cant_descuento").val());
		let cantidad = Number(fila.find(".cantidad").val());
		let precio =  Number(fila.find(".precio").val());
		
		importe= cantidad * precio;
		subtotal+= importe;
		total_descuento+= cant_descuento;
		
		
		//Si la unidad es a granel solo contar 1 articulo
		if($(this).find(".unidad").val() == 'KG'){
			articulos+= 1;
		}
		else{
			articulos+= Math.round(cantidad);
		}
		
		console.log("importe", importe, indice)
		fila.find(".importe").val(importe.toFixed(2))
		
	});
	
	//preguntar sobre redondeo
	
	total = subtotal - total_descuento;
	saldo = total - anticipo;
	
	
	
	// $(".nav-tabs .active .badge").text(articulos);
	$("#articulos:visible").val(articulos);
	$("#total").val(total.toFixed(2));
	$("#descuento:visible").val(total_descuento.toFixed(2));
	$("#subtotal:visible").val(subtotal.toFixed(2));
	$("#anticipo").val(anticipo.toFixed(2) );
	
	
	$("#articulos").val(articulos);
	
	$("#saldo").val(saldo.toFixed(2));
}





function imprimirTicket(id_registro){
	console.log("imprimirTicket()");
	
	document.title = "Venta " + id_registro + " " + $("#id_clientes option:selected").text();
	$.ajax({
		url: "imprimir_ventas.php",
		data:{
			id_registro : id_registro
			
		}
		}).done(function (respuesta){
		
		$("#ticket").html(respuesta); 
		
		setTimeout(function(){
			
			window.print();
		}, 1000 )
		
		}).always(function(){
		
		// boton.prop("disabled", false);
		// icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}
function imprimirCotizacion(id_registro){
	console.log("imprimirCotizacion()");
	
	document.title = "Venta " + id_registro;
	$.ajax({
		url: "../cotizaciones/imprimir_cotizacion.php",
		data:{
			id_registro : id_registro
			
		}
		}).done(function (respuesta){
		
		$("#ticket").html(respuesta); 
		
		setTimeout(function(){
			
			window.print();
		}, 1000 )
		
		}).always(function(){
		
		// boton.prop("disabled", false);
		// icono.toggleClass("fa-print fa-spinner fa-spin");
		
	});
}



function beforePrint() {
	//Antes de Imprimir
}
function afterPrint() {
	// window.location.reload(true);
}


function disableFunctionKeys(e) {
	var functionKeys = new Array(112, 113, 114, 115, 117, 118, 119, 120, 121, 122, 123);
	if (functionKeys.indexOf(e.keyCode) > -1 || functionKeys.indexOf(e.which) > -1) {
		e.preventDefault();
		
		console.log("key", e.which)
		
	}
	
	if(e.key == 'F12'){
		
		console.log("F12");
		
		$("#cerrar_venta").click()
	}
	
	if(e.key == 'F10'){
		console.log("F10");
		$("#buscar_producto").focus()
	}
	
	if(e.key == 'F11'){
		console.log("F11");
		aplicarMayoreo();
	}
	
	if(e.key == 'Escape'){
		
		console.log("ESC");
		
		$("#codigo_producto").focus()
	}
	// $input_activo = $(this);
};

function aplicarMayoreo(){
	
	console.log("aplicarMayoreo");
	
	let $fila = $("#tabla_venta tbody tr").last();
	
	let $precio_mayoreo =  $fila.find(".precio_mayoreo").val();
	
	$(".precio").last().val( $precio_mayoreo);
	
	sumarImportes();
}

//Funciona a llamar si ha terminado de imprimir
if (window.matchMedia) {
	var mediaQueryList = window.matchMedia('print');
	mediaQueryList.addListener(function(mql) {
		if (mql.matches) {
			beforePrint();
		} 
		else {
			afterPrint();
		}
	});
}

// window.onbeforeprint = beforePrint;
//window.onafterprint = afterPrint;
function buscarDescripcion(){
	var indice = $(this).data("indice");
	var valor_filtro = $(this).val();
	
	var num_rows = buscar(valor_filtro,'tabla_productos',indice);
	
	$("#cantidad_productos").text(num_rows);
	
	if(num_rows == 0){
		$('#mensaje').html("<div class='alert alert-warning text-center'><strong>No se ha encontrado.</strong></div>");
		}else{
		$('#mensaje').html('');
	}
}

function resetFondo(){
	
	$("#tabla_venta tbody tr").removeClass("bg-info");
	
}

function navegarFilas(e){
	var $table = $(this);
	var $active = $('input:focus,select:focus',$table);
	var $next = null;
	var focusableQuery = 'input:visible,select:visible,textarea:visible';
	var position = parseInt( $active.closest('td').index()) + 1;
	console.log('position :',position);
	switch(e.keyCode){
		case 37: // <Left>
		$next = $active.parent('td').prev().find(focusableQuery);   
		break;
		case 38: // <Up>                    
		$next = $active
		.closest('tr')
		.prev()                
		.find('td:nth-child(' + position + ')')
		.find(focusableQuery)
		;
		
		break;
		case 39: // <Right>
		$next = $active.closest('td').next().find(focusableQuery);            
		break;
		case 40: // <Down>
		$next = $active
		.closest('tr')
		.next()                
		.find('td:nth-child(' + position + ')')
		.find(focusableQuery)
		;
		break;
	}       
	if($next && $next.length)
	{        
		$next.focus();
	}
}												