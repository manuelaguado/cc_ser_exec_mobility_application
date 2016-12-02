<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="viaje_tiempo" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<nav class="dashboard-menu">
				<div class="row text-center">
					<div class="col-50">
						<a href="javascript:void(0);" class="c9 menu-link">
							<span>C9</span>
							<span class="label_code">Servicio Concluido</span>
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
					<div class="col-100" id="abandono_act_vtime" style="display: none;">
						<a class="a14 menu-link" href="javascript:void(0)"  data-return="viaje_tiempo">
							<span>A14</span>
							<span class="label_code">Adandono de servicio</span>
						</a>
					</div>
					<div class="col-100" id="abandono_des_vtime">
						<a class="code_disabled" href="javascript:void(0);" >
							<span>A14</span>
							<span class="label_code">Adandono de servicio</span>
						</a>
					</div>
				</div>
				<div class="row text-center reduce">
					<div class="col-100">
						<div id='crono' class="crono_wrapper">00:00:00</div>
					</div>
				</div>				
			</nav>
		</div>
	</div>
</script>