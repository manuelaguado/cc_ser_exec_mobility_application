<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Establecer comision para N° Económico <?=$valores['id_numeq']?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="editar_comision">
					<div class="panel panel-primary">
						<div class="panel-body">
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="comision">Comisión</label>
										  <input type="text" value="<?=$comision?>" id="comision" name="comision">
									  </div>
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<?php
										if($valores['id_numeq']){
										?>
										       <button onclick="setComisionDeault('<?=$id_operador?>')" data-toggle="button" class="btn btn-sm btn" type="button">Establecer comisión predeterminada</button>
									       <?php
										}else{
										       echo "<br><br>";
										}
										?>
									  </div>
								</div>
							</div>
						</div>
					</div>
					<input type="hidden" id="id_operador" name="id_operador" value="<?php echo $id_operador; ?>">
					<input type="hidden" id="old_comision" name="old_comision" value="<?php echo $comision; ?>">

                                   <div class="modal-footer">
						<button  onclick="comision_operador_do();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
