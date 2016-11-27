<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Establecer tarifa
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="establecer_tarifa">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									  <div class="form-group">
										<label for="nombre">Nombre</label>
										<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre" value="">
									  </div>									  
								</div>
								<div class="col-md-12">
									  <div class="form-group">
										<label for="descripcion">Descripción</label>
										<textarea class="form-control text-field autosize-descripcion" id="descripcion" name="descripcion" placeholder="Descripción"></textarea>
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
					<input type="hidden" id="id_cliente" name="id_cliente" value="<?=$id_cliente?>">
					<div class="modal-footer">
						<button  class="btn btn-ar btn-success" type="button" onclick="procesar_tarifa();">Procesar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
		<script type="text/javascript">
			jQuery(function($) {
				autosize($('textarea[class*=autosize]'));
				
				$('.money').maskMoney();
				
			});
		</script>	
</div>
