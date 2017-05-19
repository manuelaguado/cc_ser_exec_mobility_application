<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Editar cliente:
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="editar_cliente">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="cat_tipocliente">Tipo de Cliente</label>
										  <select  class="form-control" id="cat_tipocliente" name="cat_tipocliente">
											<?php echo $tiposClientes; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="id_rol">Rol</label>
										  <select class="form-control" id="id_rol" name ="id_rol">
											<?php echo $roles; ?>
										  </select>
									  </div>	
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<label for="cat_statuscliente">Status del Cliente</label>
										  <select  class="form-control" id="cat_statuscliente" name="cat_statuscliente">
											<?php echo $satatusCliente; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="nombre">Nombre</label>
										<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre(s)" autocomplete="off" value="<?=$cliente['nombre' ]?>">
									  </div>									  
								</div>
							</div>
						</div>
					</div>
					<div id="error_alerta" > </div>
					<input type="hidden" id="id_cliente" name="id_cliente" value="<?=$id_cliente?>">
					<div class="modal-footer">
						<button  class="btn btn-ar btn-success" type="button" onclick="edit_client();">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
