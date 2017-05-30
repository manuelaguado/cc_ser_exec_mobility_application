<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Seleccion de claves para el viaje N° <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="row">
					<div class="col-md-12 column">
						<div class="table-responsive">
							<table id="tarifas" class="table table-striped table-bordered table-hover">
								<thead>
									<tr>

										<th>Clave</th>
										<th>Descripción</th>
										<th>Acciones</th>
									</tr>
									<tbody>
									<?php
									if(count($claves)>0){
										foreach ($claves as $row) {
                                                                             $acciones = '';
											echo '
												<tr>
													<td>'.$row["clave"].'</td>
													<td>'.$row["descripcion"].'</td>
													<td><button onclick=\'setClaveNum("'.$row["clave"].'",'.$id_viaje.');\' class="btn btn-ar btn-success" type="button">SETEAR '.$row["clave"].'</button></td>
												</tr>
											';
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
