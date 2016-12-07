<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>tracker de unidades</title>
	<style type="text/css">
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#map {
			height: 100%;
		}	
		.labels {
			 width: 32px;
			 height: 28px;
			 color: #fff;
			 text-align:center;
			 -moz-border-radius: 50%;
			 -webkit-border-radius: 50%;
			 border-radius: 50%;
			 background: #7F1313;
			 padding-top:5px;
			 font-size:1.4em;
		}
	</style>
	<script type="text/javascript">
		function initMap() {
			var centro_mapa = new google.maps.LatLng(19.434644,-99.212046);
			
			var map = new google.maps.Map(document.getElementById('map'), {
				zoom: 17,
				center: centro_mapa,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			});
			var image = {
				url: '',
				size: new google.maps.Size(24, 24),
				origin: new google.maps.Point(0, 0),
				anchor: new google.maps.Point(0, 0)
			};

			<?php
			$geo_array = json_encode($geoDraw, JSON_NUMERIC_CHECK);
			echo "var geocerca = ". $geo_array . ";\n";	
			?>  
			var drawGeocerca = new google.maps.Polygon({
				paths: geocerca,
				strokeColor: '#82c5e0',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#c7d7dd',
				fillOpacity: 0.35
			});
			drawGeocerca.setMap(map);
			
			<?php
			$js_array = json_encode($unitsIn);
			echo "var autos = ". $js_array . ";\n";	
			?>
			for (var i = 0; i < autos.length; i++) {
				var auto = autos[i];			
				var marker = new MarkerWithLabel({
					position: {lat: auto.latitud, lng: auto.longitud},
					map: map,
					icon: image,
					draggable: false,
					raiseOnDrag: true,
					labelContent: auto.numeq,
					labelAnchor: new google.maps.Point(16, 16),
					labelClass: "labels",
					labelInBackground: false,
					zIndex:6000
				});
				var content = auto.time;
				var infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
					return function() {
						infowindow.setContent(content);
						infowindow.open(map,marker);
					};
				})(marker,content,infowindow)); 				
			}
		}
		function log(h) {
			document.getElementById("log").innerHTML += h + "<br />";
		}
	</script>
	</head>
	<body onload="initMap()">
		<div id="map"></div>
		<div id="log"></div>
	</body>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=<?=GOOGLE_MAPS?>"></script>
	<script type="text/javascript" src="<?=URL_PUBLIC?>js/markerwithlabel.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
</html>