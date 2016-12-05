function modal_nuevo_cliente(){
	$(document).ready(function() {
		$.ajax({
			url: 'clientes/modal_add_cliente',
			dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-01');}	
		});
	} );
}
function add_client(){
	var msj_error="";
	if( $('#cat_tipocliente').get(0).value == "" ) 	msj_error+='Seleccione el tipo de cliente.<br />';
	if( $('#id_rol').get(0).value == "" )			msj_error+='Seleccione el rol del cliente.<br />';
	if( $('#cat_statuscliente').get(0).value == "") msj_error+='Seleccione el status del cliente.<br />';
	if( $('#nombre').get(0).value == "" )			msj_error+='Ingrese un nombre para el cliente.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'clientes/add_client',
			type: 'POST',
			data: $("#nuevo_cliente").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#clientes').DataTable().ajax.reload();
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-02');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-03');}	
		});
	} );
}
function chk_client_root(){
	if($("#raiz").is(':checked')) {  
		$("#parent").attr('disabled','disabled');
		$("#parent").val("");
	} else {  
		$("#parent").removeAttr('disabled');
	} 
}
function agregar_cliente_tree(){
	var msj_error="";
	if( $('#cat_tipocliente').get(0).value == "" ) 	msj_error+='Seleccione el tipo de cliente.<br />';
	if( $('#id_rol').get(0).value == "" )			msj_error+='Seleccione el rol del cliente.<br />';
	if( $('#cat_statuscliente').get(0).value == "") msj_error+='Seleccione el status del cliente.<br />';
	if( $('#nombre').get(0).value == "" )			msj_error+='Ingrese un nombre para el cliente.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	$.ajax({
		url: 'clientes/add_client_children',
		type: 'POST',
		data: $("#cliente_nuevo").serialize(),
		dataType: 'html',
		success: function(resp_success){
			$('#ruta_ensamble').append(resp_success);
			$('#cat_tipocliente').get(0).value = "";
			$('#id_rol').get(0).value = "";
			$('#cat_statuscliente').get(0).value = "";
			$('#nombre').get(0).value = "";
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-04');}	
	});
}
function getFormClientEdit(id_client,parent){
	$.ajax({
		url: url_app + 'clientes/getFormClientEdit/' + id_client + '/' + parent,
		dataType: 'html',
		success: function(resp_success){
			$('#cliente_nuevo').html(resp_success);
		},
		error: function(respuesta){alerta('Alerta!','Error de conectividad de red CLI-05');}	
	});
}
function return_form_add(parent){
	$.ajax({
		url: url_app + 'clientes/return_form_add/' + parent,
		dataType: 'html',
		success: function(resp_success){
			$('#cliente_nuevo').html(resp_success);
		},
		error: function(respuesta){alerta('Alerta!','Error de conectividad de red CLI-06');}	
	});
}
function editar_cliente_tree(id_cliente,parent){
	var msj_error="";
	if( $('#cat_tipocliente').get(0).value == "" ) 	msj_error+='Seleccione el tipo de cliente.<br />';
	if( $('#id_rol').get(0).value == "" )			msj_error+='Seleccione el rol del cliente.<br />';
	if( $('#cat_statuscliente').get(0).value == "") msj_error+='Seleccione el status del cliente.<br />';
	if( $('#nombre').get(0).value == "" )			msj_error+='Ingrese un nombre para el cliente.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	if( $('#cat_statuscliente').get(0).value == "24"){
		deleteClient(id_cliente,parent);
		return false;
	}
	$.ajax({
		beforeSend: function(xhr){
			$( "#spinner_edit" ).removeClass("hidden");
		},			
		url: 'clientes/edit_client_children',
		type: 'POST',
		data: $("#cliente_nuevo").serialize(),
		dataType: 'html',
		success: function(resp_success){
			$('#nombre_nestable_' + id_cliente).html(resp_success);
			$( "#spinner_edit" ).addClass("hidden");
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-07');}	
	});
}
function guardar_disposicion(){
	$.ajax({
		beforeSend: function(xhr){
			$( "#spinner_change" ).removeClass("hidden");
		},		
		url: 'clientes/store_order',
		type: 'POST',
		data: $("#new_order").serialize(),
		dataType: 'html',
		success: function(resp_success){
			$( "#spinner_change" ).addClass("hidden");
		},
		error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-08');}	
	});
}
function deleteClient(id_cliente,padre){
	$.ajax({
		beforeSend: function(xhr){
			$( "#spinnerClient_" + id_cliente ).removeClass("hidden");
		},		
		url: 'clientes/deleteClient/' + id_cliente + '/' + padre,
		type: 'POST',
		dataType: 'json',
		success: function(resp_success){
			if (resp_success['resp'] == true) {
				$( "#spinnerClient_" + id_cliente ).addClass("hidden");
				if (resp_success['dependientes'] > 0) {
					alerta('Dependientes','Verifique que el usuario que desea eliminar no tenga dependientes');
				}else{
					$('#dataClientNestable_' + id_cliente).remove();
					
					if (resp_success['hermanos'] == 0) {
						$('#dataClientNestable_' + padre).children('button').css("display","none");
					}
					$('#cat_tipocliente').get(0).value = "";
					$('#id_rol').get(0).value = "";
					$('#cat_statuscliente').get(0).value = "";
					$('#nombre').get(0).value = "";					
					alerta('Eliminado','Se ha eliminado al usuario');
				}
			}else{
				$( "#spinnerClient_" + id_cliente ).addClass("hidden");
				alerta('Alerta!','Error de conectividad de red CLI-09');
			}
		},
		error: function(respuesta){
			$( "#spinnerClient_" + id_cliente ).addClass("hidden");
			alerta('Alerta!','Error de conectividad de red CLI-10');
		}
	});
}
function mostrarRfc(){
	if(document.getElementById("fiscal").checked == true){
		$('#fieldRfc').removeClass('hide');
	}else if(document.getElementById("fiscal").checked == false){
		$('#fieldRfc').addClass('hide');
	}
}
function verificarRfc(){
	if( $('#rfc').get(0).value != "" ) {
		if ($('#rfc').get(0).value.match(/^([A-z&Ññ]{3}|[A-z][AEIOUaeiou][A-z]{2})\d{2}((01|03|05|07|08|10|12)(0[1-9]|[12]\d|3[01])|02(0[1-9]|[12]\d)|(04|06|09|11)(0[1-9]|[12]\d|30))([A-z0-9]{2}[0-9a])?$/)) {
			/**/
		}else{
			alerta('Alerta!','El RFC no tiene el formato correcto o no es un RFC válido');
		}
	}
}
function verificarTel(){
	if( $('#telefono').get(0).value != "" ) {
		if ($('#telefono').get(0).value.match(/^[0-9]{10}/)) {
			/**/
		}else{
			alerta('Alerta!','El Télefono no tiene el formato correcto o no es un telefono válido');
		}
	}
}
function verificarCel(){
	if( $('#celular').get(0).value != "" ) {
		if ($('#celular').get(0).value.match(/^[0-9]{10}$/)) {
			/**/
		}else{
			alerta('Alerta!','El Celular no tiene el formato correcto o no es un celular válido');
		}
	}
}
function verificarMail(){
	if( $('#correo').get(0).value != "" ) {
		if ($('#correo').get(0).value.match(/^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*$/)) {
			/**/
		}else{
			alerta('Alerta!','El correo no es una direccion de correo válida');
		}
	}
}
function guardarUbicacion(id_cliente){
	var msj_error="";
	if( $('#calle').get(0).value == "" ) msj_error+='El campo calle es indispensable para guardar.<br />';
	if( $('#num_ext').get(0).value == "" )	msj_error+='El campo exterior es indispensable para guardar.<br />';
	if( $('#id_asentamiento').get(0).value == "") msj_error+='Complete el formulario para definir el asentamiento antes de guardar.<br />';
	if(document.getElementById("fiscal").checked == true){
		if( $('#rfc').get(0).value == "" ) { 
			msj_error+='El campo RFC es indispensable para guardar en una direccion fiscal.<br />';
		}else{
			if ($('#rfc').get(0).value.match(/^([A-z&Ññ]{3}|[A-z][AEIOUaeiou][A-z]{2})\d{2}((01|03|05|07|08|10|12)(0[1-9]|[12]\d|3[01])|02(0[1-9]|[12]\d)|(04|06|09|11)(0[1-9]|[12]\d|30))([A-z0-9]{2}[0-9a])?$/)) {
				/**/
			}else{
				msj_error+='El RFC no tiene el formato correcto o no es un RFC válido.<br />';
			}
		}
	}
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}
	if( $('#telefono').get(0).value != "" ){verificarTel();}
	if( $('#celular').get(0).value != "" ){verificarCel();}
	if( $('#correo').get(0).value != "" ){verificarMail();}
	
	$(document).ready(function() {
		$.ajax({
			beforeSend: function(xhr){
				$( "#savePreload" ).removeClass("hidden");
			},
			url: 'clientes/guardarUbicacion',
			type: 'POST',
			data: $("#nueva_ubicacion").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$( "#savePreload" ).addClass("hidden");
					carga_archivo('contenedor_principal', url_app + 'clientes/ubicacion/' + id_cliente);
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-11');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-12');}	
		});
	} );
}
function predeterminar_box_adr(id_datos_fiscales,id_cliente){
	$(document).ready(function() {
		$.ajax({
			beforeSend: function(xhr){
				$( "#boxload_" + id_datos_fiscales).removeClass("hidden");
			},
			url: 'clientes/predeterminarUbicacion/' + id_datos_fiscales + '/' + id_cliente,
			type: 'POST',
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$( "#boxload_" + id_datos_fiscales).addClass("hidden");
					$( "#pred").remove();
					$( "#titulo_" + id_datos_fiscales).append(" <span id='pred'> (Predeterminada)</span>");
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-13');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-14');}	
		});
	} );
}
function eliminar_box_adr(id_datos_fiscales){
	$(document).ready(function() {
		$.ajax({
			beforeSend: function(xhr){
				$( "#boxload_" ).removeClass("hidden");
			},
			url: 'clientes/eliminarUbicacion/' + id_datos_fiscales,
			type: 'POST',
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$( "#box_address_" + id_datos_fiscales).remove();
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-15');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-16');}	
		});
	} );
}
function modal_editar_cliente(id_cliente){
	$(document).ready(function() {
		$.ajax({
			url: 'clientes/modal_editar_cliente/' + id_cliente,
			dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});
				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-17');}	
		});
	} );
}
function edit_client(){
	var msj_error="";
	if( $('#cat_tipocliente').get(0).value == "" ) 	msj_error+='Seleccione el tipo de cliente.<br />';
	if( $('#id_rol').get(0).value == "" )			msj_error+='Seleccione el rol del cliente.<br />';
	if( $('#cat_statuscliente').get(0).value == "") msj_error+='Seleccione el status del cliente.<br />';
	if( $('#nombre').get(0).value == "" )			msj_error+='Ingrese un nombre para el cliente.<br />';

	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'clientes/edit_client',
			type: 'POST',
			data: $("#editar_cliente").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#myModal').modal('hide');
					$('#clientes').DataTable().ajax.reload();
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-18');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-19');}	
		});
	} );
}

function modal_establecer_tarifa(id_cliente){
	$(document).ready(function() {
		$.ajax({
			url: 'clientes/modal_establecer_tarifa/' + id_cliente,
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
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-20');}	
		});
	} );
}


function procesar_tarifa(){
	var msj_error="";
	if( $('#nombre').get(0).value == "" ) 		msj_error+='Ingrese un nombre para identifcar la tarifa.<br />';
	if( $('#descripcion').get(0).value == "" )	msj_error+='Describa la tarifa.<br />';
	if( $('#costo_base').get(0).value == "") 	msj_error+='Ingrese el costo base de la tarifa.<br />';

	if( $('#cat_tipo_tarifa').get(0).value == "" )	msj_error+='Seleccione el tipo de tarifa.<br />';
	if( $('#tabular').get(0).value == "" )	msj_error+='Falta tabular, no deberia ocurrir esto.<br />';
	if( $('#id_cliente').get(0).value == "" )	msj_error+='Falta id_cliente, no deberia ocurrir esto.<br />';
	
	if(( $('#tabular').get(0).value == "0" )&&( $('#km_adicional').get(0).value == "" )) msj_error+='Ingrese el costo por kilómetro adicional de la tarifa.<br />';
	
	if( !msj_error == "" ){
		alerta('Alerta!',msj_error);
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'clientes/procesar_tarifa',
			type: 'POST',
			data: $("#establecer_tarifa").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					$('#tarifas').DataTable().ajax.reload();
					$('#nombre').val('');
					$('#descripcion').val('');
					$('#costo_base').val('');
					$('#cat_tipo_tarifa').val('');
					$('#tabular').val('0');
					$('#km_adicional').val('');
					$("#tabulado").prop("checked", false);
					//$('#myModal').modal('hide');					
				}else{
					 alerta('Alerta!','Error de conectividad de red CLI-21');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red CLI-22');}	
		});
	} );
}
function switch_tabular(){
	if($("#tabulado").is(':checked')) {  
		$('#tabular').get(0).value = "1";
	} else { 
		$('#tabular').get(0).value = "0";	
	} 
}
function close_tarifa_form(){
	$("#add_field").css({ display: "none" });
	$("#footer_main").css({ display: "" });
}