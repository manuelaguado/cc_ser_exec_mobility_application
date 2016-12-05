<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<script id="cambio_ruta" type="text/template">
	<div data-page="index" class="page page-bg">
		<div class="page-content">
			<div class="list-block">
				<ul>
					<li>
						<a href="javascript:void(0)" class="cambio_ruta1 item-content" data-origen='{{origen}}'>
							<div class="item-inner"> 
								<div class="item-title">Tráfico</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" class="cambio_ruta2 item-content" data-origen='{{origen}}'>
							<div class="item-inner"> 
								<div class="item-title">Manifestación</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" class="cambio_ruta3 item-content" data-origen='{{origen}}'>
							<div class="item-inner">
								<div class="item-title">Calle cerrada</div>
							</div>
						</a>
					</li>
					<li>
						<a href="javascript:void(0)" class="cambio_ruta4 item-content" data-origen='{{origen}}'>
							<div class="item-inner">
								<div class="item-title">Otro</div>
							</div>
						</a>
					</li>
				</ul>
			</div>
			<div class="content-block">
				<div class="row">
					<div class="col-33"></div>
					<div class="col-33"><a href="#" class="button button-big return" data-origen='{{origen}}' style="border:#08957D; background:#08957D; color:#ffffff !important; ">Cancelar</a></div>
					<div class="col-33"></div>
				</div>
			</div>
		</div>
	</div>
</script>