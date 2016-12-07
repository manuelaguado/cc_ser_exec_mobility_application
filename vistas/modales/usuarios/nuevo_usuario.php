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
				<form role="form" id="nuevo_usuario">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="id_ubicacion">Ubicación</label>
										  <select  class="form-control" id="id_ubicacion" name="id_ubicacion">
											<?php echo $ubicacion; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="usuario">Usuario</label>
										<input type="text" class="form-control" id="usuario" name="usuario" placeholder="nombre de usuario">
									  </div>
									  <div class="form-group">
										<label for="nombres">Nombre</label>
										<input type="text" class="form-control" id="nombres" name="nombres" placeholder="Nombre(s)">
									  </div>
									  <div class="form-group">
										<label for="apellido_paterno">Apellido Paterno</label>
										<input type="text" class="form-control" id="apellido_paterno" name="apellido_paterno" placeholder="Apellido Paterno">
									  </div>
									  <div class="form-group">
										<label for="apellido_materno">Apellido Materno</label>
										<input type="text" class="form-control" id="apellido_materno" name="apellido_materno" placeholder="Apellido Materno">
									  </div>
								</div>
								<div class="col-md-6">	
									  <div class="form-group">
										<label for="correo">Correo electrónico</label>
										<input type="email" class="form-control" id="correo" name="correo" placeholder="Ingresar correo">
									  </div>
									  <div class="form-group">
										<label for="password">Contraseña</label>
										<input type="password" class="form-control" id="password" name="password" placeholder="Contraseña">
									  </div>
									  <div class="form-group">
										<label for="password2">Confirmar contraseña</label>
										<input type="password" class="form-control" id="password2" name="password2" placeholder="Confirmar contraseña">
									  </div>
									  <div class="form-group">
										<label for="id_rol">Rol</label>
										  <select class="form-control" id="id_rol" name ="id_rol">
											<?php echo $roles; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="fecha_ingreso">Fecha de ingreso (AAAA-MM-DD)</label>
										  <input type="text" class="form-control mask-date" id="fecha_ingreso" name="fecha_ingreso" placeholder="Seleccione la fecha en que ingresó" value="">
									  </div>
										<script type="text/javascript">
											jQuery(function($) {
												$('.mask-date').mask('9999-99-99');
											});
										</script>									
									  <div class="form-group">
											<label for="cat_status">Habilitado</label>
											<div class="checkbox">
												<input id="cat_status" name="cat_status" type="checkbox" checked value="3" style="position:relative; left:20px;">
											</div>
									  </div>									  
									  
								</div>
							</div>
						</div>
					</div>

					
					<div id="error_alerta" > </div>
					

					<div class="modal-footer">
						<button  class="btn btn-ar btn-success" type="button" onclick="graba_user();">Agregar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>