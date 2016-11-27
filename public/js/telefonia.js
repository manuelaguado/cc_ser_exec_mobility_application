function modal_add_celular(){
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/nuevo_cel',
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red TEL-01');}	
		});
	} );
}
function nuevo_celular(){
	var msj_error="";
	if( $('#serie').get(0).value == "" ) msj_error+='Olvidó ingresar el número de serie.<br />';
	if( $('#numero').get(0).value == "" ) msj_error+='Olvidó ingresar el número de celular<br />';

	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/nuevo_cel_do',
			type: 'POST',
			data: $("#nuevo_equipo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#telefonia').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red TEL-02');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red TEL-03');}	
		});
	} );
}
function modal_edit_celular(id_celular){
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/editar/' + id_celular,
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red TEL-04');}	
		});
	} );
}
function editar_celular(){
	var msj_error="";
	if( $('#serie').get(0).value == "" ) msj_error+='Olvidó ingresar el número de serie.<br />';
	if( $('#numero').get(0).value == "" ) msj_error+='Olvidó ingresar el número de celular<br />';
	if( $('#cat_status_celular').get(0).value == "" ) msj_error+='Olvidó seleccionar el estado del equipo<br />';
	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/editar_cel_do',
			type: 'POST',
			data: $("#equipo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#telefonia').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red TEL-05');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red TEL-06');}	
		});
	} );
}
function modal_asignar_celular(id_celular){
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/asignar/' + id_celular,
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red TEL-07');}	
		});
	} );
}
function asignar_celular(){
	var msj_error="";
	if( $('#id_operador').get(0).value == "" ) msj_error+='Olvidó seleccionar al operador al que le asignará el equipo.<br />';
	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	
	$(document).ready(function() {
		$.ajax({
			url: 'telefonia/asignar_celular_do',
			type: 'POST',
			data: $("#asignacion").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#telefonia').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red TEL-08');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red TEL-09');}	
		});
	} );
}