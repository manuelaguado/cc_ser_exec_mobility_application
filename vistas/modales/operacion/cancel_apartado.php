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
					<?php if($origen == 'rojo'){ ?>
					<br><br>Estado del operador después del seteo:<br><br>					
						<div class="radio">
							
							<label>
								<input name="status_operador" value="segundo" type="radio" class="ace input-lg">
								<span class="lbl "> Enviar de segundo</span>
							</label><br><br>
							
							<label>
								<input name="status_operador" value="cola" type="radio" class="ace input-lg">
								<span class="lbl "> Envial a la cola</span>
							</label><br><br>
							
							<label>
								<input name="status_operador" value="omitir" type="radio" class="ace input-lg">
								<span class="lbl "> Sin acciones en operador</span>
							</label>
							
						</div>				
					<?php 
					}
					?>					
					
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