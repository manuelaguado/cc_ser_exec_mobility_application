<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Vehículos de <?=$vehiculos[0]['nombre']?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="vehiculos" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th>Marca</th>
										<th>Modelo</th>
										<th>Año</th>
										<th>Placas</th>
										<th>Color</th>
										<th></th>
									</tr>
									<tbody>
									<?php
									if(count($vehiculos)>0){
										foreach ($vehiculos as $vehiculo) {
											echo "
											<tr>
												<td>".$vehiculo['marca']."</td>
												<td>".$vehiculo['modelo']."</td>
												<td>".$vehiculo['year']."</td>
												<td>".$vehiculo['placas']."</td>
												<td>".$vehiculo['color']."</td>
												<td>
													<button onclick='asignarDirecto(".$vehiculo['id_operador_unidad']."); setIdens(".$vehiculo['num'].",".$id_operador.",\"".$vehiculo['nombre']."\");' class='btn btn-xs btn-success'>
															Asignar
														<i class='ace-icon fa fa-arrow-right icon-on-right'></i>
													</button>
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
			</div>
		</div>
	</div>
</div>