<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Asignar equipo
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="asignacion">	
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="id_operador">Operador</label>
										<select class="form-control" id="id_operador" name="id_operador">
											<?php echo $lista_operadores; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" id="id_celular" name="id_celular" value="<?=$id_celular?>" />
						<input type="hidden" id="cat_status_operador_celular" name="cat_status_operador_celular" value="31" />
						<button  onclick="asignar_celular();" class="btn btn-ar btn-success" type="button">Asignar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>