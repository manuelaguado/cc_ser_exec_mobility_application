<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Editar concepto de cobro
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="cobro_editado">
					<div class="panel panel-primary">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-12">
                                                               <div class="form-group">
                                                                    <label for="concepto">Concepto</label>
                                                                    <input id="concepto" name="concepto" type="text" class="form-control" placeholder="Concepto" value="<?=$dataegreso['concepto']?>">
                                                               </div>
                                                               <div class="form-group">
                                                                    <label for="monto">Monto</label>
                                                                    <input id="monto" name="monto" type="text" class="form-control text-field money" placeholder="Monto"  value="<?=$dataegreso['monto']?>" data-prefix="$ ">
                                                               </div>
									  <div class="form-group">
										<label for="cat_periodicidad">Periodicidad</label>
										  <select class="form-control" id="cat_periodicidad" name="cat_periodicidad">
											<?php echo $periodicidad; ?>
										  </select>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
                                          <input type="hidden" value="<?=$id_concepto?>" name="id_concepto" id="id_concepto">
						<button  onclick="editar_cobro_do();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
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
