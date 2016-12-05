<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 960px;">
	<style>
	#tarifas_wrapper > div:nth-child(1){display:none;}
	</style>	
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Establecer tarifa
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="tarifas" class="display table table-striped" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Nombre</th>
										<th>descripcion</th>
										<th>$ Base</th>
										<th>$ km +</th>
										<th>Inicia</th>
										<th>Finvigencia</th>
										<th>Estado</th>
										<th>Tipo</th>
										<th>Tabulado</th>
									</tr>
								</thead>									
							</table>					
							<script>
								jQuery(function($) {							
									$('#tarifas').dataTable( {
										"fnDrawCallback": function( oSettings ) {
										  $('[data-rel=tooltip]').tooltip();
										},
										"ordering": false,
										"processing": true,
										"serverSide": true,
										"pageLength": 100,

										"ajax": {
											"url": "clientes/modal_establecer_tarifa_get/" + <?=$id_cliente?>,
											"type": "POST"
										},
										"columnDefs": [
											{
												"targets": 1,
												"visible": false,
												"searchable":false
											},
											{
												"targets": 5,
												"visible": false,
												"searchable":false
											}
										]
									} );
								});
							</script>
						</div>
					</div>
				</div>
				
				<div class="row" id="add_field" style="display:none;">
				<style>
				#establecer_tarifa > div.modal-footer{
					margin: 12px;
				}
				</style>
					<form role="form" id="establecer_tarifa">					
						<div class="panel panel-primary" style="border-color: #e0e0e0; margin: 12px;">
							<div class="panel-body">			
								<div class="row">
									<div class="col-md-6">
										  <div class="form-group">
											<label for="nombre">Nombre</label>
											<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre" value="">
										  </div>									  
									</div>
									<div class="form-group col-md-6 column">
										<label for="cat_tipo_tarifa">Tipo</label>
										<select  class="form-control" id="cat_tipo_tarifa" name="cat_tipo_tarifa">
										<?php echo utf8_encode($cat_tipo_tarifa); ?>
										</select>
									</div>									
									<div class="col-md-10">
										  <div class="form-group">
											<label for="descripcion">Descripción</label>
											<textarea class="form-control text-field autosize-descripcion" id="descripcion" name="descripcion" placeholder="Descripción"></textarea>
										  </div>									  
									</div>
									<div class="col-md-2">
										  <div class="form-group">
											<label for="tabulado">¿Tabulado?</label>
											<br>
											<input onchange="switch_tabular()" id="tabulado" name="tabulado" class="ace ace-switch ace-switch-5" type="checkbox"/>
											<span class="lbl"></span>

										  </div>									  
									</div>
									<div class="col-md-6">
										  <div class="form-group">
											<label for="costo_base">Costo base</label>
											<input type="text" class="form-control text-field money" id="costo_base" name="costo_base" placeholder="Costo base" data-prefix="$ ">
										  </div>
									</div>
									<div class="col-md-6">
										  <div class="form-group">
											<label for="km_adicional">Kilómetro adicional</label>
											<input type="text" class="form-control text-field money" id="km_adicional" name="km_adicional" placeholder="Kilómetro adicional" data-prefix="$ ">
										  </div>									  
									</div>
								</div>
							</div>
						</div>
						<div id="error_alerta" > </div>
						<input type="hidden" id="tabular" name="tabular" value="0">
						<input type="hidden" id="id_cliente" name="id_cliente" value="<?=$id_cliente?>">
						<div class="modal-footer">
							<button  class="btn btn-ar btn-success" type="button" onclick="procesar_tarifa();">Procesar</button>
							<button  onclick="close_tarifa_form();" class="btn btn-ar btn-default" type="button">Cancelar</button>
						</div>
					</form>
					<script type="text/javascript">
						jQuery(function($) {
							autosize($('textarea[class*=autosize]'));
							
							$('.money').maskMoney();
							
						});
					</script>					
				</div>

				<div class="modal-footer" id="footer_main">					
					<button  class="btn btn-ar btn-success" type="button" id="add">Agregar</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>				
			</div>
		</div>
	</div>
</div>