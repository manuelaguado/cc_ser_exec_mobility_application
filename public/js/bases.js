function modal_add_base(){
	$(document).ready(function() {
		$.ajax({
			url: 'bases/nueva_base',
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red BAS-01');}	
		});
	} );
}
function nueva_base(){
	var msj_error="";
	if( $('#descripcion').get(0).value == "" ) msj_error+='Olvidó ingresar la descripción.<br />';
	if( $('#ubicacion').get(0).value == "" ) msj_error+='Olvido ingresar la ubicación<br />';
	if( $('#cat_tipobase').get(0).value == "" ) msj_error+='Olvido seleccionar el tipo de base<br />';
	if( $('#longitud').get(0).value == "" ) msj_error+='Olvido ingresar la longitud<br />';
	if( $('#latitud').get(0).value == "" ) msj_error+='Olvido ingresar la latitud<br />';
	if( $('#geocerca').get(0).value == "" ) msj_error+='Olvido ingresar la geocerca<br />';
	if( $('#clave').get(0).value == "" ) msj_error+='Olvido ingresar la clave<br />';
	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	
	$(document).ready(function() {
		$.ajax({
			url: 'bases/nueva_base_do',
			type: 'POST',
			data: $("#nueva_base").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#bases').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red BAS-02');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red BAS-03');}	
		});
	} );
}
function modal_asignar_bases(operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'bases/asignar_bases/' + operador_unidad,
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red BAS-04');}	
		});
	} );
}
function asignarBase(operador_unidad,id_base) {
	$(document).ready(function() {
		var estado = document.getElementById("idenbase_" + id_base).checked;
		$.ajax({
			url: url_app + 'bases/asignarBase/' + operador_unidad + '/' + id_base + '/' + estado,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					/*nada que hacer*/
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-06');}	
		});
	} );
}
function accion_bases(id_tabla){
	$(document).ready(function() {
		$('#'+ id_tabla).dataTable();
		$('#'+ id_tabla +' tbody').on('click', 'tr', function () {
			var id = $('td', this).eq(0).text();
			$.ajax({
				url: 'bases/edita_base/' + id,
				dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
				error: function(respuesta){ alerta('Alerta!','Error de conectividad de red UNIT-07');}	
			});
		} );
	} );
}
function edita_base(){
	var msj_error="";
	if( $('#descripcion').get(0).value == "" ) msj_error+='Olvidó ingresar la descripción.<br />';
	if( $('#ubicacion').get(0).value == "" ) msj_error+='Olvido ingresar la ubicación<br />';
	if( $('#cat_tipobase').get(0).value == "" ) msj_error+='Olvido seleccionar el tipo de base<br />';
	if( $('#longitud').get(0).value == "" ) msj_error+='Olvido ingresar la longitud<br />';
	if( $('#latitud').get(0).value == "" ) msj_error+='Olvido ingresar la latitud<br />';
	if( $('#geocerca').get(0).value == "" ) msj_error+='Olvido ingresar la geocerca<br />';
	if( $('#clave').get(0).value == "" ) msj_error+='Olvido ingresar la clave<br />';
	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	
	$(document).ready(function() {
		$.ajax({
			url: 'bases/edita_base_do',
			type: 'POST',
			data: $("#nueva_base").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#bases').DataTable().ajax.reload();
					$('#myModal').modal('hide');
				}else{
					alerta('Alerta!','Error de conectividad de red BAS-08');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red BAS-09');}	
		});
	} );
}