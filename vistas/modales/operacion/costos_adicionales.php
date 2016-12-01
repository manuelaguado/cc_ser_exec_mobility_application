<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Agregar costos adicionales al viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form id="costos_adicionales">
					Aquí un form con los costos adicionales
					<input type="hidden" id="id_viaje" name="id_viaje" value="<?=$id_viaje?>" />
				</form>
			</div>
			<div class="modal-footer">					
				<button onclick="costos_adicionales_do();" class="btn btn-ar btn-success" type="button" id="add">Agregar costo</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>