<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<title>tracker de unidades</title>
	<style>
		#map {
			height: 100%;
		}
		.controls {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 32px;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
			z-index:10000000000;
		}

		#pac-input {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 300px;
		}

		#pac-input:focus {
			border-color: #4d90fe;
		}

		.pac-container {
			font-family: Roboto;
		}

		#type-selector {
			color: #fff;
			background-color: #4d90fe;
			padding: 5px 11px 0px 11px;
		}

		#type-selector label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}
		#target {
			width: 345px;
		}			
	</style>
    <script>
		function initMap() {
			$('#destino_referencia', window.parent.document).val('');
			var mapDiv = document.getElementById('map');
			var lat;
			var lng;
			var latLng = new google.maps.LatLng(19.399874, -99.146903);
			var map = new google.maps.Map(mapDiv, {
				zoom: 12,
				center: latLng
			});
			
			var input = document.getElementById('pac-input');
			var searchBox = new google.maps.places.SearchBox(input);
			map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

			map.addListener('bounds_changed', function() {
				searchBox.setBounds(map.getBounds());
			});			

			
			var markers = [];
			searchBox.addListener('places_changed', function() {
				var places = searchBox.getPlaces();

				if (places.length == 0) {
					return;
				}
				markers.forEach(function(marker) {
					marker.setMap(null);
				});
				markers = [];

				var bounds = new google.maps.LatLngBounds();
				places.forEach(function(place) {
					var icon = {
						url: place.icon,
						size: new google.maps.Size(71, 71),
						origin: new google.maps.Point(0, 0),
						anchor: new google.maps.Point(17, 34),
						scaledSize: new google.maps.Size(25, 25)
					};

					markers.push(new google.maps.Marker({
						map: map,
						icon: icon,
						title: place.name,
						position: place.geometry.location
					}));

					if (place.geometry.viewport) {
						bounds.union(place.geometry.viewport);
					} else {
						bounds.extend(place.geometry.location);
					}
				});
				map.fitBounds(bounds);
				$('#destino_referencia', window.parent.document).val($('#pac-input').val() + ' ');
			});
			
			
			google.maps.event.addListener(map, "dblclick", function(event) {
				var lat = event.latLng.lat();
				var lng = event.latLng.lng();
				$( '#geocoordenadas_destino', window.parent.document ).val(lat+','+lng);
				$.post( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key=<?=GOOGLE_MAPS?>", function( data ) {
					var promise = new Promise(function(done,fail){
						$( "#geocodificacion_inversa_destino", window.parent.document ).val(data.results[0].formatted_address);
						
						var breakit = (data.results[0].address_components).length;
						for(var i in data.results[0].address_components){
							for(var k in data.results[0].address_components[i].types){
								
								switch (data.results[0].address_components[i].types[k]) {
									
									
									case 'locality':
										$('#destino_referencia', window.parent.document).val($('#destino_referencia', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_1':
										$('#destino_referencia', window.parent.document).val($('#destino_referencia', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_2':
										$('#destino_referencia', window.parent.document).val($('#destino_referencia', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_3':
										$('#destino_referencia', window.parent.document).val($('#destino_referencia', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'route':
										$('#destino_calle', window.parent.document).val(data.results[0].address_components[i].long_name);
										$('#destino_hide_ok', window.parent.document).removeClass('hide');
										break;
									case 'street_number':
										$('#destino_num_ext', window.parent.document).val(data.results[0].address_components[i].long_name);
										$('#destino_hide_ok', window.parent.document).removeClass('hide');
										break;
									case 'postal_code':
										$.post( "../asentamientos/busqueda_cp?query="+data.results[0].address_components[i].long_name, function( result ) {

											var dat = JSON.parse(result);
											if(dat.suggestions[0] != null){
												$('#id_asentamiento_destino', window.parent.document).val(dat.suggestions[0].data);
												$('#asentamiento_destino', window.parent.document).val(dat.suggestions[0].colonia+', '+dat.suggestions[0].cp+',  '+dat.suggestions[0].ciudad+', '+dat.suggestions[0].municipio+', '+dat.suggestions[0].estado);
											}
											done();
										});
										break;
									default:
										break;
								}
								if(k == breakit){
									done();
								}
							}
						}
						setTimeout(function(){
							done();
						},500)
					}).then(function() {
						$( '.modal-backdrop', window.parent.document ).remove();
						$( '#myModal', window.parent.document ).remove();
						$( "iframe[name='gm-master']", window.parent.document ).remove();
						$( '.pac-container', window.parent.document ).remove();
					}).catch(function(){
						console.log('Promesa no cumplida');
					})
				});
			});
		}
    </script>
	</head>
	<body>
		<input id="pac-input" class="controls" type="text" placeholder="Referencia">
		<div id="map" style="height:740px !important"></div>
	</body>
	
	<script src="<?=URL_PUBLIC?>js/generales.js"></script>
	<script src="https://code.jquery.com/jquery-3.1.0.min.js"   integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s="   crossorigin="anonymous"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS?>&libraries=places&signed_in=true&callback=initMap" async defer></script>
</html>