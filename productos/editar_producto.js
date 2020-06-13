$('#form_productos').submit(guardarProducto );

function guardarProducto(event) {
		event.preventDefault();
		var boton = $(this).find(':submit');
		var icono = boton.find('.fa');
		boton.prop('disabled', true);
		icono.toggleClass('fa-save fa-spinner fa-spin');
		
		var formulario = $(this).serializeArray();
		console.log("formulario: ", formulario)
		$.ajax({
			url: '../productos/guardar_productos.php',
			dataType: 'JSON',
			method: 'POST',
			data: formulario
			}).done(function (respuesta) {
			console.log(respuesta);
			if (respuesta.estatus == "success") {
				alertify.success('Se ha guardado correctamente');
				$('#modal_productos').modal('hide');
				listaProductos();
				} else {
				alertify.error('Error al guardar');
				//console.log(respuesta.mensaje);
			}
			}).fail(function(jqXHR, textStatus, errorThrown){
				alertify.error("Ocurrió un Error"+ errorThrown);
			
			}).always(function () {
			boton.prop('disabled', false);
			icono.toggleClass('fa-save fa-spinner fa-spin');
		});
		
	}
	//CANTIDAD CONTENEDORA
	$('#cantidad_contenedora').keyup(function () {
		var cantidad_contenedora = Number($(this).val());
		var costo_proveedor = Number($('#costo_proveedor').val());
		
		if (costo_proveedor != '') {
			var costo_pz = costo_proveedor / cantidad_contenedora;
			$('#costo_unitario').val(costo_pz.toFixed(2));
			
			if (costo_pz != '') {
				
				//ganancia menudeo
				var ganancia_menudeo_porc = Number($('#ganancia_menudeo_porc').val());
				var ganancia_menudeo_pesos = (ganancia_menudeo_porc * costo_pz) / 100;
				$('#ganancia_menudeo_pesos').val(ganancia_menudeo_pesos.toFixed(2));
				
				//precio mayoreo
				var precio_menudeo = costo_pz + ganancia_menudeo_pesos;
				$('#precio_menudeo').val(precio_menudeo.toFixed(2));
				
			}
		}
	});
	
	//COSTO PROVEEDOR
	$('#costo_proveedor').keyup(function modificarPrecio() {
		console.log("modificarPrecio");
		var costo_proveedor = Number($(this).val());
		var factor = Number($('#factor').val());
		var ganancia_mayoreo_porc = Number($('#ganancia_mayoreo_porc').val());
		
		if (ganancia_mayoreo_porc != '') {
			
			//ganancia mayoreo
			var ganancia_mayoreo_pesos = (ganancia_mayoreo_porc * costo_proveedor) / 100;
			$('#ganancia_mayoreo_pesos').val(ganancia_mayoreo_pesos.toFixed(2));
			// $('#precio_mayoreo').val((costo_proveedor+ganancia_mayoreo_pesos).toFixed(2));
		}
		
		if (factor != '') {
			var costo_pz = costo_proveedor / factor;
			$('#costo_unitario').val(costo_pz.toFixed(2));
			
			if (costo_pz != '') {
				
				var ganancia_menudeo_porc = Number($('#ganancia_menudeo_porc').val());
				var ganancia_menudeo_pesos = (ganancia_menudeo_porc * costo_pz) / 100;
				$('#ganancia_menudeo_pesos').val(ganancia_menudeo_pesos.toFixed(2));
				
				var precio_menudeo = costo_pz + ganancia_menudeo_pesos;
				$('#precio_menudeo').val(precio_menudeo.toFixed(2));
				
			}
		}
	});
	
	//GANANCIA MAYOREO PORCENTAJE
	$('#ganancia_mayoreo_porc').keyup(function () {
		var ganancia_mayoreo_porc = Number($(this).val());
		var costo_proveedor = Number($('#costo_proveedor').val());
		
		if (costo_proveedor != '') {
			var ganancia_mayoreo_pesos = (ganancia_mayoreo_porc * costo_proveedor) / 100;
			$('#ganancia_mayoreo_pesos').val(ganancia_mayoreo_pesos.toFixed(2));
			var precio_mayoreo = ganancia_mayoreo_pesos + costo_proveedor;
			$('#precio_mayoreo').val(precio_mayoreo.toFixed(2));
		}
		
	});
	
	//PRECIO MAYOREO
	$('#precio_mayoreo').keyup(function () {
		var precio_mayoreo = Number($(this).val());
		var costo_proveedor = Number($('#costo_proveedor').val());
		
		if (costo_proveedor != '') {
			var ganancia_mayoreo_pesos = precio_mayoreo - costo_proveedor;
			$('#ganancia_mayoreo_pesos').val(ganancia_mayoreo_pesos.toFixed(2));
			
			var ganancia_mayoreo_porc = ((precio_mayoreo * 100) / costo_proveedor) - 100;
			$('#ganancia_mayoreo_porc').val(ganancia_mayoreo_porc.toFixed(2));
		}
	});
	
	//GANANCIA MENUDEO PORCENTAJE
	$('#ganancia_menudeo_porc').keyup(function () {
		console.log("calculaPrecioVenta");
		
		var ganancia_menudeo_porc = Number($(this).val());
		// var costo_unitario = Number($('#costo_unitario').val());
		var costo_unitario = Number($('#costo_proveedor').val());
		
		if (costo_unitario != '') {
			var ganancia_menudeo_pesos = (ganancia_menudeo_porc * costo_unitario) / 100;
			$('#ganancia_menudeo_pesos').val(ganancia_menudeo_pesos.toFixed(2));
			var precio_menudeo = costo_unitario + ganancia_menudeo_pesos;
			$('#precio_menudeo').val(precio_menudeo.toFixed(2));
		}
		
	});
	
	//PRECIO MENUDEO
	$('#precio_menudeo').keyup(function calculaGanancia() {
		console.log("calculaGanancia()")
		var precio_menudeo = Number($(this).val());
		var costo_unitario = Number($('#costo_proveedor').val());
		
		if (costo_unitario != '') {
			var ganancia_menudeo_porc = ((precio_menudeo * 100) / costo_unitario) - 100;
			$('#ganancia_menudeo_porc').val(ganancia_menudeo_porc.toFixed(2));
			var ganancia_menudeo_pesos = precio_menudeo - costo_unitario;
			$('#ganancia_menudeo_pesos').val(ganancia_menudeo_pesos.toFixed(2));
			
		}
	});
	
	//EXISTENCIA EN CAJAS/PZ
	$('#existentes_contenedores').keyup(function () {
		var existentes_contenedores = Number($(this).val());
		var cantidad_contenedora = Number($('#cantidad_contenedora').val());
		
		if (cantidad_contenedora != '') {
			var existencia_productos = existentes_contenedores * cantidad_contenedora;
			$('#existencia_productos').val(existencia_productos.toFixed(2));
		}
	});
	
	$('#existentes_contenedores_inv').keyup(function () {
		var existentes_contenedores = Number($(this).val());
		var cantidad_contenedora = Number($('#cantidad_contenedora').val());
		
		if (cantidad_contenedora != '') {
			var existencia_productos = existentes_contenedores * cantidad_contenedora;
			$('#existencia_productos_inv').val(existencia_productos.toFixed(2));
		}
	});
	
	