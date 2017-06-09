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
function proceso249(){
	$.ajax({
		url: 'ingresosoperador/proceso249',
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
function proceso249_do(){
	$.ajax({
		url: 'ingresosoperador/proceso249_do',
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$('#myModal').modal('hide');
				$('#conceptos').DataTable().ajax.reload();
			}else{
				alerta('Alerta!','Error de conectividad de red INOP-06');
			}
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-07');}
	});
}
function accion_procesadosGroup(id_operador){
	carga_archivo('contenedor_principal', url_app + 'ingresosoperador/viajes_procesados/' + id_operador);
}
function marcar_como_pagado(id_operador){
	$.ajax({
		url: 'ingresosoperador/marcar_como_pagado/' + id_operador,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-08');}
	});
}
function marcar_como_pagado_do(id_operador){
	$.ajax({
		url: 'ingresosoperador/marcar_como_pagado_do/' + id_operador,
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$('#myModal').modal('hide');
				$('#conceptos').DataTable().ajax.reload();
			}else{
				alerta('Alerta!','Error de conectividad de red INOP-09');
			}
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red INOP-10');}
	});
}
function ver_viajes_archivados(id_operador){
	carga_archivo('contenedor_principal', url_app + 'ingresosoperador/ver_viajes_archivados/' + id_operador);
}
