<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
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
			var centro_mapa = new google.maps.LatLng(<?=$coords->lat?>,<?=$coords->lng?>);
			var posicion_marker = new google.maps.LatLng(<?=$coords->lat?>,<?=$coords->lng?>);

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
			var marker = new MarkerWithLabel({
				position: posicion_marker,
				map: map,
				icon: image,
				draggable: false,
				raiseOnDrag: true,
				labelContent: "<?=$num_eq?>", <?php /*etiqueta con el numero economico*/ ?>
				labelAnchor: new google.maps.Point(16, 16),
				labelClass: "labels", // the CSS class for the label
				labelInBackground: false,
				zIndex:6000
			});
			setInterval(updateMarker,3000);
			function updateMarker() {
				$.post('../lastPositionById/<?=$id_operador?>',{}, function(json) {
				marker.setPosition(new google.maps.LatLng(json['lat'],json['lng']));
				iw = new google.maps.InfoWindow({content: json['time'] + " " + json['bateria'] + "%"});
				//map.setCenter(new google.maps.LatLng(json['lat'],json['lng']));
				},"json");
			} 
			var iw = new google.maps.InfoWindow({
				content: "<?=$coords->time?>, <?=$coords->bateria?>%"
			});
			google.maps.event.addListener(marker, "click", function (e) { iw.open(map, this); });
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
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3"></script>
	<script type="text/javascript" src="<?=URL_PUBLIC?>js/markerwithlabel.js"></script>
	<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
</html>