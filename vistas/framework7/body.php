<body>

<div class="statusbar-overlay"></div>
<div class="panel-overlay"></div>

<?php
/*Panel's*/
include_once('left_panel.php');
include_once('right_panel.php');

/*Views*/
include_once('main.php');

/*Templates*/
include_once('templates/base.php');
include_once('templates/inicio.php');
include_once('templates/acudir.php');
include_once('templates/abordo.php');

include_once('templates/tipo_viaje.php');
	include_once('templates/viaje_tiempo.php');
	include_once('templates/viaje_km.php');
	include_once('templates/viaje_tab.php');
	
include_once('templates/tipo_viaje_sitio.php');
	include_once('templates/sitio_tiempo.php');
	include_once('templates/sitio_km.php');
	include_once('templates/sitio_tab.php');
	
include_once('templates/escala.php');
include_once('templates/regreso.php');
include_once('templates/elegir_base.php');
include_once('templates/cambio_ruta.php');
include_once('templates/abandono.php');

/*Pops*/
include_once('elements/pops.php');


include_once('footer.php');
?>


</body>