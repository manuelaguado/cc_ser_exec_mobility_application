<div class="modal fade" id="pulledApart" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Apartados
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="contentPullApart" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>
										<th></th>
										<th>NUM</th>
										<th>Operador</th>
										<th>Mes</th>
										<th>Año</th>
										<th>Status</th>
										<th></th>
									</tr>
									<tbody>
									<?php
									if(count($operadores)>0){
										foreach ($operadores as $operador) {
											if($operador['num'] < $actual){
												$turn = '<i class="fa fa-times fa-2x orange" aria-hidden="true"></i>';
											}else if($actual == $operador['num']){
												echo "<script>$('#id_operador_turno').val('".$operador['id_operador']."');</script>";
												$turn = '<i class="fa fa-bell fa-2x green icon-animated-bell" aria-hidden="true"></i>';
											}else{
												$turn = '';
											}
											echo "
											<tr>
												<td>".$turn."</td>
												<td>".$operador['num']."</td>
												<td>".$operador['nombre']."</td>
												<td>".$operador['mensual']."</td>
												<td>".$operador['anual']."</td>
												<td>".$operador['status']."</td>
												<td>
											";
											if($operador['multi'] == 1){
												echo "
													<button onclick='asignarDirecto(".$operador['id_operador_unidad']."); setIdens(".$operador['num'].",".$operador['id_operador'].",\"".$operador['nombre']."\");' class='btn btn-xs btn-success'>
															Asignar
														<i class='ace-icon fa fa-arrow-right icon-on-right'></i>
													</button>
												";
											}else{
												echo "
													<button onclick='elegirVehiculo(".$operador['id_operador'].");' class='btn btn-xs btn-warning'>
															Elegir
														<i class='ace-icon fa fa-arrow-right icon-on-right'></i>
													</button>
												";
											}
											echo "
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
				<script>
					$('#turno_apartado').val('<?=$actual?>');
				</script>				
			</div>
		</div>
	</div>
</div>