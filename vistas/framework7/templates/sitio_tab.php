<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="sitio_tab" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="c10 menu-link" data-return="sitio_km">
							<span>C10</span>
							<span class="label_code">Inicio de escala</span>
						</a>
					</div>
					<div class="col-50" id="abandono_service_act" style="display: none;">
						<a class="a14 menu-link" href="javascript:void(0)"  data-return="sitio_km">
							<span>A14</span>
							<span class="label_code">Adandono de servicio</span>
						</a>
					</div>
					<div class="col-50" id="abandono_service_des">
						<a class="code_disabled" href="javascript:void(0);" >
							<span>A14</span>
							<span class="label_code">Adandono de servicio</span>
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
				<div class="row text-center reduce">
					<div class="col-100">
						<div id='km_avance' class="crono_wrapper">Servicio Tabulado</div>
					</div>	
				</div>
			</nav>
		</div>
	</div>
</script>