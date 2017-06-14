<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 760px;">
	<style>
	#costosAdicionales_wrapper > div:nth-child(1){display:none;}
	</style>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Costos adicionales al viaje
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
										<th></th>
									</tr>
								</thead>
							</table>
							<script>
								jQuery(function($) {
									$('#costosAdicionales').dataTable( {
										"fnDrawCallback": function( oSettings ) {
										  $('[data-rel=tooltip]').tooltip();
										  $('#total1').html('MXN ' + $('#costosAdicionales').DataTable().column( 1 ).data().sum());
										},
										"ordering": false,
										"processing": true,
										"serverSide": true,
										"pageLength": 100,

										"ajax": {
											"url": "operacion/costos_adicionales_show_get/" + <?=$id_viaje?>,
											"type": "POST"
										},
                                                                      "columnDefs": [
                                                                             {
                                                                                    "targets": 4,
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
				<br>
				<div class="modal-footer" id="footer_main">
					<div class="ca_flt_modal" id="total1"></div>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
					<script type="text/javascript">
						jQuery(function($) {
							autosize($('textarea[class*=autosize]'));

							$('.money').maskMoney({allowNegative:true});

						});
					</script>
			</div>
		</div>
	</div>
</div>
