<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Establecer número económico
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="editar_numero_eq">
					<div class="panel panel-primary">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="id_numeq">Número Económico</label>
										  <select class="form-control" id="id_numeq" name="id_numeq">
											<?php echo $selectNumEq; ?>
										  </select>
									  </div>
								</div>
								<div class="col-md-6">
									  <div class="form-group" id="selectNumEq">
										<label>&nbsp;</label><br>
										<?php
										if($valores['id_numeq']){
										?>
										<button onclick="liberar_numero('<?=$valores['id_numeq']?>')" data-toggle="button" class="btn btn-sm btn" type="button">Liberar número <?=$valores['id_numeq']?></button>
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
					<input type="hidden" id="num_eq_old" name="num_eq_old" value="<?php echo $valores['id_numeq']; ?>">
					<div class="modal-footer">
						<button  onclick="numero_economico_do();" class="btn btn-ar btn-success" type="button">Editar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>