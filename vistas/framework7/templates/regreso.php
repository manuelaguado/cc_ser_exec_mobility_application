<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="regreso" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
				<div class="row text-center">
					<div class="col-50" id="request_queue_act">
						<a class="f14 menu-link" href="javascript:void(0)"  data-return="regreso">
							<span>F14</span>
							<span class="label_code">Solicitar cordón</span>
						</a>
					</div>
					<div class="col-50" id="request_queue_des" style="display: none;">
						<a class="code_disabled" href="javascript:void(0);" >
							<span>F14</span>
							<span class="label_code">Solicitar cordón</span>
						</a>
					</div>
					<div class="col-50" id="air_service_act" style="display: none;">
						<a class="f15 menu-link" href="javascript:void(0)">
							<span>F15</span>
							<span class="label_code">Servicio al aire</span>
						</a>
					</div>
					<div class="col-50" id="air_service_des">
						<a class="code_disabled" href="javascript:void(0);" >
							<span>F15</span>
							<span class="label_code">Servicio al aire</span>
						</a>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-50" id="fin_labores_act">
						<a href="javascript:void(0);" class="c2 menu-link">
							<span>C2</span>
							<span class="label_code">Fin de labores</span>
						</a>
					</div>
					<div class="col-50" id="mod_viaje_act" style="display: none;">
						<a href="javascript:void(0);" class="f16 menu-link">
							<span>F16</span>
							<span class="label_code">Modificar modo viaje</span>
						</a>
					</div>
					<div class="col-50" id="mod_viaje_des">
						<a href="javascript:void(0);" class="code_disabled">
							<span>F16</span>
							<span class="label_code">Modificar modo viaje</span>
						</a>
					</div>
				</div>
			</nav>
		</div>
	</div>
</script>