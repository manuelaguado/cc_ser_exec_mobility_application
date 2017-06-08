function accion_operatorGroup(id_tabla){
	$(document).ready(function() {
		$('#'+ id_tabla).dataTable();
		$('#'+ id_tabla +' tbody').on('click', 'tr', function () {
			var id = $('td', this).eq(10).text();
			carga_archivo('contenedor_principal', url_app + 'ingresosoperador/viajes_operador/' + id);
		} );
	} );
}
function pausar_viaje(id_viaje){
	$.ajax({
		url: 'ingresosoperador/pausar_viaje/' + id_viaje,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-01');}
	});
}
function pausar_viaje_do(id_viaje){
	$.ajax({
		url: 'ingresosoperador/pausar_viaje_do/' + id_viaje,
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$('#myModal').modal('hide');
				$('#viajes_operador').DataTable().ajax.reload();
			}else{
				alerta('Alerta!','Error de conectividad de red INOP-02');
			}
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-03');}
	});
}
function variantes_viaje(id_viaje){
	$.ajax({
		url: 'ingresosoperador/variantes_viaje/' + id_viaje,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-04');}
	});
}
function mapsroutes(id_viaje){
	$.ajax({
		url: 'ingresosoperador/mapsroutes/' + id_viaje,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-05');}
	});
}
