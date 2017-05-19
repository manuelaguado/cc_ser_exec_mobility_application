<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Estado del operador
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="editar_status_operador">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									  <div class="form-group">
										<label for="cat_statusoperador">Estado del operador</label>
										  <select class="form-control" id="cat_statusoperador" name="cat_statusoperador">
											<?php echo $selectStatusOperador; ?>
										  </select>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="id_operador" name="id_operador" value="<?php echo $id_operador; ?>">
					<input type="hidden" id="id_usuario" name="id_usuario" value="<?php echo $valores['id_usuario']; ?>">
					<div class="modal-footer">
						<button  onclick="status_operador_do();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>