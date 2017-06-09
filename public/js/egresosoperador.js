function modal_nuevo_cobro(){
	$(document).ready(function() {
		$.ajax({
			url: 'egresosoperador/add_nuevo_cobro',
			dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-01');}
		});
	} );
}
function nuevo_cobro(){
	var msj_error="";
	if( $('#concepto').get(0).value == "" ) msj_error+='Ingrese el nombre que recibirá el concepto de cobro.<br />';
	if( $('#monto').get(0).value == "")	msj_error+='Ingrese el monto que cobrará por el concepto.<br />';
	if( $('#cat_periodicidad').get(0).value == "")	msj_error+='Seleccione la periodicidad del concepto de cobro.<br />';
	if( !msj_error == "" ){
		alerta_div('error_alerta','Error en la captura de datos.',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'egresosoperador/add_nuevo_cobro_do',
			type: 'POST',
			data: $("#nuevo_cobro").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#conceptos').DataTable().ajax.reload();
				}else{
					 alerta('Alerta!','Error de conectividad de red EGOP-02');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-03');}
		});
	} );
}
function eliminar_cobro(id_concepto){
	$.ajax({
		url: 'egresosoperador/eliminar_cobro/' + id_concepto,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-04');}
	});
}
function eliminar_cobro_do(id_concepto){
       $.ajax({
              url: 'egresosoperador/eliminar_cobro_do/' + id_concepto,
              dataType: 'json',
              success: function(resp_success){
                     if (resp_success['resp'] == true) {
                            $('#myModal').modal('hide');
                            $('#conceptos').DataTable().ajax.reload();
                     }else{
                            alerta('Alerta!','Error de conectividad de red EGOP-05');
                     }
              },
              error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-06');}
       });
}
function editar_cobro(id_concepto){
       $.ajax({
		url: 'egresosoperador/editar_cobro/' + id_concepto,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-07');}
	});
}
function editar_cobro_do(){
       $.ajax({
              url: 'egresosoperador/editar_cobro_do',
              type: 'POST',
              data: $("#cobro_editado").serialize(),
              dataType: 'json',
              success: function(resp_success){
                     if (resp_success['resp'] == true) {
                            $('#myModal').modal('hide');
                            $('#conceptos').DataTable().ajax.reload();
                     }else{
                            alerta('Alerta!','Error de conectividad de red EGOP-08');
                     }
              },
              error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-09');}
       });
}
function establecer_cobro(id_operador) {
	$(document).ready(function() {
		var operador = escape(id_operador);
		var estado = document.getElementById("permission_" + operador).checked;
		var id_concepto = document.getElementById("id_concepto").value;
		$.ajax({
			url: url_app + 'egresosoperador/establecer_cobro/' + id_concepto + '/' + operador + '/' + estado ,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					//alerta('Alerta!','ok');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-10');}
		});
	} );
}
function activarc12t3(id_viaje){
	$.ajax({
		url: 'egresosoperador/activarc12t3/' + id_viaje,
		dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-11');}
	});
}
function activarc12t3_do(id_viaje){
	$.ajax({
		url: 'egresosoperador/activarc12t3_do/' + id_viaje,
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$('#myModal').modal('hide');
				$('#c12').DataTable().ajax.reload();
			}else{
				alerta('Alerta!','Error de conectividad de red EGOP-12');
			}
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red EGOP-13');}
	});
}
