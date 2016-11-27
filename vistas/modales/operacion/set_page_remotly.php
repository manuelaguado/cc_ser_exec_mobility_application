<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Establecer página remota
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="set_page_remotly">
					<br>
					<select class="form-control" id="page" name="page">
						<option value="abandono">Abandono</option>
						<option value="abordo">Abordo</option>
						<option value="acudir">Acudir</option>
						<option value="base">Base</option>
						<option value="cambio_ruta">Cambio de ruta</option>
						<option value="elegir_base">Elegir base</option>
						<option value="escala">Escala</option>
						<option value="inicio">Inicio</option>
						<option value="regreso">Regreso</option>
						<option value="sitio_km">Sitio por km para sitio</option>
						<option value="sitio_tab">Sitio tabulado para sitio</option>
						<option value="sitio_tiempo">Sitio por tiempo para sitio</option>
						<option value="tipo_viaje">Seleccionar tipo de viaje</option>
						<option value="tipo_viaje_sitio">Seleccionar tipo de viaje para salida por sitio</option>
						<option value="viaje_km">Cobro por km para viaje</option>
						<option value="viaje_tab">Cobro tabulado para viaje</option>
						<option value="viaje_tiempo">Cobro por tiempo para viaje</option>
					</select>
					<br>
					<div class="modal-footer">
						<input type="hidden" value="<?=$id_operador?>" id="id_operador" name="id_operador" >
						<button onclick="setPageRemotly();" class="btn btn-ar btn-success" type="button" id="add">Enviar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>