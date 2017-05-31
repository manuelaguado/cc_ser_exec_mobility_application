function activar_f13(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f13/' + id_operador_unidad,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-01');}
		});
	} );
}

function activar_f13_do(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_f13/' + id_operador_unidad,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					setTimeout(function(){ $('#cordon').DataTable().ajax.reload(); }, 5000);
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-02');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-03');}
		});
	} );
}

function activar_a10(id_operador_unidad,base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_a10/' + id_operador_unidad + '/' + base,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-10');}
		});
	} );
}
function verificar_a10(id_operador_unidad,base){
	$(document).ready(function() {

		$.post( 'operacion/verificar_a10/' + id_operador_unidad + '/' + base);

		/*Ably

		var channel = conn.channels.get('verify_a10_'+id_operador_unidad+'');

		channel.subscribe(function(message){
			var resp = JSON.parse(message.data);
			if(resp['state'] == '1'){
				$('#cordon').DataTable().ajax.reload();
				$('#myModal').modal('hide');
			}
		});
		*/
		/*Pusher*/
		var a10_channel = pusher.subscribe('verify_a10_' + id_operador_unidad + '');
		a10_channel.bind('pusher:subscription_succeeded', function() {
			//console.log(pusher.connection.state);
		});
		a10_channel.bind('evento', function(data) {
			if(data.message.state == '1'){
				$('#cordon').DataTable().ajax.reload();
				$('#myModal').modal('hide');
			}
		});
	});
}

function activar_a10_do(id_operador_unidad,base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_a10/' + id_operador_unidad + '/' + base,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#aut_a10').css("display", "none");
					$('#aut_c06').css("display", "");
					$('#aut_c02').css("display", "");
					$('#aut_f14').css("display", "");
					$('#aut_f06').css("display", "");
					$('#aut_out').css("display", "");
					$('#dis_a10').css("display", "");

					verificar_a10(id_operador_unidad,base);

				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-11');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-12');}
		});
	} );
}
function aut_c06(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_c06/' + id_operador_unidad + '/' + id_base ,
			dataType: 'json',
				success: function(resp_success){
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon').DataTable().ajax.reload();
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-13');
					}
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-14');}
		});
	} );
}
function aut_c02(num,id_operador,id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_c02/' + id_operador_unidad + '/' + id_operador + '/' + num,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#activos').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-15');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-16');}
		});
	} );
}
function aut_f14(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_f14/' + id_operador_unidad + '/' + id_base ,
			dataType: 'json',
				success: function(resp_success){
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon').DataTable().ajax.reload();
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-17');
					}
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-18');}
		});
	} );
}
function aut_f06(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_f06/' + id_operador_unidad + '/' + id_base ,
			dataType: 'json',
				success: function(resp_success){
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon').DataTable().ajax.reload();
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-19');
					}
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-20');}
		});
	} );
}
function modal_activar_c06(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_c06/' + id_operador_unidad + '/' + id_base,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-21');}
		});
	} );
}
function modal_activar_c02(id_operador,num,id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_c02/' + id_operador + '/' + num + '/' + id_operador_unidad,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-22');}
		});
	} );
}
function modal_activar_f14(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f14/' + id_operador_unidad + '/' + id_base,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-23');}
		});
	} );
}
function modal_activar_f06(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f06/' + id_operador_unidad + '/' + id_base,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-24');}
		});
	} );
}
function modal_mensajeria(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_mensajeria/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-25');}
		});
	} );
}
function delivery_stat(id_mensaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/delivery_stat/' + id_mensaje,
			dataType: 'html',
				success: function(resp_success){
					if( resp_success == '1'){
						$('#msj_info').css('display','none');
						$('#msj_verify').css('display','none');
						$('#msj_delivery').css('display','');
					}
				},
			error: function(respuesta){ return false;}
		});
	} );
}
function guardar_mensaje(){
	var msj_error="";
	if( $('#id_operador_msg_mod').get(0).value == "" )	msj_error+='Error de conectividad de red OPRN-26';
	if( $('#mensaje').get(0).value == "")	msj_error+='Es necesario precisar el mensaje.<br />';

	if( !msj_error == "" ){
		alerta('Faltan datos', msj_error);
		return false;
	}
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'operacion/guardar_mensaje',
			type: 'POST',
			data: $("#nuevo_mensaje").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#add').css('display','none');
					$('#msj_info').css('display','none');
					$('#msj_verify').css('display','');
					$('#msj_delivery').css('display','none');
					$('#myModal').modal('hide');
				}else{
					alerta('Alerta!', 'Error de conectividad de red OPRN-27');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-28');}
		});
	} );
}
function broadcast_all(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/broadcast_all',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-29');}
		});
	} );
}
function enviar_emision(){
	var msj_error="";
	if( $('#mensaje').get(0).value == "")	msj_error+='Es necesario precisar el mensaje.<br />';

	if( !msj_error == "" ){
		alerta('Faltan datos', msj_error);
		return false;
	}
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'operacion/enviar_emision',
			type: 'POST',
			data: $("#nuevo_mensaje").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
						$('#add').css('display','none');
						$('#msj_info').css('display','none');
						$('#myModal').modal('hide');
				}else{
					alerta('Alerta!', 'Error de conectividad de red OPRN-30');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-31');}
		});
	} );
}
function check_standinLine(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/check_standinLine/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-32');}
		});
	} );
}

function set_page_remotly(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/set_page_remotly/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-33');}
		});
	} );
}

function setPageRemotly(){
	var msj_error="";
	if( $('#page').get(0).value == "")	msj_error+='Es necesario seleccionar una página de la lista.<br />';

	if( !msj_error == "" ){
		alerta('Faltan datos', msj_error);
		return false;
	}
	$(document).ready(function() {
		$.ajax({
			url: url_app + 'operacion/setPageRemotly',
			type: 'POST',
			data: $("#set_page_remotly").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
				}else{
					alerta('Alerta!', 'Error de conectividad de red OPRN-34');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-35');}
		});
	} );
}
function modal_activar_out(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_out/' + id_operador_unidad + '/' + id_base,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-36');}
		});
	} );
}
function aut_out(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_out/' + id_operador_unidad + '/' + id_base ,
			dataType: 'json',
				success: function(resp_success){
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon_kpmg').DataTable().ajax.reload();
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-37');
					}
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-38');}
		});
	} );
}
function modal_extra_origen(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_extra_origen/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-39');}
		});
	} );
}
function modal_extra_destino(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_extra_destino/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-40');}
		});
	} );
}
function removeClient(id){

	$('#client'+id).remove();
	$('#usuario_'+id).remove();

	pasajeros = $.grep(pasajeros, function(value) {
		return value != id;
	});


	var destinos = $('#pasajeros_list > div').length;
	if(destinos == 0){
		$('#user_list').addClass('hide');
		$('#spinDestinos').addClass('hide');
		$('#dta_name').html('Usuario');
		$("#id_cliente_origen").val('');
		$("#id_cliente_destino").val('');
		$("#id_cliente_origen").html('<option value="" disabled selected>Origenes guardados</option>');
		$("#id_cliente_destino").html('<option value="" disabled selected>Destinos guardados</option>');
		globalTypeUser = 'init';
		pasajeros = [];
	}
}
function procesar_servicio(){

	var msj_error="";

	if( $('input[id ^= usuario]').val() == null)
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Es necesario por lo menos un pasajero para procesar el servicio.</li>';}

	if( $('#forma_pago').val() == "")
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Debe de indicar una forma de pago antes de procesar el servicio.</li>';}

	if( $('#cat_tiposervicio').val() == "" )
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indique el tipo de servicio.</li>';}

	if(($('#cat_tiposervicio').val() == 166 )&&($('#id_operador_unidad').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar al operador que cubrirá la recepción</li>';}

	if( $('#cat_tipo_salida').val() == "" )
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indique el tipo de salida.</li>';}

	if(( $('#cat_tipo_salida').val() == 182 )&&($("input[name='sitio_select_oper']:checked" ).val() === undefined))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Seleccione al operador que va de salida por sitio.</li>';}

	if(( $('#cat_tipo_salida').val() == 181 )&&($('#id_operador_unidad').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Es requerido el operador al que asignará el viaje al aire.</li>';}

	if( $('#fecha_hora').val() == "" )
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise la hora a la cual se requiere el servicio.</li>';}

	if(( $('#geocodificacion_inversa_origen').val() == "" )&&($('#id_cliente_origen').val() === null))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Necesita indicar al menos una fuente de origen.</li>';}

	if(( $('#geocodificacion_inversa_destino').val() == "" )&&($('#id_cliente_destino').val() === null))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Necesita indicar al menos una fuente de destino.</li>';}

	if(($('#temporicidad').val() == 162 )&&($('#id_operador_unidad').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar al operador que cubrirá el apartado</li>';}

	if(($('#cat_tiposervicio').val() == 163 )&&($('#mensajes').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar la descripción de mensajes que se enviarán o bien seleccionar la opción "otro" de la lista.</li>';}

	if(($('#cat_tiposervicio').val() == 164 )&&($('#paquetes').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar la descripción de paquetes que se enviarán o bien seleccionar la opción "otro" de la lista.</li>';}

	if($('#exist_tarifa').val() == 0)
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>El cliente seleccionado no tiene tarifas activas para procesar el viaje</li>';}

	if( !msj_error == "" ){
		alerta('Alerta','<ul class="list-unstyled spaced">'+msj_error+'</ul>');
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operacion/procesar_servicio',
			type: 'POST',
			data: $("#nuevo_servicio").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					carga_archivo('contenedor_principal', url_app + 'operacion/solicitud');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-41');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-42');}
		});
	} );

}
function mapCoordSelect_origen(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/mapCoordSelect_origen/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
						$( "iframe[name='gm-master']" ).remove();
						$( ".pac-container" ).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-43');}
		});
	} );
}
function mapCoordSelect_destino(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/mapCoordSelect_destino/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
						$( "iframe[name='gm-master']" ).remove();
						$( ".pac-container" ).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-44');}
		});
	} );
}
function iframeSetReference(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/iframeSetReference/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-45');}
		});
	} );
}
function iframeSetReferenceD(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/iframeSetReferenceD/',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-46');}
		});
	} );
}
function cleanOrigen(){
	$('#origen_referencia').val('');
	$('#geocodificacion_inversa_origen').val('');
	$('#geocoordenadas_origen').val('');
	$('#origen_calle').val('');
	$('#origen_num_ext').val('');
	$('#origen_num_int').val('');
	$('#origen_telefono').val('');
	$('#origen_celular').val('');
	$('#origen_hide_ok').addClass('hide');
}
function cleanDestino(){
	$('#destino_referencia').val('');
	$('#geocodificacion_inversa_destino').val('');
	$('#geocoordenadas_destino').val('');
	$('#destino_calle').val('');
	$('#destino_num_ext').val('');
	$('#destino_num_int').val('');
	$('#destino_telefono').val('');
	$('#destino_celular').val('');
	$('#destino_hide_ok').addClass('hide');
}
function cleanSavedOrigen(){
	$("#id_cliente_origen").val('');
}
function cleanSavedDestino(){
	$("#id_cliente_destino").val('');
}
function pop_alaire(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/getTBUnits',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-47');}
		});
	} );
}
function asignarDirecto(id_operador_unidad){
	$('#id_operador_unidad').val(id_operador_unidad);
	$('#pulledApart').modal('hide');
	$('#myModal').modal('hide');
}
function verifySalida(){
	var type = $('#cat_tipo_salida').val();
	if(type == 182){
		$('#select_fs').removeClass('hide');
	}else if(type == 181){
		$('#select_fs').addClass('hide');
		pop_alaire();
	}else{
		$('#select_fs').addClass('hide');
	}
}
function pop_apartado(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/pulledApart',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-48');}
		});
	} );
}
function pop_mensajeria(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/mensajeriaSettings',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-49');}
		});
	} );
}
function pop_paqueteria(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/paqueteriaSettings',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-50');}
		});
	} );
}
function setIdens(numero_economico,id_operador,nombre){
	$('#numero_economico').val(numero_economico);
	$('#id_operador').val(id_operador);
	$('#dta_opt').html(numero_economico + '::' + nombre);
}
function apartadoActive(){
	var statApart = $('input[name="tipo_temporicidad"]:checked').val();
	var time = new Date();
	if(statApart == 1){
		pop_apartado();
		time.setSeconds(3600);
		$('#fecha_hora').val(time.toMysqlFormat());

		$("#cat_tipo_salida option[value='180']").prop('selected', true);
		$("#cat_tipo_salida option[value='180']").removeAttr('disabled');
		$("#cat_tipo_salida option[value='181']").attr('disabled','disabled');
		$("#cat_tipo_salida option[value='182']").attr('disabled','disabled');
		$('#temporicidad').val('162');
	}else{
		$("#cat_tipo_salida option[value='180']").prop('selected', false);
		habilitar();
		$('#temporicidad').val('184');
		$('#fecha_hora').val(time.toMysqlFormat());
		$('#dta_opt').html('Opciones');
	}
}
function habilitar(){
	$("#cat_tipo_salida option[value='180']").removeAttr('disabled');
	$("#cat_tipo_salida option[value='181']").removeAttr('disabled');
	$("#cat_tipo_salida option[value='182']").removeAttr('disabled');
}
function desApartar(){
	$("#tipo_temporicidad").prop("checked", "");
	$("#tipo_temporicidad").removeAttr('disabled');
	$('#temporicidad').val('184');
}
function Apartar(){
	$("#cat_tipo_salida option[value='180']").prop('selected', true);
	$("#cat_tipo_salida option[value='180']").removeAttr('disabled');
	$("#cat_tipo_salida option[value='181']").attr('disabled','disabled');
	$("#cat_tipo_salida option[value='182']").attr('disabled','disabled');
	$("#tipo_temporicidad").prop("checked", "checked");
	$("#tipo_temporicidad").attr('disabled','disabled');
	$('#temporicidad').val('162');
}
function verifyServicio(){
	var type = $('#cat_tiposervicio').val();
	if(type == 163){/*mensajeria*/
		pop_mensajeria();
		$("#observaciones").attr('placeholder','Dimensiones, Contenido, Peso, Cantidad de bultos, Indicaciones, Cuidados.');
		desApartar();
	}else if(type == 164){/*paqueteria*/
		pop_paqueteria();
		$("#observaciones").attr('placeholder','Dimensiones, Contenido, Peso, Cantidad de bultos, Indicaciones, Cuidados.');
		desApartar();
	}else if(type == 166){/*recepcion*/
		pop_apartado();
		Apartar();
	}else{
		habilitar();
		$("#cat_tipo_salida option[value='180']").prop('selected', false);
		$("#cat_tipo_salida option[value='181']").prop('selected', false);
		$("#cat_tipo_salida option[value='182']").prop('selected', false);
		desApartar();
	}
}
function msgPaqArray(){
	var checkPaq = '';
	$('input[name="msgPaq[]"]:checked').each(function(){
		checkPaq += $(this).val() + '|';
	});
	$('#msgPaqArray').val(checkPaq.substring(0, checkPaq.length-1));
}
function set_status_viaje(id_viaje,stat,origen){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/set_status_viaje/' + id_viaje + '/' + stat + '/' + origen,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-51');}
		});
	} );
}
function setear_status_viaje(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/setear_status_viaje',
			type: 'POST',
			data: $("#setear_status_viaje").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#tabla_pendientes').DataTable().ajax.reload();
				}else{
						  if(resp_success['qrymissing'] == 'cat_cancelaciones'){
						alerta('Alerta!','Se requiere que seleccione un motivo de cancelación');
					}else if(resp_success['qrymissing'] == 'status_operador'){
						alerta('Alerta!','Indique el estado del operador');
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-52');
					}
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-53');}
		});
	} );
}
function viajeAlAire(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/viajeAlAire/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-54');}
		});
	} );
}
function asignarViajeAlAire(id_operador_unidad,id_operador,id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/asignarViajeAlAire/' + id_operador_unidad + '/' + id_operador + '/' + id_viaje,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-55');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-56');}
		});
	} );
}

function activar_cancelacion(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_cancelacion/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-57');}
		});
	} );
}
function activar_abandono(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_abandono/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-58');}
		});
	} );
}
function costos_adicionales(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/costos_adicionales/' + id_viaje,
			dataType: 'html',
			success: function(resp_success){
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){

					$( "#add" ).click(function() {
						$("#add_field").css({ display: "" });
						$("#footer_main").css({ display: "none" });
					});
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-59');}
		});
	} );
}
function cambiar_tarifa(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/cambiar_tarifa/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-60');}
		});
	} );
}

function activar_cancelacion_do(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_cancelacion_do/'+ id_viaje,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-61');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-62');}
		});
	} );
}
function activar_abandono_do(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_abandono_do/'+ id_viaje,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-63');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-64');}
		});
	} );
}
function costos_adicionales_do(){
	var msj_error="";
	if( $('#cat_concepto').get(0).value == "" )	msj_error+='Seleccione el concepto del costo adicional.<br />';
	if( $('#costo').get(0).value == "" )		msj_error+='Ingrese el costo adicional.<br />';
	if( $('#id_viaje').get(0).value == "" )	msj_error+='Falta id_viaje, no deberia ocurrir esto.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'operacion/costos_adicionales_do',
			type: 'POST',
			data: $("#costos_adicionales").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#costosAdicionales').DataTable().ajax.reload();
					$('#cat_concepto').val('');
					$('#costo').val('');
					//$('#myModal').modal('hide');
				}else{
					 alerta('Alerta!','Error de conectividad de red OPRN-85');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-86');}
		});
	} );
}
function cambiar_tarifa_do(id_tarifa_cliente,id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/cambiar_tarifa_do/' + id_tarifa_cliente + '/' + id_viaje,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('a[id ^= fare_]').html('<i class="fa fa-square-o bigger-150"  aria-hidden="true"></i>');
					$('#fare_'+id_tarifa_cliente).html('<i class="fa fa-check-square-o bigger-150 green" aria-hidden="true"></i>');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-67');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-68');}
		});
	} );
}
function close_costos_form(){
	$("#add_field").css({ display: "none" });
	$("#footer_main").css({ display: "" });
}
function eliminar_costoAdicional(id_costos_adicionales){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/eliminar_costoAdicional/' + id_costos_adicionales,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#costosAdicionales').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-69');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-70');}
		});
	} );
}

function cancel_apartado(id_viaje,origen){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/cancel_apartado/' + id_viaje + '/' + origen,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-71');}
		});
	} );
}
function cancel_apartado_set(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/cancel_apartado_set',
			type: 'POST',
			data: $("#cancel_apartado_set").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					carga_archivo('contenedor_principal',url_app + 'operacion/programados');
				}else{
						  if(resp_success['qrymissing'] == 'cat_cancelaciones'){
						alerta('Alerta!','Se requiere que seleccione un motivo de cancelación');
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-72');
					}
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-73');}
		});
	} );
}

function apartado2pendientes(id_viaje,origen){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/apartado2pendientes/' + id_viaje + '/' + origen,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-74');}
		});
	} );
}
function apartado2pendientesDo(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/apartado2pendientesDo',
			type: 'POST',
			data: $("#apartado2pendientesDo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					carga_archivo('contenedor_principal',url_app + 'operacion/programados');
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-75');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-76');}
		});
	} );
}



function apartadoAlAire(id_viaje,origen){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/apartadoAlAire/' + id_viaje + '/' + origen,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-77');}
		});
	} );
}
function asignarApartadoAlAire(id_operador_unidad,id_operador,id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/asignarApartadoAlAire/' + id_operador_unidad + '/' + id_operador + '/' + id_viaje,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#rojo').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-78');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-79');}
		});
	} );
}


function procesarNormal(id_viaje,origen){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/procesarNormal/' + id_viaje + '/' + origen,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-80');}
		});
	} );
}
function procesarNormalDo(){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/procesarNormalDo',
			type: 'POST',
			data: $("#procesarNormalDo").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					carga_archivo('contenedor_principal',url_app + 'operacion/programados');
				}else{
						  if(resp_success['qrymissing'] == 'cat_cancelaciones'){
						alerta('Alerta!','Se requiere que seleccione un motivo de cancelación');
					}else if(resp_success['qrymissing'] == 'status_operador'){
						alerta('Alerta!','Indique el estado del operador');
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-81');
					}
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-82');}
		});
	} );
}


var notif;
$(function() {
	notif = new buzz.sound( "dist/audio/notif_loop", {
		formats: ['mp3']
	}).setVolume(0).play();
});

$("body").on("click", ".notif-off", function() {
	notif.pause().fadeOut(3000);
});

function notifyRender(data) {
	var resp_success = JSON.parse(data);
	$('#notificaciones_body').html('');
	$('.badge-success').html('');
	$('#notificaciones_count').html('');
	if(resp_success.length > 0){
		if(resp_success[0]['total'] > 0){
			notif.setVolume(100).loop().play();
			$.each(resp_success, function( key, value ) {
				$('.badge-success').html(value['total']);
				$('#notificaciones_count').html(value['total']);

				var time = new Date();

				if(value['fecha'] > time.toMysqlFormat()){
					var classe = 'num_notif';
				}else{
					var classe = 'num_notif_red';
				}
				var salida = new Date(value['fecha']);
				var options = {
					weekday: "long",  hour: "numeric", minute: "numeric"
				};
				var print_date = salida.toLocaleDateString("es-mx", options)
				$('#notificaciones_body').append('<li>'+
					'<a onclick="carga_archivo(\'contenedor_principal\',\'operacion/programados\');" class="clearfix">'+
					'<span class="'+classe+'">'+value['id_viaje']+'</span>'+
						'<span class="msg-body" style="margin-left: 50px">'+
							'<span class="msg-title">'+
								'<span class="blue">'+value['empresa']+': </span>'+
								' '+value['cliente']+
							'</span>'+
							'<span class="msg-title">'+
								'<span class="blue">Operador: </span>'+
								' '+value['numq']+
							'</span>'+
							'<span class="msg-time">'+
								'<i class="ace-icon fa fa-clock-o"></i>'+
								'<span>  '+print_date+'</span>'+
							'</span>'+
						'</span>'+
					'</a>'+
				'</li>');


			});
		}
	}
}
function dataViaje(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/dataViaje/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-83');}
		});
	} );
}
function elegirVehiculo(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/elegirVehiculo/' + id_operador,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						$('#pulledApart').modal('hide');
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-84');}
		});
	} );
}
$("body").on("click", ".add_user_form", function() {
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/addClienteUsuario',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-85');}
		});
	} );
});

$("body").on("click", ".add_user_do", function() {
	var msj_error="";
	if( $('#cat_tipocliente').get(0).value == "" ) 	msj_error+='Seleccione el tipo de cliente.<br />';
	if( $('#id_rol').get(0).value == "" )			msj_error+='Seleccione el rol del cliente.<br />';
	if( $('#cat_statuscliente').get(0).value == "") msj_error+='Seleccione el status del cliente.<br />';
	if( $('#nombre').get(0).value == "" )			msj_error+='Ingrese un nombre para el cliente.<br />';
	if( $('#padre').get(0).value == "" )			msj_error+='Seleccione el nombre de la empresa desde el combo.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	$.ajax({
		url: 'operacion/add_user_client',
		type: 'POST',
		data: $("#nuevo_cliente_usr").serialize(),
		dataType: 'html',
		success: function(resp_success){
			$('#myModal').modal('hide');
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-86');}
	});
});

function modal_activar_c1(id_operador,num){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_c1/' + id_operador + '/' + num,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-87');}
		});
	} );
}

function activar_c1_do(num,id_operador,id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_c1/' + id_operador_unidad + '/' + id_operador + '/' + num,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#inactivos').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-88');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-89');}
		});
	} );
}

function modal_activar_f6(id_operador,num){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f6/' + id_operador + '/' + num,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-90');}
		});
	} );
}

function activar_f6_do(num,id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_f6/' + id_operador + '/' + num,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#inactivos').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-91');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-92');}
		});
	} );
}
function modal_desactivar_f06(id_operador,num){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_desactivar_f06/' + id_operador + '/' + num,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-93');}
		});
	} );
}

function desactivar_f06_do(num,id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/desactivar_f06_do/' + id_operador + '/' + num,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#suspendidas').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-91');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-94');}
		});
	} );
}
$("body").on("click", "#fillcordon", function() {
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/intoCordon',
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-95');}
		});
	} );
});
function meteralCordon(id_operador_unidad,id_episodio,base,statuscordon){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/meteralCordon/' + id_episodio + '/' + id_operador_unidad + '/' + base + '/' + statuscordon,
			dataType: 'json',
				success: function(resp_success){
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon_kpmg').DataTable().ajax.reload();
					}else{
						alerta('Alerta!','Error de conectividad de red OPRN-96');
					}
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-97');}
		});
	} );
}
function selectClave(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/selectClave/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-98');}
		});
	} );
}
function setClaveNum(clave,id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/setClaveNum/' + id_viaje + '/' + clave,
			dataType: 'html',
				success: function(resp_success){
					$('#myModal').modal('hide');
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-99');}
		});
	} );
}
function setClaveOk(id_viaje,clave){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/setClaveOk/' + id_viaje + '/' + clave,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#myModal2').modal('hide');
					$('#tabla_asignados').DataTable().ajax.reload();
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-100');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-100');}
		});
	} );
}
function historia_viaje(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/historia_viaje/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPER-101');}
		});
	} );
}
function modificar_destino(id_viaje){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modificar_destino/' + id_viaje,
			dataType: 'html',
				success: function(resp_success){
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-102');}
		});
	} );
}
