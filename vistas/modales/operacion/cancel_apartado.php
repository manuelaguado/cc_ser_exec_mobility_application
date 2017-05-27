<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Cancelar viaje: <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form id="cancel_apartado_set">
					La siguiente acción cancelará el viaje con identificador: <?=$id_viaje?>

					<br><br>
					Seleccione el motivo de cancelación:<br><br>
					<div class="col-sm-5">
						<select class="form-control" id="cat_cancelaciones" name="cat_cancelaciones">
							<?php echo $razones_cancelacion; ?>
						</select>
					</div>
					<br>
				

					<br><br>¿Está seguro de continuar con esta acción?

					<input type="hidden" id="id_viaje" name="id_viaje" value="<?=$id_viaje?>" />
					<input type="hidden" id="origen" name="origen" value="<?=$origen?>" />
				</form>
			</div>

			<div class="modal-footer">
				<button onclick="cancel_apartado_set();" class="btn btn-ar btn-success" type="button" id="add">Si, cancelar servicio</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
