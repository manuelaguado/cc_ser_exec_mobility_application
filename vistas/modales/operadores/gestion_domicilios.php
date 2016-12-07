<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Domicilios del operador
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="domicilios" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										
										<th>Domicilio</th>
										<th>Tipo</th>
										<th>Status</th>
										<th>Acciones</th>
									</tr>
									<tbody>
									<?php
									if(count($domicilios)>0){
										foreach ($domicilios as $row) {
											echo "
											<tr>
												
												<td>".$row->domicilio."</td>
												<td>".utf8_encode($row->etiqueta1)."</td>
												<td>".utf8_encode($row->etiqueta2)."</td>
												<td>
												<center>
											";
												if(($this->tiene_permiso('Operadores|del_domicilio')) && ($row->etiqueta2 != 'Eliminado')){
													echo "
														<a title='Eliminar' onclick=\"eliminar_domicilio(".$row->id_domicilio.")\" href=\"javascript:void(0)\" class='red'>
															<i class='ace-icon fa fa-trash-o bigger-130'></i>
														</a>
													";
												}
												if(($this->tiene_permiso('Operadores|inactivar_domicilio')) && ($row->etiqueta2 != 'Inactivo')&& ($row->etiqueta2 != 'Eliminado')){
													echo "
														<a title='In-Activar' onclick=\"inactivar_domicilio(".$row->id_domicilio.")\" href=\"javascript:void(0)\" class='red'>
															<i class='ace-icon fa fa-ban bigger-130'></i>
														</a>
													";
												}
												if(($this->tiene_permiso('Operadores|activar_domicilio')) && ($row->etiqueta2 != 'Activo')){
													echo "
														<a title='Activar' onclick=\"activar_domicilio(".$row->id_domicilio.")\" href=\"javascript:void(0)\" class='green'>
															<i class='ace-icon fa fa-plug bigger-130'></i>
														</a>
													";
												}
											echo "
												</center>
												</td>
											</tr>
											";
										}
									}
									?>
									</tbody>
								</thead>
							</table>
						</div>
					</div>
				</div>
				<?php
				if($this->tiene_permiso('Operadores|add_domicilio')){
				?>					
				<div class="row" id="add_field" style="display:none;">
					<form id="nuevo_dom">
						<div class="col-md-12 column">
							<div class="panel-body">
								<div class="form-group">
									<div class="row">
										<div class="form-group">
											<label for="domicilio">Nuevo domicilio</label>
											<textarea placeholder="Domicilio" id="domicilio" name="domicilio" class="form-control"></textarea>
										</div>
										<div class="row">
											<div class="form-group col-md-6 column">
												<label for="cat_tipodomicilio">Tipo de domicilio</label>
												<select  class="form-control" id="cat_tipodomicilio" name="cat_tipodomicilio">
												<?php echo $tipodom; ?>
												</select>
											</div>
											<div class="form-group  col-md-6 column">
												<label for="cat_statusdomicilio">Status del domicilio</label>
												<select  class="form-control" id="cat_statusdomicilio" name="cat_statusdomicilio">
												<?php echo $statusdom; ?>
												</select>
											</div>	
										</div>
										<input type="hidden" id="id_operador" name="id_operador" value="<?=$id_operador?>"/>
										<div class="col-md-2 column">
											<button class="btn btn-ar btn-primary" type="button" onclick="graba_dom();">Agregar</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<?php
				}
				?>
				<div class="modal-footer">
					<?php
					if($this->tiene_permiso('Operadores|add_domicilio')){
					?>					
					<button  class="btn btn-ar btn-success" type="button" id="add">Agregar</button>
					<?php
					}
					?>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>				
			</div>
		</div>
	</div>
</div>