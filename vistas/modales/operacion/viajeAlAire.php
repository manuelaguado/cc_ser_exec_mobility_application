<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Otorgar viaje a operadores en Tiempo a la Base
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="domicilios" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										
										<th>NUM</th>
										<th>Operador</th>
										<th>Unidad</th>
										<th>Acciones</th>
									</tr>
									<tbody>
									<?php
									if(count($operadores)>0){
										foreach ($operadores as $operador) {
											echo "
											<tr>
												<td>".$operador['numeq']."</td>
												<td>".utf8_encode($operador['nombre'])."</td>
												<td>".$operador['marca'].' '.$operador['modelo'].' '.$operador['color']."</td>
												<td>
													<button onclick='asignarViajeAlAire(".$operador['id_operador_unidad'].",".$operador['id_operador'].",".$id_viaje.")' class='btn btn-xs btn-success'>
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