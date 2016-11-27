<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Editar equipo
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="equipo">	
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="serie">Serie</label>
										<input id="serie" name="serie" type="text" class="form-control" placeholder="Número de serie" value="<?=$dataCell['serie']?>">
									  </div>
									  <div class="form-group">
										<label for="imei">IMEI</label>
										<input id="imei" name="imei" type="text" class="form-control"  placeholder="IMEI" value="<?=$dataCell['imei']?>">
									  </div>
									  <div class="form-group">
										<label for="numero">Número</label>
										<input id="numero" name="numero" type="text" class="form-control"  placeholder="Número de celular" value="<?=$dataCell['numero']?>">
									  </div>
									  <div class="form-group">
										<label for="marcacion_corta">Marcación corta</label>
										<input id="marcacion_corta" name="marcacion_corta" type="text" class="form-control"  placeholder="Marcación corta" value="<?=$dataCell['marcacion_corta']?>">
									  </div>
									  <div class="form-group">
										<label for="marca">Marca</label>
										<input id="marca" name="marca" type="text" class="form-control"  placeholder="Marca" value="<?=$dataCell['marca']?>">
									  </div>
									  <div class="form-group">
										<label for="valor">Valor</label>
										<input id="valor" name="valor" type="text" class="form-control"  placeholder="valor" value="<?=$dataCell['valor']?>">
									  </div>
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<label for="modelo">Modelo</label>
										<input id="modelo" name="modelo" type="text" class="form-control"  placeholder="Modelo" value="<?=$dataCell['modelo']?>">
									  </div>
									  <div class="form-group">
										<label for="so">Sistema operativo</label>
										<input id="so" name="so" type="text" class="form-control"  placeholder="Sistema operativo" value="<?=$dataCell['so']?>">
									  </div>
									  <div class="form-group">
										<label for="version">Versión del sistema</label>
										<input id="version" name="version" type="text" class="form-control"  placeholder="Ingrese la versión" value="<?=$dataCell['version']?>">
									  </div>
									  <div class="form-group">
										<label for="sim">Número de SIM Card</label>
										<input id="sim" name="sim" type="text" class="form-control"  placeholder="SIM" value="<?=$dataCell['sim']?>">
									  </div>
									  <div class="form-group">
										<label for="cat_status_celular">Estado</label>
										  <select class="form-control" id="cat_status_celular" name="cat_status_celular">
											<?php echo $selectEstado; ?>
										  </select>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" id="id_celular" name="id_celular" value="<?=$id_celular?>" />
						<button  onclick="editar_celular();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>