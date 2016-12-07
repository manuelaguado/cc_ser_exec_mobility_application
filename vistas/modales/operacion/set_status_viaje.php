<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					<?=$write['title']?> <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form id="setear_status_viaje">
					La siguiente acción <?=$write['accion']?> el viaje con identificador: <?=$id_viaje?>

					<?php if($stat == '170'){ ?>
					<br><br>
					Estado del operador después del seteo:<br><br>					
						<div class="radio">
							<?php if($origen == 'proceso'){ ?>
							<label>
								<input name="status_operador" value="segundo" type="radio" class="ace input-lg">
								<span class="lbl "> Enviar de segundo</span>
							</label><br><br>
							
							<label>
								<input name="status_operador" value="cola" type="radio" class="ace input-lg">
								<span class="lbl "> Envial a la cola</span>
							</label><br><br>							
							
							<?php } ?>
							<?php if(($origen == 'proceso')||($origen == 'asignados')){ ?>

							<label>
								<input name="status_operador" value="suspender" type="radio" class="ace input-lg">
								<span class="lbl "> Suspender operador</span>
							</label><br><br>
							
							<label>
								<input name="status_operador" value="omitir" type="radio" class="ace input-lg">
								<span class="lbl "> Sin acciones en operador</span>
							</label>
							
							<?php } ?>
						</div>
					<?php } ?>					
					
					<?php if($stat == '173'){ ?>
					<br><br>
					Seleccione el motivo de cancelación:<br><br>
					<div class="col-sm-5">
						<select class="form-control" id="cat_cancelaciones" name="cat_cancelaciones">
							<?php echo $razones_cancelacion; ?>
						</select>
					</div>
					<br>
					<?php if($origen == 'asignados'){ ?>
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
						} 
					?>					
					
					<br><br>¿Está seguro de continuar con esta acción?
				
					<input type="hidden" id="id_viaje" name="id_viaje" value="<?=$id_viaje?>" />
					<input type="hidden" id="stat" name="stat" value="<?=$stat?>" />
					<input type="hidden" id="origen" name="origen" value="<?=$origen?>" />
				</form>
			</div>
			
			<div class="modal-footer">					
				<button onclick="setear_status_viaje();" class="btn btn-ar btn-success" type="button" id="add">Si, <?=$write['boton']?> servicio</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>