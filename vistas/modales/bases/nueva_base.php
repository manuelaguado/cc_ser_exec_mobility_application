<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Nueva Base
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nueva_base">	
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									  <div class="form-group">
										<label for="descripcion">Descripción</label>
										<input id="descripcion" name="descripcion" type="text" class="form-control" placeholder="Descripción" value="">
									  </div>
									  <div class="form-group">
										<label for="ubicacion">Ubicación</label>
										<input id="ubicacion" name="ubicacion" type="text" class="form-control"  placeholder="Ubicación" value="">
									  </div>
									  <div class="form-group">
										<label for="clave">Clave</label>
										<input id="clave" name="clave" type="text" class="form-control"  placeholder="Clave" value="">
									  </div>
									  <div class="form-group">
										<label for="geocerca">Geocerca</label>
										<textarea rows="5" id="geocerca" name="geocerca" type="text" class="form-control"  placeholder="Geocerca" value=""></textarea>
									  </div>
									  <div class="form-group">
										<label for="cat_tipobase">Tipo</label>
										  <select class="form-control" id="cat_tipobase" name="cat_tipobase">
											<?php echo $selecTipos; ?>
										  </select>
									  </div>
									  <div class="row">
										  <div class="form-group col-md-6">
											<label for="latitud">Latitid</label>
											  <input id="latitud" name="latitud" type="text" class="form-control"  placeholder="Latitud" value="">
										  </div>
										  <div class="form-group col-md-6">
											<label for="longitud">Longitud</label>
											  <input id="longitud" name="longitud" type="text" class="form-control"  placeholder="Longitud" value="">
										  </div>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button  onclick="nueva_base();" class="btn btn-ar btn-success" type="button">Agregar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>