<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
                                   Pagar al operador <?=$id_operador?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente acción marcara todos los viajes del ciclo como pagados.
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="marcar_como_pagado_do(<?=$id_operador?>)" class="btn btn-ar btn-success" type="button" id="add">Pagar</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
