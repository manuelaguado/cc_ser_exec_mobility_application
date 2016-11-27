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

function activar_f15(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f15/' + id_operador_unidad,
			dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-04');}	
		});
	} );
}

function activar_f15_do(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_f15/' + id_operador_unidad,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					setTimeout(function(){ $('#tiempoalabase').DataTable().ajax.reload(); }, 5000);
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-05');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-06');}	
		});
	} );
}

function activar_f16(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_f16/' + id_operador_unidad,
			dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-07');}	
		});
	} );
}

function activar_f16_do(id_operador_unidad){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/activar_f16/' + id_operador_unidad,
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					setTimeout(function(){ $('#tiempoalabase').DataTable().ajax.reload(); }, 5000);
				}else{
					alerta('Alerta!','Error de conectividad de red OPRN-08');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red OPRN-09');}	
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
function aut_c02(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/aut_c02/' + id_operador_unidad + '/' + id_base ,
			dataType: 'json',
				success: function(resp_success){	
					if (resp_success['resp'] == true) {
						$('#myModal').modal('hide');
						$('#cordon').DataTable().ajax.reload();
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
function modal_activar_c02(id_operador_unidad,id_base){
	$(document).ready(function() {
		$.ajax({
			url: 'operacion/modal_activar_c02/' + id_operador_unidad + '/' + id_base,
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
	if( $('#id_operador').get(0).value == "" )	msj_error+='Error de conectividad de red OPRN-26';
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
						$('#cordon').DataTable().ajax.reload();
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
	
	if(( $('#id_asentamiento_origen').val() == "" )&&($('#id_cliente_origen').val() === null))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Necesita indicar al menos una fuente de origen.</li>';}

	if(( $('#id_asentamiento_destino').val() == "" )&&($('#id_cliente_destino').val() === null))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Necesita indicar al menos una fuente de destino.</li>';}
	
	if(($('#apartado').val() == 1 )&&($('#id_operador_unidad').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar al operador que cubrirá el apartado</li>';}
	
	if(($('#cat_tiposervicio').val() == 163 )&&($('#mensajes').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar la descripción de mensajes que se enviarán o bien seleccionar la opción "otro" de la lista.</li>';}
	
	if(($('#cat_tiposervicio').val() == 164 )&&($('#paquetes').val() == ""))
		{msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Indispensable seleccionar la descripción de paquetes que se enviarán o bien seleccionar la opción "otro" de la lista.</li>';}
	
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
	$('#asentamiento_origen').val('');
	$('#id_asentamiento_origen').val('');
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
	$('#asentamiento_destino').val('');
	$('#id_asentamiento_destino').val('');
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
	if(statApart == 1){
		pop_apartado();
		$("#cat_tipo_salida option[value='180']").prop('selected', true);
		$("#cat_tipo_salida option[value='180']").removeAttr('disabled');
		$("#cat_tipo_salida option[value='181']").attr('disabled','disabled');
		$("#cat_tipo_salida option[value='182']").attr('disabled','disabled');
		$('#temporicidad').val('162');
	}else{
		$("#cat_tipo_salida option[value='180']").prop('selected', false);
		habilitar();
		$('#temporicidad').val('184');
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