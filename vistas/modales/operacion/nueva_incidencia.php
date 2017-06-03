<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 760px;">
	<style>
	#nuevaIncidencia_wrapper > div:nth-child(1){display:none;}
	</style>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Control de incidencias de viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="nuevaIncidencia" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Incidencia</th>
										<th>Capturó</th>
										<th>Fecha</th>
										<th>Acciones</th>
									</tr>
								</thead>
							</table>
							<script>
								jQuery(function($) {
									$('#nuevaIncidencia').dataTable( {
										"ordering": false,
										"processing": true,
										"serverSide": true,
										"pageLength": 100,

										"ajax": {
											"url": "operacion/nueva_incidencia_get/" + <?=$id_viaje?>,
											"type": "POST"
										}
									} );
								});
							</script>
						</div>
					</div>
				</div>

				<div class="row" id="add_field" style="display:none;">
				<style>
				#nueva_incidencia > div.modal-footer{
					margin: 12px;
				}
				</style>
					<form role="form" id="nueva_incidencia">
						<div class="panel panel-primary" style="border-color: #e0e0e0; margin: 12px;">
							<div class="panel-body">
								<div class="row">
									<div class="col-md-12">
										<label for="cat_incidencias">Incidencia</label>
										<select  class="form-control" id="cat_incidencias" name="cat_incidencias">
										<?php echo $cat_incidencias; ?>
										</select>
									</div>
								</div>
							</div>

						</div>

						<div id="error_alerta" > </div>


						<input type="hidden" id="id_viaje" name="id_viaje" value="<?=$id_viaje?>" />
						<div class="modal-footer">
							<button  class="btn btn-ar btn-success" type="button" onclick="nueva_incidencia_do();">Capturar</button>
							<button  onclick="close_nueva_incidencia_form();" class="btn btn-ar btn-default" type="button">Cerrar</button>
						</div>
					</form>
				</div>
				<br>
				<div class="modal-footer" id="footer_main">
					<button  class="btn btn-ar btn-success" type="button" id="add">Agregar incidencia</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>
