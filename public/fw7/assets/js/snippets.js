/*WebWorker link*/
var online;
function worker_link(time) {
	if(typeof(Worker) !== "undefined") {
		if(typeof(wlink) == "undefined") {
			wlink = new Worker(url_app + "public/fw7/assets/js/worker_link.js?time=" + time);
		}	
		wlink.onmessage = function(event) {
			var valores = JSON.parse(event.data);
			if(valores['stat'] == 'ok'){
				document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link"></span>';
				online = true;
			}
		};
		wlink.onerror = function(event){
			document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink"  style="color:red;"></span>';
			online = false;	
		};
	} else {
		/*no soportado*/
		document.getElementById("infolink").innerHTML = '<span class="kkicon icon-bug"></span>';
	}
}
function stop_worker_link() { 
	wlink.terminate();
	wlink = undefined;
}		
		
/*WebWorker RIDE*/
		function worker_ride(viaje) {
			if(typeof(Worker) !== "undefined") {
				if(typeof(wride) == "undefined") {
					wride = new Worker(url_app + "public/fw7/assets/js/worker_ride.js?viaje=" + viaje);
				}
				wride.onmessage = function(event) {
					var valores = JSON.parse(event.data);
					if(valores['new'] == true){
						storeTravel(valores);
						$("#ride_false").hide();
						$("#ride_true").show();
						//myApp.alert('Vea los detalles de su destino en el menú', 'Nuevo destino'); <--activar
					}else{
						$("#ride_true").hide();
						$("#ride_false").show();
					}
					stop_worker_ride();
				};
			} else {
				//no soportado
				document.getElementById("infolink").innerHTML = '<span class="kkicon icon-bug"></span>';
			}
		}
		function stop_worker_ride() {
			if(typeof(wride) != "undefined") {
				wride.terminate();
				wride = undefined;
			}
		}
		function start_worker_ride(link){
			if(link == true){
				var active = dataBase.result;
				var data = active.transaction(["viaje"], "readwrite");
				var object = data.objectStore("viaje");
				
				var index = object.index("by_iden");
				var request = index.get(1);
				
				request.onsuccess = function () {
					var result = request.result;
					if (result !== undefined) {
						worker_ride(result.id_viaje);
					}else{
						worker_ride(1);
					}
				};
			}
		}
var startRide = setInterval("start_worker_ride(online)",4000);

/*WebWorker SYNC*/
		function worker_sync() {
			if(typeof(Worker) !== "undefined") {
				if(typeof(wsync) == "undefined") {
					var active = dataBase.result;
					var data = active.transaction(["claves"], "readonly");
					var object = data.objectStore("claves");
					var elements = [];
					object.openCursor().onsuccess = function (e) {
						var result = e.target.result;
						if (result === null) {
							return;
						}
						elements.push(result.value);
						result.continue();
					};				
					data.oncomplete = function () {
						for (var key in elements) {
							var id 	= elements[key].id;
						}
						var sendvar = JSON.stringify(elements);
						wsync = new Worker(url_app + "public/fw7/assets/js/worker_sync.js?sendvar=" + sendvar);
						wsync.onmessage = function(event) {
							var valores = JSON.parse(event.data);
							if(valores[0]['resp'] == true){
								$.each(valores, function(k,v){
									//console.log(k+' -> ' +v.id);
									eliminarClave(v.id);
								});
							}
							stop_worker_sync();
						};
					};
				}
			} else {
				//no soportado
				document.getElementById("infolink").innerHTML = '<span class="kkicon icon-bug"></span>';
			}
		}
		function stop_worker_sync() {
			if(typeof(wsync) != "undefined") {
				wsync.terminate();
				wsync = undefined;
			}
		}
		function start_worker_sync(link){
			if(link == true){
				worker_sync();
			}
		}
var startSync = setInterval("start_worker_sync(online)",4000);		


/*worker_link.js*/

function getGET(){
   var loc = location.href;
   var getString = loc.split('?')[1];
   var GET = getString.split('&');
   var get = {};
   for(var i = 0, l = GET.length; i < l; i++){
      var tmp = GET[i].split('=');
      get[tmp[0]] = unescape(decodeURI(tmp[1]));
   }
   return get;
}
var get = getGET();
function getHTTPObject() {
	var xmlhttp=false;
	try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}
	catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}
var http = getHTTPObject();
function handleHttpResponse() {	
	if (http.readyState == 4) {
		postMessage(http.responseText);
	}
}
function getLink() {
	var url1 = "../../../../mobile/ping";
	http.open("POST", url1, true);
	http.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	http.onreadystatechange = handleHttpResponse;
	http.send();
}
function getSignal(){
	setInterval("getLink()", get['time']);
}
getSignal();


/*worker_ride*/

function getGET(){
   var loc = location.href;
   var getString = loc.split('?')[1];
   var GET = getString.split('&');
   var get = {};
   for(var i = 0, l = GET.length; i < l; i++){
      var tmp = GET[i].split('=');
      get[tmp[0]] = unescape(decodeURI(tmp[1]));
   }
   return get;
}
var get = getGET();
function getHTTPObject() {
	var xmlhttp=false;
	try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}
var http = getHTTPObject();
function handleHttpResponse() {		
	if (http.readyState == 4) {
		postMessage(http.responseText);
	}
}
function getLink() {
	var url1 = "../../../../mobile/ride/" + get['viaje'];
	http.open("POST", url1, true);
	http.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	http.onreadystatechange = handleHttpResponse;
	http.send();								
}
getLink();

/*worker_sync*/

function getGET(){
   var loc = location.href;
   var getString = loc.split('?')[1];
   var GET = getString.split('&');
   var get = {};
   for(var i = 0, l = GET.length; i < l; i++){
      var tmp = GET[i].split('=');
      get[tmp[0]] = unescape(decodeURI(tmp[1]));
   }
   return get;
}
var get = getGET();
function getHTTPObject() {
	var xmlhttp=false;
	try{xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");}
		catch(e){
		try{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(E){
			if (!xmlhttp && typeof XMLHttpRequest!='undefined') xmlhttp=new XMLHttpRequest();
		}
	}
	return xmlhttp; 
}
var http = getHTTPObject();
function handleHttpResponse() {
	if (http.readyState == 4) {
		postMessage(http.responseText);
	}
}
function getLink() {
	var par = "&sendvar=" + get['sendvar'];
	var url1 = "../../../../mobile/sync";
	http.open("POST", url1, true);
	http.setRequestHeader("Content-Type","application/x-www-form-urlencoded; charset=UTF-8");
	http.onreadystatechange = handleHttpResponse;
	http.send(par);								
}
getLink();




/********************** Funciones sin usar *************************************************/
/*CARGAR TODOS LOS DATOS*/
function loadAll() {
	/*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
	/*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readonly");
	/*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
	
	/*En esta variable vamos a ir recuperando los objetos recuperados*/
	var elements = [];
	/*Recorriendo el almacen de objetos seleccionado con data.objectStore*/
	object.openCursor().onsuccess = function (e) {
		/*Recupera un objeto*/
		var result = e.target.result;
		/*Si no es nulo se agrega a la variable elements*/
		if (result === null) {
			return;
		}
		elements.push(result.value);
		/*Esto permite seguir recorriendo el almacen de objetos*/
		result.continue();
	};
	/*Transacción satisfactoria, se presentan los resultados recorriendolos con for*/
	data.oncomplete = function () {
		
		/*
		var outerHTML = '';
		for (var key in elements) {
			outerHTML += '\n\
			<tr>\n\
				<td>' + elements[key].dni + '</td>\n\
				<td>' + elements[key].name + '</td>\n\
				<td>\n\
					<button type="button" onclick="load(' + elements[key].id + ')">Details</button>\n\
					<button type="button" onclick="loadByDni(' + elements[key].dni + ')">Details DNI</button>\n\
				</td>\n\
			</tr>';
		}
		elements = [];
		document.querySelector("#elementsList").innerHTML = outerHTML;
		*/
		
	};
}

/*CARGAR TODOS LOS DATOS ORDENADOS POR DETERMINADO INDICE*/
function loadAllByName() {
	/*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
	/*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readonly");
	/*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
	/*indicar el índice*/
	var index = object.index("by_name");
	
	/*En esta variable vamos a ir recuperando los objetos recuperados*/
	var elements = [];
	/*Recorriendo el almacen sobre la variable que incluye tanto el almacén como el índice a utilizar*/
	index.openCursor().onsuccess = function (e) {
		/*Recupera un objeto*/
		var result = e.target.result;
		/*Si no es nulo se agrega a la variable elements*/
		if (result === null) {
			return;
		}
		elements.push(result.value);
		/*Esto permite seguir recorriendo el almacen de objetos*/
		result.continue();
	};
	/*Transacción satisfactoria, se presentan los resultados recorriendolos con for*/
	data.oncomplete = function () {
		var outerHTML = '';
		for (var key in elements) {
			outerHTML += '\n\
			<tr>\n\
				<td>' + elements[key].dni + '</td>\n\
				<td>' + elements[key].name + '</td>\n\
				<td>\n\
					<button type="button" onclick="load(' + elements[key].id + ')">Details</button>\n\
					<button type="button" onclick="loadByDni(' + elements[key].dni + ')">Details DNI</button>\n\
				</td>\n\
			</tr>';
		}
		elements = [];
		document.querySelector("#elementsList").innerHTML = outerHTML;
	};
}

/*CARGAR UN SOLO OBJETO*/
function load(id) {
	/*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
	/*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readwrite");
	/*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
	
	/*Recuperando objeto*/
	var request = object.get(parseInt(id));
	
	/*Transacción satisfactoria, en este caso se presenta alert*/
	request.onsuccess = function () {
		var result = request.result;
		if (result !== undefined) {
			alert("ID: " + result.id + "\n\
				   DNI " + result.dni + "\n\
				   Name: " + result.name + "\n\
				   Surname: " + result.surname);
		}
	};
}

/*CARGAR UN OBJETO POR INDICE*/
function loadById(id) {
	/*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
	/*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readwrite");
	/*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
	
	/*indicar al objeto que contiene el almacén de datos que utiliza un índice*/
	var index = object.index("by_id");
	
	/*Recuperando objeto*/
    var request = index.get(String(id));
	
	/*Transacción satisfactoria, en este caso se presenta alert*/
    request.onsuccess = function () {
        var result = request.result;

        if (result !== undefined) {
            alert("ID: " + result.id + "\n\
                   DNI " + result.dni + "\n\
                   Name: " + result.name + "\n\
                   Surname: " + result.surname);
        }
    };
}

/*AGREGAR NUEVO REGISTRO*/
function add() {
	/*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
	/*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readwrite");
	/*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
	
	/*Insertar el objeto*/
	var request = object.put({
		dni: document.querySelector("#dni").value,
		name: document.querySelector("#name").value,
		surname: document.querySelector("#surname").value
	});
	/*Control de errores para el metodo put*/
	request.onerror = function (e) {
		alert(request.error.name + '\n\n' + request.error.message);
	};
	/*Transacción satisfactoria, en este caso se vacian campos input*/
	data.oncomplete = function (e) {
		document.querySelector('#dni').value = '';
		document.querySelector('#name').value = '';
		document.querySelector('#surname').value = '';
		alert('Object successfully added');
		/*carga los datos*/
		loadAll();
	};
}

/*VACIAR EL ALMACEN DE OBJETOS*/
function clearData() {
    /*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
    /*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readwrite");
    /*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");

	/*Vaciar el almacen de objetos*/
	var objectStoreRequest = object.clear();
	
	/*control de requerimiento*/
	objectStoreRequest.onsuccess = function(event) {
		console.warn('Delete');
	};
	
	/*Transacción satisfactoria, refresca la lista*/
	data.oncomplete = function(event) {
		console.warn('Complete');
		loadAll();
	};
	
	/*Control de errores para el metodo clear*/
	data.onerror = function(event) {
		console.warn(error);
	};

};

/*ELIMINAR UN REGISTRO EXISTENTE*/
function delObj(id){
    /*Recuperando el conector de la base de datos*/
	var active = dataBase.result;
    /*Iniciar una transacción -almacen -modo*/
	var data = active.transaction(["claves"], "readwrite");
    /*Indicar sobre qué almacén vamos a trabajar*/
	var object = data.objectStore("claves");
    
	/*Eliminar el objeto*/
    var request = object.delete(id);
    
	/*Control de errores para el metodo delete*/
	request.onerror = function(e) {
      console.log(e);
    }
	
    /*Transacción satisfactoria, en este caso se recargan los datos*/
    request.onsuccess = function(e) {
      loadAll();
    }
}










/////////////////////////////////*GUARDAR COORDENADAS PARA TAXIMETRO*///////////////////////////////////

function storeCoords(lat, lon, acc, tsp){
	var active = dataBase.result;
	var data = active.transaction(["taxmeter"], "readwrite");
	var object = data.objectStore("taxmeter");
	var objectStoreRequest = object.clear();
	var request = object.put({
		iden:				1,
		latitud: 			lat,
		longitud:			lon,
		accurate:			acc,
		timestamp:			tsp,
		avance:				0
	});
}
/*OBTENER COORDENADAS PARA TAXIMETRO*/
var coords = new Object();
function currentCoords(){
	var active = dataBase.result;
	var data = active.transaction(["taxmeter"], "readwrite");
	var object = data.objectStore("taxmeter");
	
	var index = object.index("by_iden");
	var request = index.get(1);
	
	request.onsuccess = function () {
		var result = request.result;
		if (result !== undefined) {
			coords = {
				latitud: result.latitud, 
				longitud: result.longitud,
				accurate: result.accurate,
				avance: result.avance
			};
		}
	};
}
/*ACTUALIZAR COORDENADAS PARA TAXIMETRO*/
function updateCoords(lat, lon, acc, tsp, avance){
	var active = dataBase.result;
	var data = active.transaction(["taxmeter"], "readwrite");
	var object = data.objectStore("taxmeter");
	var index = object.index("by_iden");
	var request = index.get(1);
	request.onsuccess = function () {
		var data = request.result;
		
		data.date		=	(new Date).getTime(),
		data.latitud	=	lat,
		data.longitud	=	lon,
		data.accurate	=	acc,
		data.timestamp	=	tsp,
		data.avance		=	avance
		
		object.put(data);
	}
}











/*
	en app.js;
	storeCoords(lat, lon, acc, tsp);
	intervalTaximetro();

*/

//tsp = new Date(new Date(position.timestamp).toISOString().slice(0, 19)+'+0600').toISOString().slice(0, 19).replace('T', ' ');

var km_current = 0;
function initTaximetro(lat, lon, acc, tsp){
	currentCoords();
	var metrosRecorridos = haversineDistance([coords.latitud,coords.longitud], [lat,lon]);
	metrosRecorridos = parseFloat(metrosRecorridos).toFixed(2);
	
	var totalRecorrido = parseFloat(coords.avance) + parseFloat(metrosRecorridos);
	
	km_recorridos = ( totalRecorrido / 1000 ).toFixed(2);
	costo_avance =   (parseFloat((totalRecorrido - 4000) * .009 ) + parseFloat(60.00)).toFixed(2);
	
	/*console.log('metrosRecorridos: '+metrosRecorridos);
	console.log('totalRecorrido: '+totalRecorrido);
	console.log('km_recorridos: '+km_recorridos);
	console.log('costo_avance: '+costo_avance);
	console.log('km_current: '+km_current);*/
	console.log('Taximetro en ejecucion');
	if((isNaN(km_recorridos) == false) && (isNaN(costo_avance) == false)){
		km_current = km_recorridos;
		updateCoords(lat,lon,acc,tsp,totalRecorrido);
		if(km_recorridos <= 4){
			var kma = document.getElementById("km_avance");
			kma.innerHTML = km_recorridos + ' km';
			
			var coa = document.getElementById("costo_avance");
			coa.innerHTML = '$60.00';
		}else{
			var kma = document.getElementById("km_avance");
			kma.innerHTML = km_recorridos + ' km';
			
			var coa = document.getElementById("costo_avance");
			coa.innerHTML = '$' + costo_avance;
		}
	}
}
var countMeter;
function intervalTaximetro(){
	countMeter = setInterval('initTaximetro(lat, lon, acc, tsp)',1000);
}














/*OBTENER EL ULTIMO ID DEL GPS*/
function lastIdGps(callback){
	var active = dataBase.result;
	var data = active.transaction(["gps"], "readonly");
	var object = data.objectStore("gps");
	var elements;	
	object.openCursor().onsuccess = function (e) {
		var result = e.target.result;
		if (result === null) {
			return;
		}
		elements = result.value;
		result.continue();
	};
	data.oncomplete = function () {
		//console.log(elements);
		if(elements == undefined){
			callback(0);
		}else{
			callback(elements['id']);
		}
	};
}


/*OBTENER ULTIMA LOCALIZACION GUARDADA*/
function lastSavedPoints(callback){
	lastIdGps(function(datagps){
		if(datagps != 0){
			var active = dataBase.result;
			var data = active.transaction(["gps"], "readwrite");
			var object = data.objectStore("gps");
			
			var index = object.index("by_iden");
			var request = index.get(datagps);
			request.onsuccess = function () {
				var result = request.result;
				/*result !== undefined*/
				var metrosRecorridos = haversineDistance([result.latitud,result.longitud], [lat,lon]);
				console.log(result.latitud);
				console.log(result.longitud);
				console.log(lat);
				console.log(lon);
				if (metrosRecorridos > 100){
					storeGps();
				}
			};
		}else{
			storeGps();
		}
	});
	callback();
}


/*Comprobación de conexión*/
var online;
function startLink(time) {
	$.ajax({
		url: url_app + 'mobile/ping',
		type: 'POST',
		data: 'interval=' + time,
		dataType: 'json',
		success: function(resp_success){
			if(resp_success['stat'] == 'ok'){
				document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link" style="color:#03A388 !important"></span>';
				online = true;
				if(resp_success['session'] ==  null){
					window.location = url_app +  "login";
				}
			}else{
				document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink"  style="color:red  !important""></span>';
				online = false;
			}
		},
		error: function(respuesta){ 
			document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink"  style="color:red  !important""></span>';
			online = false;
			console.warn('offline');
		}
	});
}
var initLink = setInterval("startLink()",1000);



/*WebSockets RATCHET*/

var conn = new ab.Session('wss://172.20.8.99/wss2/',
var conn = new ab.Session('ws://172.20.8.99/',	
	function() {
		conn.subscribe('varios', function(topic, data) {
			console.log(data);
		});
	},
	function() {
		console.warn('Se ha cerrado la conexión con WebSockets');
	},
	{'skipSubprotocolCheck': true}
);

		
conn.publish(''+sendvar+'', 'varios');
//conn.publish(''+sendvar+'', 'varios',[],[],{acknowledge: true});

conn.subscribe('varios', function(topic, data) {
	var resp_success = JSON.parse(data);
	console.log(resp_success[0].resp);	
});
		

/*AJAX de SYNC*/
	
$.ajax({
	url: url_app + 'mobile/sync',
	type: 'POST',
	data: 'sendvar=' + sendvar,
	dataType: 'json',
	success: function(resp_success){
		
		if(resp_success[0]['resp'] == true){
			$.each(resp_success, function(k,v){
				//console.log(k+' -> ' +v.id);
				eliminarClave(v.id,'claves');
			});
		}
		switch (resp_success[0]['clave']) {
			case 'C1':
				storeTravel(resp_success[0]);							
				break;
		}
	},
	error: function(respuesta){ 
		console.warn('Sin conexión SYNC');
	}	
});
			

			
			
/*GPS*/
function startGps() {
	storeGps(function(){
		var active = dataBase.result;
		var data = active.transaction(["gps"], "readonly");
		var object = data.objectStore("gps");
		var elements = [];	
		object.openCursor().onsuccess = function (e) {
			var result = e.target.result;
			if (result === null) {
				return;
			}
			elements.push(result.value);
			result.continue();
		};
		data.oncomplete = function () {
			var sendvar = JSON.stringify(elements);
			if((navigator.onLine)&&(jQuery.isPlainObject(elements[0]))){
					$.ajax({
						url: url_app + 'mobile/gps',
						type: 'POST',
						data: 'sendvar=' + sendvar,
						dataType: 'json',
						success: function(resp_success){
							if(resp_success[0]['resp'] == true){
								$.each(resp_success, function(k,v){
									eliminarClave(v.id,'gps');
								});
							}					
						},
						error: function(respuesta){ 
							console.warn('Sin conexión GPS');
						}	
					});
			}
		};
		last_lat = lat;
		last_lon = lon;
	});
}
var initGps  = setInterval("startGps()",5000);





/*RECURSO DE INFORMACION DE DESTINO ENCRIPTADA*/
function startRide() {
	var active = dataBase.result;
	var data = active.transaction(["viaje"], "readwrite");
	var object = data.objectStore("viaje");
	
	var index = object.index("by_iden");
	var request = index.get(1);
	request.onsuccess = function () {
		var result = request.result;
		if (result !== undefined) {
			if(navigator.onLine){
				getBase(function(){
					var parametros = {
						"id_viaje" : result.id_viaje,
						"base" : globalBase
					};
					$.ajax({
						url: url_app + 'ride',
						type: 'POST',
						data: parametros,
						dataType: 'json',
						success: function(resp_success){

						/*****************************************************************************************************************/
						/*********************************** SERVICIOS CONDICIONADOS A LA RECEPCION DE LA CLAVES MEDIANTE LA RIDE ********/
						/*****************************************************************************************************************/						
							
							switch (resp_success['clave']) {
								/******************************************************************* SINCRONIZAR ESTADOS DE PANTALLAS ******/
								case 'R1':
									myApp.alert('Se sincronizará el estado de su movil con la central ', 'Atención',function(){
										loadTemplate(resp_success['set_page']);
									});
									
									break;
								/********************************************************************************** VIAJE POR CORDON *******/
								case 'A10':
									if (resp_success['new'] == true){
										$('#data_cordon').html('NO HAY DATOS DE CORDON');
										storeTravel(resp_success);
										$("#ride_false").hide();
										$("#ride_true").show();
										updatePageButtons('ride_false','ride_true');
										$("#exit_true").hide();
										$("#exit_false").show();
										updatePageButtons('exit_true','exit_false');
										myApp.alert('Vea los detalles de su destino en el menú', 'Nuevo destino');
									}else{
										$("#ride_true").hide();
										$("#ride_false").show();
										updatePageButtons('ride_true','ride_false');
									}
									break;
									
								/************************************************************************************** VIAJE ON AIR *******/
								case 'F15':
									if (resp_success['new'] == true){
										$('#data_cordon').html('NO HAY DATOS DE CORDON');
										storeTravel(resp_success);
										$("#air_service_des").hide();
										$("#air_service_act").show();
										updatePageButtons('air_service_des','air_service_act');
										myApp.alert('Vea los detalles de su destino en el menú', 'Servicio al aire');
									}else{
										$("#air_service_act").hide();
										$("#air_service_des").show();
										updatePageButtons('air_service_act','air_service_des');
									}
									break;
									
								/********************************************************************************** SALIDA POR SITIO *******/
								case 'F13':
									if (resp_success['new'] == true){
										$('#data_cordon').html('NO HAY DATOS DE CORDON');
										storeTravel(resp_success);
										$("#salida_sitio_des").hide();
										$("#salida_sitio_act").show();
										updatePageButtons('salida_sitio_des','salida_sitio_act');
										myApp.alert('Se autorizó la salida por sitio', 'Salida por sitio');
									}else{
										$("#salida_sitio_act").hide();
										$("#salida_sitio_des").show();
										updatePageButtons('salida_sitio_act','salida_sitio_des');
									}
									break;
								
								/********************************************************************************** FORMACION EN EL CORDON *******/
								case 'F14':
									if(resp_success['queue'] == true){
										myApp.confirm(
											'Turno: ' + resp_success['turno'] + 
											'<br><br>Verifique el cordón en el menú para conocer el estado.',
											'Asignado al cordón',
										function () {
											loadTemplate('base');
											setStoreVariable('base',resp_success['base'],function(){});
										});
										
										$('#data_cordon').html('');
										$.each(resp_success['cordon'], function( key, value ) {
											suma = parseInt(key) + parseInt(1);
										  $('#data_cordon').append('<div class="user-tail"><span class="ava-tail"><img src="'+url_app+'public/fw7/assets/img/tmp/driver-icon.png" alt="Operador"><div class="cordon_name">'+ suma +'.-'+value+'</div></span></div>');
										});
										
									}
									break;								
								
								/********************************************************************************** SALIDA POR SITIO *******/
								case 'F19':
									if (resp_success['new'] == false){
										$('#data_cordon').html('');
										$.each(resp_success['cordon'], function( key, value ) {
											suma = parseInt(key) + parseInt(1);
										  $('#data_cordon').append('<div class="user-tail"><span class="ava-tail"><img src="'+url_app+'public/fw7/assets/img/tmp/driver-icon.png" alt="Operador"><div class="cordon_name">'+ suma +'.-'+value+'</div></span></div>');
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
									break;
								/********************************************************************************** ESTADO NORMALIZADO *******/
								case 'F20':
										$("#exit_true").show();
										$("#exit_false").hide();
										updatePageButtons('exit_false','exit_true');
									break;
								/*************************************************************************** MODIFICAR MODO DE VIAJE *******/
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
									break;
								/********************************************************************************** INICIO D LABORES *******/
								case 'C1':
									storeTravel(resp_success);
									break;
							}
							
							
							if(resp_success['mensaje'] !== false) {
								storeClave('F17',''+resp_success['id_mensaje']+'','NULL','NULL','NULL','Mensaje Leido',function(){
									timbre.play();
									myApp.alert(resp_success['mensaje'], 'Mensaje',function(){
										timbre.pause();
									});
								});
							}
							
							
						/*****************************************************************************************************************/
						/********************************* FIN SERVICIOS CONDICIONADOS A LA RECEPCION DE LA CLAVES MEDIANTE LA RIDE ******/
						/*****************************************************************************************************************/						
							
						},
						error: function(respuesta){ 
							console.warn('Sin conexión RIDE');
						}
					});
				});
			}
		}else{
			var travel_0 = '{"id_viaje":0}';
			storeTravel(travel_0);
		}
	};
}

var initRide = setInterval("startRide()",2000);












/*ABLI*/
var conn;
var presenceChannel;
var presence;

function conectar(){
	conn = new Ably.Realtime({key:ably_api_key,clientId:'cid' + id_operador +'',disconnectedRetryTimeout:3000, suspendedRetryTimeout:5000});
}

conectar();

$("body").on("click", "#infolink", function() {
	conn.connection.close();
});

conn.connection.on('connected', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-link" style="font-weight: bold; color:#03A388 !important"></span>';

		presenceChannel = conn.channels.get(ably_presence);
		presence = presenceChannel.presence;
		presence.enterClient('cid' + id_operador,function(){
			presence.enter();
		});
})
conn.connection.on('disconnected', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:#ffd400 !important"></span>';
})
conn.connection.on('suspended', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:red !important"></span>';
})
conn.connection.on('connecting', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink blink_me" style="font-weight: bold; color:#FFFFFF !important"></span>';
})
conn.connection.on('closed', function() {
	document.getElementById("infolink").innerHTML = '<span class="kkicon icon-unlink" style="font-weight: bold; color:red !important"></span>';
	conn.connection.connect();
})

var gpsChannel = conn.channels.get('gps'+id_operador+'');
gpsChannel.subscribe(function(message){
	gps_ok(message.data);
});

var syncChannel = conn.channels.get('sync'+id_operador+'');
syncChannel.subscribe(function(message){
	sync_ok(message.data);
});

var rideChannel = conn.channels.get('ride'+id_operador+'');
rideChannel.subscribe(function(message){
	ride_ok(message.data);
});

var bcChannel = conn.channels.get('broadcast');
bcChannel.subscribe(function(message){
	broadcastPlay(message.data);
});