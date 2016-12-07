<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Nueva Tarifa
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nueva_tarifa">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label for="nombre">Nombre</label>
										<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre">
									</div>
									<div class="form-group">
										<label for="costo_base">Costo Base</label>
										<input type="text" class="form-control text-field" id="costo_base" name="costo_base" placeholder="Base">
									</div>
									<div class="form-group">
										<label for="km_adicional">Kilometro adicional</label>
										<input type="text" class="form-control text-field" id="km_adicional" name="km_adicional" placeholder="Km Adicional">
									</div>									  
									<div class="form-group">
										<label for="cat_formapago">Forma de pago</label>
										<select class="form-control" id="cat_formapago" name="cat_formapago">
											<?php echo $formapago; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="id_operador" name="id_operador" value="<?php echo $id_operador; ?>">
					<div class="modal-footer">
						<button  onclick="nueva_tarifa_do();" class="btn btn-ar btn-success" type="button">Agregar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>