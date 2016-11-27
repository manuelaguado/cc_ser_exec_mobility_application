/*GPS*/
function startGps() {
	storeGps(function(){
		var active = dataBase.result;
		var data = active.transaction(["gps"], "readonly");
		var object = data.objectStore("gps");
		var elements = [];
		object.openCursor().onsuccess = function (e) {
			var cursor = e.target.result;
			if (cursor === null) {
				return;
			}
			if (cursor && elements.length < 20) {
				cursor.value['id_operador'] = id_operador;
				cursor.value['proceso'] = 'gps';
				elements.push(cursor.value);
				cursor.continue();
			}
		};
		data.oncomplete = function () {
			var sendvar = JSON.stringify(elements);
			if(jQuery.isPlainObject(elements[0])){
				$.ajax({
					url: 'mobile/gps',
					type: "POST",
					dataType: 'json',
					data: 'gps='+sendvar,
					/*No se verifica el envio para ahorrar datos*/
					success: function(resp_success){
						if (resp_success['gps'] == 'ok') {
							var gpsSent = JSON.parse(sendvar);
							$.each(gpsSent, function(k,v){
								eliminarClaveByToken(v.token,'gps');
							});
						}
					},
				});
			}
		};
		last_lat = lat;
		last_lon = lon;
	});
}
/*SINCRONIZACION*/
function startSync(exec){
	var active = dataBase.result;
	var data = active.transaction(["claves"], "readonly");
	var object = data.objectStore("claves");
	var elements = [];	
	object.openCursor().onsuccess = function (e) {
		var result = e.target.result;
		if (result === null) {
			return;
		}
		result.value['latitud_act'] = lat;
		result.value['longitud_act'] = lon;
		result.value['proceso'] = 'sync';
		elements.push(result.value);
		result.continue();
	};
	data.oncomplete = function () {
		var sendvar = JSON.stringify(elements);
		if(jQuery.isPlainObject(elements[0])){
			//console.log('SYNC SEND');
			$.ajax({
				url: 'mobile/sync',
				type: "POST",
				dataType: 'json',
				data: 'sync='+sendvar,
				success: function(e){
					exec();
				}
			});
		}else{
			exec();
		}
	};
}

/*SUSCRIPCIONES*/
function sync_ok(data) {
	var resp_success = JSON.parse(data);
		$.each(resp_success, function(k,v){
			if(v.resp == true){
				eliminarClaveByToken(v.token,'claves');
			}
		});
		switch (resp_success[0]['clave']) {
			case 'C1':
				storeTravel(resp_success[0]);
				break;
		}
}

function gps_ok(data) {
	var resp_success = JSON.parse(data);
		$.each(resp_success, function(k,v){
			if(v.resp == true){
				eliminarClaveByToken(v.token,'gps');
			}
		});
}
function broadcastPlay(data){
	var resp_success = JSON.parse(data);
	timbre.play();
	myApp.alert(resp_success['mensaje'], 'Notificación general',function(){
		timbre.pause();
	});
}

function ride_ok(data) {
	var resp_success = JSON.parse(data);
	switch (resp_success['clave']) {
		///////////////////////////////////////////////////////////////////////////// sincronizar estados de pantalla
		case 'R1':
			myApp.alert('Se sincronizará el estado de su movil con la central ', 'Atención',function(){
				if(resp_success['set_page'] == 'inicio'){
					setStoreVariable('base','',function(){});
					$('#data_cordon').html('NO HAY DATOS DE CORDON');
					finalizar_servicio();
				}else if(resp_success['set_page'] == 'regreso'){
					storeClave('C1','C1','F11','NULL','NULL','NULL',function(){
						loadTemplateWOR1('regreso');
					});
				}else{
					loadTemplate(resp_success['set_page']);
				}
			});
			//console.log('RIDE R1 OK');
			break;
		///////////////////////////////////////////////////////////////////////////// viaje por cordon
		case 'R11':
		case 'A10':
			if (resp_success['new'] == true){
				$$('#data_viaje').html('');
				$('#data_cordon').html('NO HAY DATOS DE CORDON');
				storeTravel(resp_success);
				$("#ride_false").hide();
				$("#ride_true").show();
				updatePageButtons('ride_false','ride_true');
				$("#exit_true").hide();
				$("#exit_false").show();
				updatePageButtons('exit_true','exit_false');
				myApp.alert('Vea los detalles de su destino en el menú', 'Nuevo destino');
				
				$.each(resp_success['viaje'], function( key, value ) {
					if(value != ''){
						switch (key) {
							case 'Coordenadas origen':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							case 'Coordenadas destino':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							default:
								$$('#data_viaje').append( '<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner">'+value+'</div></div></div>' );
						}
					}
				});
				
				getBase(function () {	
					storeClave('R5','C1',globalBase,'NULL','NULL','ACUSE DE RECEPCION DE A10',function(){});
				});
				
			}else{
				$("#ride_true").hide();
				$("#ride_false").show();
				updatePageButtons('ride_true','ride_false');
			}
			
			//console.log('RIDE A10 OK');
			break;
			
		//////////////////////////////////////////////////////////////////////////////////////////////////// viaje on air
		case 'F15':
			if (resp_success['new'] == true){
				$$('#data_viaje').html('');
				$('#data_cordon').html('NO HAY DATOS DE CORDON');
				storeTravel(resp_success);
				$("#air_service_des").hide();
				$("#air_service_act").show();
				updatePageButtons('air_service_des','air_service_act');
				myApp.alert('Vea los detalles de su destino en el menú', 'Servicio al aire');
				
				$.each(resp_success['viaje'], function( key, value ) {
					if(value != ''){
						switch (key) {
							case 'Coordenadas origen':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							case 'Coordenadas destino':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							default:
								$$('#data_viaje').append( '<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner">'+value+'</div></div></div>' );
						}
					}	
				});				
				storeClave('R6','C1','NULL','NULL','NULL','ACUSE DE RECEPCION DE F15',function(){});
			}else{
				$("#air_service_act").hide();
				$("#air_service_des").show();
				updatePageButtons('air_service_act','air_service_des');
			}
			
			//console.log('RIDE F15 OK');
			break;
			
		////////////////////////////////////////////////////////////////////////////////////////////////// salida por sitio
		case 'F13':
			if (resp_success['new'] == true){
				$('#data_cordon').html('NO HAY DATOS DE CORDON');
				storeTravel(resp_success);
				$("#salida_sitio_des").hide();
				$("#salida_sitio_act").show();
				updatePageButtons('salida_sitio_des','salida_sitio_act');
				myApp.alert('Se autorizó la salida por sitio', 'Salida por sitio');
				
				$.each(resp_success['viaje'], function( key, value ) {
					if(value != ''){
						switch (key) {
							case 'Coordenadas origen':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							case 'Coordenadas destino':
								$$('#data_viaje').append('<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner"><a href="https://www.google.com.mx/maps/place/@'+value+',19z/data=!3m1!4b1!4m5!3m4!1s0x0:0x0!8m2!3d'+value+'">'+value+'</a></div></div></div>');
								break;
							default:
								$$('#data_viaje').append( '<div class="card"><div class="card-header" style="color:#000000;">'+key+'</div><div class="card-content"><div class="card-content-inner">'+value+'</div></div></div>' );
						}
					}	
				});					
				
				getBase(function () {	
					storeClave('R7','C1',globalBase,'NULL','NULL','ACUSE DE RECEPCION DE F13',function(){});
				});
			}else{
				$("#salida_sitio_act").hide();
				$("#salida_sitio_des").show();
				updatePageButtons('salida_sitio_act','salida_sitio_des');
			}
			
			//console.log('RIDE F13 OK');
			break;
		
		/////////////////////////////////////////////////////////////////////////////////////////////////// formacion en el cordon
		case 'F14':
			if(resp_success['queue'] == true){
				
				$('#update_cordon').css('display','none');
				
				myApp.confirm(
					'Turno: ' + resp_success['turno'] + 
					'<br><br>Verifique el cordón en el menú para conocer el estado.',
					'Asignado al cordón',
				function () {
					loadTemplate('base');
					setStoreVariable('base',resp_success['base'],function(){});
				});
				
				
				$$('#data_cordon').html('');
				$$('#data_viaje').html('');
				$.each(resp_success['cordon'], function( key, value ) {
					suma = parseInt(key) + parseInt(1);
				  $$('#data_cordon').append('<div class="user-tail"><span id="ava-tail" class="circle_back"><img class="avatar_tail" src="'+url_app+'fw7/assets/img/driver-black.svg" alt="Operador"><div class="cordon_name">'+ suma +'.-'+value+'</div></span></div>');
				});
				
				
				storeClave('R8','C1','NULL','NULL','NULL','ACUSE DE RECEPCION DE F14',function(){});
			
			}else if(resp_success['queue'] == false){
				
				$('#update_cordon').css('display','');
				var data_ind = JSON.parse(JSON.stringify(resp_success['indicadores']));
				var verify = myApp.indicadores(data_ind);
				$$('#data_cordon').html(verify);
				
			}	
			//console.log('RIDE F14 OK');
			break;								
		
		/////////////////////////////////////////////////////////////////////////////////////////////////////// salida por sitio
		case 'F19':
			if (resp_success['new'] == false){
				$$('#data_cordon').html('');
				$.each(resp_success['cordon'], function( key, value ) {
					suma = parseInt(key) + parseInt(1);
				  $$('#data_cordon').append('<div class="user-tail"><span id="ava-tail" class="circle_back"><img class="avatar_tail" src="'+url_app+'fw7/assets/img/driver-black.svg" alt="Operador"><div class="cordon_name">'+ suma +'.-'+value+'</div></span></div>');
				});
			}
			if(resp_success['turno'] <= 3){
				$("#exit_true").hide();
				$("#exit_false").show();
				updatePageButtons('exit_true','exit_false');
			}else{
				$("#exit_true").show();
				$("#exit_false").hide();
				updatePageButtons('exit_false','exit_true');
			}
			
			storeClave('R2','C1','NULL','NULL','NULL','ACUSE CORDON',function(){});
			
			//console.log('RIDE F19 OK');
			break;
		///////////////////////////////////////////////////////////////////////////////////////////////////////// estado normalizado
		case 'F20':
				$("#exit_true").show();
				$("#exit_false").hide();
				updatePageButtons('exit_false','exit_true');
				//console.log('RIDE F20 OK');
			break;
		///////////////////////////////////////////////////////////////////////////////////////////////////// modificar modo de viaje
		case 'F16':
			if (resp_success['new'] == true){
				$('#data_cordon').html('NO HAY DATOS DE CORDON');
				storeTravel(resp_success);
				$("#mod_viaje_des").hide();
				$("#mod_viaje_act").show();
				updatePageButtons('mod_viaje_des','mod_viaje_act');
				myApp.alert('Se autorizó la modificación de su viaje', 'Viaje modificado');
			}else{
				$("#mod_viaje_act").hide();
				$("#mod_viaje_des").show();
				updatePageButtons('mod_viaje_act','mod_viaje_des');
			}
			storeClave('R9','C1','NULL','NULL','NULL','ACUSE DE RECEPCION DE F16',function(){});
			//console.log('RIDE F16 OK');
			break;
		////////////////////////////////////////////////////////////////////////////////////////////////////////// inicio de labores
		case 'C1':
			storeTravel(resp_success);
			storeClave('R10','C1','F11','NULL','NULL','ACUSE DE RECEPCION DE C1',function(){});
			//console.log('RIDE C1 OK');
			break;
		case 'MSG':
			storeClave('F17','C1',''+resp_success['id_mensaje']+'','NULL','NULL','Mensaje Leido',function(){
				timbre.play();
				myApp.alert(resp_success['mensaje'], 'Mensaje',function(){
					timbre.pause();
				});
			});
			//console.log('RIDE MSG OK');
			break;
			
		case 'R3':
			dOut();
			break;
		case 'R4':
			dOut();
			break;
	}
}
	
/*INTERVALS*/
var initSync = setInterval("startSync(function(){})",1000);
var initGps  = setInterval("startGps()",5000);