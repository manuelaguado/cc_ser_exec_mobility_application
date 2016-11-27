<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Nueva Unidad
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="editar_vehiculo">	
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									  <div class="form-group">
										<label for="id_marca">Marca</label>
										  <select  onchange="getModelos()" class="form-control" id="id_marca" name="id_marca">
											<?php echo $marcas; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="id_modelo">Modelo</label>
										  <select  class="form-control" id="id_modelo" name="id_modelo">
											<?php echo $modelos; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="cat_status_unidad">Estado</label>
										  <select  class="form-control" id="cat_status_unidad" name="cat_status_unidad">
											<?php echo $stat_unit; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="year">Año</label>
										<input id="year" name="year" type="text" class="form-control" placeholder="Año" value="<?=$vehiculo['year']?>">
									  </div>
									  <div class="form-group">
										<label for="placas">Placas</label>
										<input id="placas" name="placas" type="text" class="form-control"  placeholder="Placas" value="<?=$vehiculo['placas']?>">
									  </div>
									  <div class="form-group">
										<label for="motor">Motor</label>
										<input id="motor" name="motor" type="text" class="form-control"  placeholder="Motor" value="<?=$vehiculo['motor']?>">
									  </div>
									  <div class="form-group">
										<label for="color">Color</label>
										<input id="color" name="color" type="text" class="form-control"  placeholder="Color" value="<?=$vehiculo['color']?>">
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
					<input type="hidden" name="id_unidad" id="id_unidad" value="<?=$vehiculo['id_unidad']?>">
						<button  onclick="editar_vehiculo();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>