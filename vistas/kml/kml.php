<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <title>KML RUTA</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>

function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 11,
    center: {lat: 19.412577, lng: -99.151558}
  });

  var ctaLayer = new google.maps.KmlLayer({
    url: '<?=URL_APP?>tmp/<?=$file?>',
    map: map
  });
}

    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS?>&signed_in=true&callback=initMap">
    </script>
  </body>
</html>