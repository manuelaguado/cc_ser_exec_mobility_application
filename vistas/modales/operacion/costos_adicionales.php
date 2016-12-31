<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 760px;">
	<style>
	#costosAdicionales_wrapper > div:nth-child(1){display:none;}
	</style>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Agregar costos adicionales al viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="costosAdicionales" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Concepto</th>
										<th>Costo</th>
										<th>Capturó</th>
										<th>Fecha</th>
										<th>Acciones</th>
									</tr>
								</thead>									
							</table>					
							<script>
								jQuery(function($) {							
									$('#costosAdicionales').dataTable( {
										"fnDrawCallback": function( oSettings ) {
										  $('[data-rel=tooltip]').tooltip();
										  $('#total1').html('MXN ' + $('#costosAdicionales').DataTable().column( 1 ).data().sum());
										  $('#total2').html('MXN ' + $('#costosAdicionales').DataTable().column( 1 ).data().sum());
										},
										"ordering": false,
										"processing": true,
										"serverSide": true,
										"pageLength": 100,

										"ajax": {
											"url": "operacion/costos_adicionales_get/" + <?=$id_viaje?>,
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
				#costos_adicionales > div.modal-footer{
					margin: 12px;
				}
				</style>
					<form role="form" id="costos_adicionales">					
						<div class="panel panel-primary" style="border-color: #e0e0e0; margin: 12px;">
							<div class="panel-body">			
								<div class="row">
									<div class="col-md-6">
										<label for="cat_concepto">Concepto</label>
										<select  class="form-control" id="cat_concepto" name="cat_concepto">
										<?php echo $cat_concepto; ?>
										</select>
									</div>									
									<div class="col-md-6">
										  <div class="form-group">
											<label for="costo">Costo base</label>
											<input type="text" class="form-control text-field money" id="costo" name="costo" placeholder="Costo" data-prefix="$ ">
										  </div>
									</div>
								</div>
							</div>
							
						</div>
						
						<div id="error_alerta" > </div>
						
						
						<input type="hidden" id="id_viaje" name="id_viaje" value="<?=$id_viaje?>" />
						<div class="modal-footer">
							<div class="ca_flt_modal" id="total2"></div>
							<button  class="btn btn-ar btn-success" type="button" onclick="costos_adicionales_do();">Capturar</button>
							<button  onclick="close_costos_form();" class="btn btn-ar btn-default" type="button">Cerrar</button>
						</div>
					</form>				
				</div>
				<br>
				<div class="modal-footer" id="footer_main">
					<div class="ca_flt_modal" id="total1"></div>
					<button  class="btn btn-ar btn-success" type="button" id="add">Agregar costo adicional</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
					<script type="text/javascript">
						jQuery(function($) {
							autosize($('textarea[class*=autosize]'));
							
							$('.money').maskMoney();
							
						});
					</script>					
			</div>
		</div>
	</div>
</div>