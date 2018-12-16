<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Añadir nuevo usuario:
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nuevo_cliente_usr">
					<div class="panel panel-primary">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
									  <div class="form-group">
										<label for="empresa">Empresa o Cliente</label>
										<input type="hidden" id="padre" name="padre" value="">
										<input type="text" value="" class="form-control text-field" id="empresa" name="empresa" placeholder="Empresa" autocomplete="off">
									  </div>
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<label for="cat_tipocliente">Tipo de Usuario</label>
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
										<label for="cat_statuscliente">Status del Usuario</label>
										  <select  class="form-control" id="cat_statuscliente" name="cat_statuscliente">
											<?php echo $satatusCliente; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="nombre">Nombre</label>
										<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre(s)" autocomplete="off">
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div id="error_alerta" > </div>
					<div class="modal-footer">
						<button  class="btn btn-ar btn-success add_user_do" type="button">Agregar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
					<script>
						$('#empresa').autocomplete({
							serviceUrl: 'operacion/listadoEmpresas',
							minChars: 2,
							onSelect: function (suggestion) {
								$('#empresa').val(suggestion.value);
								$('#padre').val(suggestion.data);
							},
						});
					</script>
</div>
