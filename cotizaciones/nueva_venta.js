
var producto_elegido ;

$(document).ready( function onLoad(){
	$('.bg-info').keydown(navegarFilas);
	$("#modal_granel").on("shown.bs.modal",  ()=> { 
    $("#cantidad").focus();
	});
	$('#nueva_partida').click(nuevaPartida);
	$('#form_granel').submit(agregarGranel);
	$('#form_agregar_producto').submit(function(event){
		
		event.preventDefault();
	});
	
	$(document).on('keydown', disableFunctionKeys);
	
	alertify.set('notifier','position', 'top-right');
	
	
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
	
}); 


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

function nuevaPartida(){
	console.log("nuevaPartida()", producto);
	
	
	$fila_producto = `<tr class="">
	<td class="w-25">
	
	<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
	</td>
	
	<td >
	<input type="text" class="descripcion" class="form-control" >
	</td>
	<td class="">
	<input type="number" readonly step="any" class="costo form-control text-right">
	</td>
	<td class="">
	<input type="number" readonly step="any" class="importe_compra form-control text-right">
	</td>	
	<td >
	<input type="number"  step="any" class="precio form-control text-right"  >
	</td>	
	</td>
	<td class="">
	<input type="number" readonly step="any" class="importe form-control text-right">
	</td>
	
	
	<td class="text-center">
	<button title="Eliminar Producto" class="btn btn-danger btn_eliminar">
	<i class="fa fa-trash"></i>
	</button> 
	</td>
	</tr>`;
	
	
	$("#tabla_venta tbody").append($fila_producto);
	
	
	
	//Asigna Callbacks de eventos
	$(".cantidad").keyup(sumarImportes);
	$(".cantidad").change(sumarImportes);
	$(".precio").keyup(sumarImportes);
	$(".precio").change(sumarImportes);
	$("input").focus(function(){
		$(this).select();
	});
	$(".btn_eliminar").click(eliminarProducto);
	// $("#buscar_producto").val("");
	
	
	alertify.success("Producto Agregado")
	
	sumarImportes();
	
	$("#buscar_producto").val("");
	$("#buscar_producto").focus();
}


function agregarProducto(producto){
	console.log("agregarProducto()", producto);
	
	//Buscar por id_productos, si se encuentra agregar 1 unidad sino agregar nuevo producto
	console.log("Buscando id_productos = ", producto.id_productos);
	var $existe= $(".id_productos[value='"+producto.id_productos+"']");
	console.log("existe", $existe);
	
	if($existe.length > 0){
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
	<td class="w-25">
	<input hidden class="id_productos"  value="${producto['id_productos']}">
	<input hidden class="descripcion" value='${producto['descripcion_productos']}'>
	<input hidden class="precio_mayoreo" value='${producto['precio_mayoreo']}'>
	<input hidden class="ganancia_porc" value='${producto['ganancia_menudeo_porc']}'>
	<input hidden class="costo_proveedor" value='${producto['costo_proveedor']}'>
	<input type="number"  step="any" class="cantidad form-control text-right"  value='${producto['cantidad']}'>
	</td>
	
	<td class="text-center">${producto['descripcion_productos']}</td>
	<td class="text-center venta">
	<input type="number"  step="any" class="precio form-control text-right"  value='${producto['precio_menudeo']}'>
	</td>	
	<td class="text-center venta">
	<input type="number" readonly step="any" class="importe form-control text-right">
	</td>
	
	<td class="w-25">	
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
	$(".cantidad").keyup(sumarImportes);
	$(".cantidad").change(sumarImportes);
	$(".precio").keyup(sumarImportes);
	$(".precio").change(sumarImportes);
	$("input").focus(function(){
		$(this).select();
	});
	$(".btn_eliminar").click(eliminarProducto);
	// $("#buscar_producto").val("");
	
	
}

alertify.success("Producto Agregado")

sumarImportes();

$("#buscar_producto").val("");
$("#buscar_producto").focus();
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
				"importe": $(item).find(".importe").val(),
				"existencia_anterior": $(item).find(".existencia_anterior").val(),
				"costo_proveedor": $(item).find(".costo_proveedor").val()
				
			})
		});
		
		$.ajax({
			url: 'guardar_ventas.php',
			method: 'POST',
			dataType: 'JSON',
			data:{
				fecha_ventas: $('#fecha_movimiento').val(),
				tipo_movimiento: $('#tipo_movimiento').val(),
				id_usuarios: $('#id_usuarios').val(),
				id_turnos: $('#id_turnos').val(),
				articulos: $('#articulos').val(),
				id_vendedores: $('#id_vendedores').val(),
				id_clientes: $('#id_clientes').val(),
				subtotal: $("#subtotal").val(),
				iva: $("#iva").val(),
				total: $("#total").val(),
				productos: productos
			}
			}).done(function(respuesta){
			if(respuesta.estatus_movimiento == "success"){
				alertify.success('Movimiento Guardado');
				imprimirTicket( respuesta.folio)
				// window.location.reload(true);
				
			}
			}).fail(function(xhr, error, ernum){
			alertify.error("Ocurrio un error:"+ error + ernum );
			}).always(function(){
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


function sumarImportes(){
	console.log("sumarImportes");
	let subtotal = 0;
	let articulos = 0;
	$(".id_productos").each(function(indice, fila ){
		let cantidad = Number($(this).closest("tr").find(".cantidad").val());
		let precio = Number($(this).closest("tr").find(".precio").val());
		articulos+=cantidad;
		importe= cantidad * precio;
		subtotal+= importe;
		$(this).closest("tr").find(".importe").val(importe.toFixed(2))
	});
	
	iva = subtotal * .16;
	total = subtotal + iva;
	$("#subtotal").val(subtotal.toFixed(2));
	$("#iva").val(iva.toFixed(2) );
	$("#total").val(total.toFixed(2));
	$("#articulos").val(articulos);
	
}





function imprimirTicket(id_registro){
	console.log("imprimirTicket()");
	
	
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



function beforePrint() {
	//Antes de Imprimir
}
function afterPrint() {
	window.location.reload(true);
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