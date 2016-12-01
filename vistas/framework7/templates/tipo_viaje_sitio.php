<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="tipo_viaje_sitio" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="a15_sitio menu-link">
							<span>A15</span>
							<span class="label_code">Servicio por km</span>
						</a>
					</div>
					<div class="col-50">
						<a href="javascript:void(0);" class="a2_sitio menu-link">
							<span>A2</span>
							<span class="label_code">Servicio por tiempo</span>
						</a>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="a16_sitio menu-link">
							<span>A16</span>
							<span class="label_code">Servicio tabulado</span>
						</a>
					</div>
					<div class="col-50" id="cancel_service_act" style="display: none;">
						<a class="c6 menu-link" href="javascript:void(0)">
							<span>C6</span>
							<span class="label_code">Servicio cancelado</span>
						</a>
					</div>
					<div class="col-50" id="cancel_service_des">
						<a class="code_disabled" href="javascript:void(0);" >
							<span>C6</span>
							<span class="label_code">Servicio cancelado</span>
						</a>
					</div>	
				</div>
			</nav>
		</div>
	</div>
</script>