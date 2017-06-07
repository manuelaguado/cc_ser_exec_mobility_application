<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Tarifas vigentes cambio post viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="tarifas" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Nombre</th>
										<th>$ Base</th>
										<th>$ km +</th>
										<th>Tipo</th>
										<th>Tabulado</th>
										<th></th>
									</tr>
									<tbody>
									<?php
									if(count($tarifas)>0){
										foreach ($tarifas as $row) {
											if($row->tabulado == 1){$tab = "SI";}else{$tab = "NO";}
											if($row->id_tarifa_cliente == $current_tarifa){
												$select = '
												<a href="javascript:;" onclick="cambiar_tarifa_do_post('.$row->id_tarifa_cliente.','.$id_viaje.');" id="fare_'.$row->id_tarifa_cliente.'">
													<i class="fa fa-check-square-o bigger-150 green" aria-hidden="true"></i>
												</a>';
											}else{
												$select = '
												<a href="javascript:;" onclick="cambiar_tarifa_do_post('.$row->id_tarifa_cliente.','.$id_viaje.');" id="fare_'.$row->id_tarifa_cliente.'">
													<i class="fa fa-square-o bigger-150"  aria-hidden="true"></i>
												</a>';
											}
											echo "
												<tr>
													<td><a title='".$row->descripcion."'>".$row->nombre."</a></td>
													<td>".$row->costo_base."</td>
													<td>".$row->km_adicional."</td>
													<td>".$row->tipo."</td>
													<td>".$tab."</td>
													<td>".$select."</td>
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
				<div class="modal-footer" id="footer_main">
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
</div>
