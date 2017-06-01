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
               var mod_ref;
               var mod_stre;
               var mod_ext;
               var mod_gcd;
               var mod_inv;
		function initMap() {
                     /*
                     destino_referencia = mod_ref
                     destino_calle = mod_stre
                     destino_numero_exterior = mod_ext
                     geocoordenadas destino = mod_gcd
                     geocodificacion_inversa_destino = mod_inv
                     */
                     $('#refmod_aux', window.parent.document).val('');
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
                            $('#refmod_aux', window.parent.document).val($('#pac-input').val() + ' ');
			});


			google.maps.event.addListener(map, "dblclick", function(event) {
				var lat = event.latLng.lat();
				var lng = event.latLng.lng();
				mod_gcd = lat+','+lng;
				$.post( "https://maps.googleapis.com/maps/api/geocode/json?latlng="+lat+","+lng+"&key=<?=GOOGLE_MAPS?>", function( data ) {
					var promise = new Promise(function(done,fail){
						mod_inv = data.results[0].formatted_address;
						var breakit = (data.results[0].address_components).length;
						for(var i in data.results[0].address_components){
							for(var k in data.results[0].address_components[i].types){

								switch (data.results[0].address_components[i].types[k]) {


									case 'locality':
										mod_ref = ( $('#refmod_aux', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_1':
										mod_ref = ( $('#refmod_aux', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_2':
										mod_ref = ( $('#refmod_aux', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'administrative_area_level_3':
										mod_ref = ( $('#refmod_aux', window.parent.document).val() + data.results[0].address_components[i].long_name + ' ');
										break;
									case 'route':
										mod_stre = data.results[0].address_components[i].long_name;
										break;
									case 'street_number':
										mod_ext = data.results[0].address_components[i].long_name;
										break;
									case 'postal_code':

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
                                          $.post( "../modificar_destino_do", {a:mod_ref,b:mod_stre,c:mod_ext,d:mod_gcd,e:mod_inv,f:<?=$id_viaje?>}).done(function( data ) {
                                                 dato = $.parseJSON(data);
                                                 if(dato.resp == true){
								$.post( "../setClaveOk/<?=$id_viaje?>/T3");
                                                        alert('destino modificado');
                                                        $( '.modal-backdrop', window.parent.document ).remove();
              						$( '#myModal', window.parent.document ).remove();
              						$( "iframe[name='gm-master']", window.parent.document ).remove();
              						$( '.pac-container', window.parent.document ).remove();

                                                 }else{
                                                        alert('Error crítico notifique de inmediato mod_d_01');
                                                 };

                                          }, "json");

					}).catch(function(){
						alert('Error crítico notifique de inmediato mod_d_01');
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
