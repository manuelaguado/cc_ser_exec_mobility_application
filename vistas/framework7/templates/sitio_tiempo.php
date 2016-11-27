<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="sitio_tiempo" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
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
						<a href="javascript:void(0);" class="a14 menu-link" data-return="sitio_tiempo">
							<span>A14</span>
							<span class="label_code">Abandono de servicio</span>
						</a>
					</div>
				</div>
				<div class="row text-center reduce">
					<div class="col-100">
						<div id='crono' class="crono_wrapper">00:00:00</div>
					</div>
				</div>
				<div class="row text-center reduce">
					<div class="col-100">
						<div id='costo' class="costo_wrapper">$00.00</div>
					</div>	
				</div>	
			</nav>
		</div>
	</div>
</script>