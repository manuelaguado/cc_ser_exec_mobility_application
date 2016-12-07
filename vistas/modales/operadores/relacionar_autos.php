<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 800px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Asignación de automoviles
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						<?php
						foreach ($operador as $num => $data){
							echo $data->nombres.' '.$data->apellido_paterno.' '.$data->apellido_materno;
						}
						?>
					</small>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<input type="hidden" name="id_operador" id="id_operador" value="<?php echo $id_operador; ?>">
				<div class="panel panel-primary">
					<div class="panel-body">			
						<div class="row">
							<div class="col-md-12">
								<table class="table table-striped table-bordered table-hover" id="simple-table">
									<thead>
										<tr>
											<th></th>
											<th class="center">
												<label class="pos-rel">
													Asignar
												</label>
											</th>
											<th>Marca</th>
											<th>Modelo</th>
											<th>Año</th>
											<th>Placas</th>
											<th>Motor</th>
											<th>Color</th>
										</tr>
									</thead>

									<tbody>
									<?php
									foreach ($unidades as $num => $auto){
										$operador_unidad = $model2->getPermisos($id_operador,$auto->id_unidad);
										if($operador_unidad['permiso'] >= 1){$checked = 'checked'; $hidden = ''; $id_operador_unidad = $operador_unidad['id_operador_unidad'];}else{$checked = ''; $hidden = 'hidden';$id_operador_unidad = '';}
										
									?>
										<tr>
											<td><?=$num?></td>
											<td class="center">
												<span style="float: left; position:relative;">
													<input onchange='asignarAutomovil(<?php echo $auto->id_unidad; ?>);' id="permission_<?php echo $auto->id_unidad; ?>" name="permission_<?php echo $auto->id_unidad; ?>" class="ace ace-switch ace-switch-5" type="checkbox" <?php echo $checked; ?>/>
													<span class="lbl"></span>
													<div id="relacionar_bases_<?php echo $auto->id_unidad; ?>" class="asign_base <?=$hidden?>"><i onclick="modal_asignar_bases(<?=$id_operador_unidad?>)" class="fa fa-building"></i></div>

												</span>
											</td>
											<td><?=utf8_encode($auto->marca)?></td>
											<td><?=utf8_encode($auto->modelo)?></td>
											<td><?=$auto->year?></td>
											<td><?=$auto->placas?></td>
											<td><?=$auto->motor?></td>
											<td><?=$auto->color?></td>
										</tr>
									<?php
									}
									?>
									</tbody>
								</table>								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>