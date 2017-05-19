<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Nuevo equipo
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nuevo_equipo">	
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="serie">Serie</label>
										<input id="serie" name="serie" type="text" class="form-control" placeholder="Número de serie" value="">
									  </div>
									  <div class="form-group">
										<label for="imei">IMEI</label>
										<input id="imei" name="imei" type="text" class="form-control"  placeholder="IMEI" value="">
									  </div>
									  <div class="form-group">
										<label for="numero">Número</label>
										<input id="numero" name="numero" type="text" class="form-control"  placeholder="Número de celular" value="">
									  </div>
									  <div class="form-group">
										<label for="marcacion_corta">Marcación corta</label>
										<input id="marcacion_corta" name="marcacion_corta" type="text" class="form-control"  placeholder="Marcación corta" value="">
									  </div>
									  <div class="form-group">
										<label for="marca">Marca</label>
										<input id="marca" name="marca" type="text" class="form-control"  placeholder="Marca" value="">
									  </div>
									  <div class="form-group">
										<label for="valor">Valor</label>
										<input id="valor" name="valor" type="text" class="form-control"  placeholder="valor" value="">
									  </div>
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<label for="modelo">Modelo</label>
										<input id="modelo" name="modelo" type="text" class="form-control"  placeholder="Modelo" value="">
									  </div>
									  <div class="form-group">
										<label for="so">Sistema operativo</label>
										<input id="so" name="so" type="text" class="form-control"  placeholder="Sistema operativo" value="">
									  </div>
									  <div class="form-group">
										<label for="version">Versión del sistema</label>
										<input id="version" name="version" type="text" class="form-control"  placeholder="Ingrese la versión" value="">
									  </div>
									  <div class="form-group">
										<label for="sim">Número de SIM Card</label>
										<input id="sim" name="sim" type="text" class="form-control"  placeholder="SIM" value="">
									  </div>
									  <div class="form-group">
										<label for="sim">&nbsp;</label>
										<div class="checkbox">
											<label class="block">
												<input name="externo" id="externo" type="checkbox" class="ace input-lg" value="true">
												<span class="lbl bigger-120"> ¿Externo?</span>
											</label>
										</div>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<input type="hidden" name="cat_status_celular" id="cat_status_celular" value="30">
						<button  onclick="nuevo_celular();" class="btn btn-ar btn-success" type="button">Agregar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>