function modal_nueva_unidad(){
	$(document).ready(function() {
		$.ajax({
			url: 'unidades/add_unidad',
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-01');}	
		});
	} );
}
function nuevo_vehiculo(){
	var msj_error="";
	if( $('#id_marca').get(0).value == "" ){ msj_error+='Olvidó seleccionar la marca.<br />'; /*$('#usuario').css({background:'#F4CECD'}); */ }
	if( $('#id_modelo').get(0).value == "" )	msj_error+='Olvidó seleccionar el modelo.<br />';
	if( $('#year').get(0).value == "")	msj_error+='Olvidó ingresar el año.<br />';
	if( $('#placas').get(0).value == "")	msj_error+='Olvidó ingresar las placas.<br />';
	if( $('#motor').get(0).value == "" )	msj_error+='Olvidó ingresar el número de motor.<br />';
	if( $('#color').get(0).value == "" )	msj_error+='Olvidó ingresar el color.<br />';
	if( !msj_error == "" ){
		alerta_div('error_alerta','Error en la captura de datos.',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'unidades/add_unidad_do',
			type: 'POST',
			data: $("#nuevo_vehiculo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#unidades').DataTable().ajax.reload();
				}else{
					 alerta('Alerta!','Error de conectividad de red UNIT-02');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-03');}	
		});
	} );
}
function accion_unidades(id_tabla){
	$(document).ready(function() {
		$('#'+ id_tabla).dataTable();
		$('#'+ id_tabla +' tbody').on('click', 'tr', function () {
			var id = $('td', this).eq(0).text();
			$.ajax({
				url: 'unidades/edita_unidad/' + id,
				dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
				error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-04');}	
			});
		} );
	} );
}
function editar_vehiculo(){
	var msj_error="";
	if( $('#id_marca').get(0).value == "" )  msj_error+='Olvidó seleccionar la marca.<br />';
	if( $('#id_modelo').get(0).value == "" )	msj_error+='Olvidó seleccionar el modelo.<br />';
	if( $('#year').get(0).value == "")	msj_error+='Olvidó ingresar el año.<br />';
	if( $('#placas').get(0).value == "")	msj_error+='Olvidó ingresar las placas.<br />';
	if( $('#motor').get(0).value == "" )	msj_error+='Olvidó ingresar el número de motor.<br />';
	if( $('#color').get(0).value == "" )	msj_error+='Olvidó ingresar el color.<br />';
	if( !msj_error == "" ){
		alerta_div('error_alerta','Error en la captura de datos.',msj_error);
		return false;
	}
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'unidades/edita_unidad_do',
			type: 'POST',
			data: $("#editar_vehiculo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#unidades').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red UNIT-05');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-06');}		
		});
	} );
}
function getModelos(){
	$(document).ready(function() {
		var id_marca = $('#id_marca').val();
		$.ajax({
			url: 'unidades/getModelos/' + id_marca,
			dataType: 'html',
			success: function(resp_success){			
				$('#id_modelo').html(resp_success);
				$("#id_modelo").removeAttr("readonly");
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-07');}	
		});
	} );
}
function asignarAutomovil(id_unidad) {
	$(document).ready(function() {
		var unidad = escape(id_unidad);
		var estado = document.getElementById("permission_" + unidad).checked;
		var id_operador = document.getElementById("id_operador").value;
		$.ajax({
			url: url_app + 'unidades/asignarAutomovil/' + id_operador + '/' + unidad + '/' + estado ,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					if((resp_success['estado'] == "true")&&(resp_success['permiso'] == true)){
						$('#relacionar_bases_' + unidad).removeClass('hidden');
						document.getElementById('relacionar_bases_' + unidad).innerHTML = "<i onclick=\"modal_asignar_bases(" + resp_success['id_operador_unidad'] + ")\" class=\"fa fa-building\"></i>";
					}else if ((resp_success['estado'] == "false")&&(resp_success['permiso'] == true)){
						$('#relacionar_bases_' + unidad).addClass('hidden');
						document.getElementById('relacionar_bases_' + unidad).innerHTML = "";
					}
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-06');}	
		});
	} );
}