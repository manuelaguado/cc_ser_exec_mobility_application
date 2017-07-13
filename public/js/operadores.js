function activar_operador(id_usuario){
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'operadores/activar/' + id_usuario,
			dataType: 'json',
			success: function(resp_success){
				if(resp_success['resp'] == true){
					$('#operadores').DataTable().ajax.reload();
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-01');}
		});
	} );
}
function numero_economico(operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/numero_economico/' + operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-02');}
		});
	} );
}
function numero_economico_do(){
	var msj_error="";
	if( $('#id_numeq').get(0).value == "" ) msj_error+='Olvidó seleccionar el número económico.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operadores/numero_economico_do',
			type: 'POST',
			data: $("#editar_numero_eq").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#operadores').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPER-03');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-04');}
		});
	} );
}
function liberar_numero(num){
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'operadores/liberarnumero/' + num,
			dataType: 'json',
			success: function(resp_success){
				if(resp_success['resp'] == true){
					$('#myModal').modal('hide');
					$('#operadores').DataTable().ajax.reload();
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-05');}
		});
	} );
}
function status_operador(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/status_operador/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-06');}
		});
	} );
}
function status_operador_do(){
	var msj_error="";
	if( $('#cat_statusoperador').get(0).value == "" ) msj_error+='Olvidó seleccionar el status para el operador.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operadores/status_operador_do',
			type: 'POST',
			data: $("#editar_status_operador").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#operadores').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPER-07');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-08');}
		});
	} );
}
function modal_nueva_tarifa(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/nueva_tarifa/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-09');}
		});
	} );
}
function nueva_tarifa_do(){
	var msj_error="";
	if( $('#nombre').get(0).value == "" ) msj_error+='Olvidó Escribir el nombre o descripción que le data a esta tarifa.<br />';
	if( $('#costo_base').get(0).value == "" ) msj_error+='Olvidó ingresar el costo base.<br />';
	if( $('#km_adicional').get(0).value == "" ) msj_error+='Olvidó ingresar el costo por km adicional.<br />';
	if( $('#cat_formapago').get(0).value == "" ) msj_error+='Olvidó seleccionar la forma de pago.<br />';

	var costo_base=$('#costo_base').val();
	var km_adicional=$('#km_adicional').val();
	var reg= /^\d+\.?\d{0,2}$/;
	if (!reg.test($('#costo_base').val())) msj_error+='El costo base no es válido.<br />';
	if (!reg.test($('#km_adicional').val())) msj_error+='El km adicional no es válido.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operadores/nueva_tarifa_do',
			type: 'POST',
			data: $("#nueva_tarifa").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#tarifa').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPER-10');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-11');}
		});
	} );
}
function eliminar_tarifa(id_tarifa){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/tarifas_del/' + id_tarifa,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-12');}
		});
	} );
}
function tarifas_del_do(id_tarifa){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/tarifas_del_do/' + id_tarifa,
			type: 'POST',
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#tarifa').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPER-13');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-14');}
		});
	} );
}
function modal_telefonos(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/modal_telefonos/' + id_operador,
			dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					$('#telefonos').dataTable({
						"dom": '<"top"p>'
					});
					$( "#add" ).click(function() {
						$("#add_field").css({ display: "" });
						$("#add").css({ display: "none" });
					});
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-15');}
		});
	} );
}
function graba_tel(){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/agregar_telefono',
			type: 'POST',
			data: $("#nuevo_tel").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-16');}
		});
	} );
}
function eliminar_telefono(id_telefono){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/eliminar_telefono/' + id_telefono,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-17');}
		});
	} );
}
function activar_telefono(id_telefono){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/activar_telefono/' + id_telefono,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-18');}
		});
	} );
}
function inactivar_telefono(id_telefono){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/inactivar_telefono/' + id_telefono,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-19');}
		});
	} );
}
function relacionar_autos(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/relacionar_autos/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-20');}
		});
	} );
}





function modal_domicilios(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/modal_domicilios/' + id_operador,
			dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					$('#domicilios').dataTable({
						"dom": '<"top"p>'
					});
					$( "#add" ).click(function() {
						$("#add_field").css({ display: "" });
						$("#add").css({ display: "none" });
					});
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-21');}
		});
	} );
}
function graba_dom(){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/agregar_domicilio',
			type: 'POST',
			data: $("#nuevo_dom").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-22');}
		});
	} );
}
function eliminar_domicilio(id_domicilio){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/eliminar_domicilio/' + id_domicilio,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-23');}
		});
	} );
}
function activar_domicilio(id_domicilio){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/activar_domicilio/' + id_domicilio,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-24');}
		});
	} );
}
function inactivar_domicilio(id_domicilio){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/inactivar_domicilio/' + id_domicilio,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$("#add_field").addClass("hidden");
					$('#myModal').modal('hide');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-25');}
		});
	} );
}

function modal_ver_telefonos(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/modal_ver_telefonos/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-26');}
		});
	} );
}
function modal_ver_direcciones(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/modal_ver_direcciones/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-27');}
		});
	} );
}
function historia_operador(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/historia_operador/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-28');}
		});
	} );
}
function comision_operador(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operadores/comision_operador/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-29');}
		});
	} );
}
function comision_operador_do(){
	var msj_error="";
	if( $('#comision').get(0).value == "" ) msj_error+='Ingrese el valor de la comisión.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operadores/comision_operador_do',
			type: 'POST',
			data: $("#editar_comision").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#operadores').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPER-30');
				}
			},
			error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-31');}
		});
	} );
}
function setComisionDeault(id_operador){
	$.ajax({
		url: 'operadores/setComisionDeault/' + id_operador,
		type: 'POST',
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$('#comision').val(resp_success['comision']);
			}else{
				alerta('Alerta!','Error de conectividad de red OPER-33');
			}
		},
		error: function(respuesta){alerta('Alerta!','Error de conectividad de red OPER-34');}
	});
}
