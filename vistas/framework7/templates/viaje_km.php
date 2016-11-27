<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="viaje_km" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="c10 menu-link" data-return="viaje_km">
							<span>C10</span>
							<span class="label_code">Inicio de escala</span>
						</a>
					</div>
					<div class="col-50" id="cambio_ruta_act">
						<a href="javascript:void(0);" class="c12 menu-link" data-return="viaje_km">
							<span>C12</span>
							<span class="label_code">Cambio de ruta</span>
						</a>
					</div>
					<div class="col-50" id="cambio_ruta_des" style="display: none;">
						<a href="javascript:void(0);" class="code_disabled">
							<span>C12</span>
							<span class="label_code">Cambio de ruta</span>
						</a>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="c9 menu-link">
							<span>C9</span>
							<span class="label_code">Servicio concluido</span>
						</a>
					</div>
					<div class="col-50">
						<a href="javascript:void(0);" class="c14 menu-link">
							<span>C14</span>
							<span class="label_code">Destino parcial</span>
						</a>
					</div>
				</div>
				<div class="row text-center">
					<div class="col-100">
						<a href="javascript:void(0);" class="a14 menu-link" data-return="viaje_km">
							<span>A14</span>
							<span class="label_code">Abandono de servicio</span>
						</a>
					</div>
				</div>
			</nav>
		</div>
	</div>
</script>