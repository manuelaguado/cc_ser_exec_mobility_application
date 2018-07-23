<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Seleccion de claves para el viaje N° <?=$id_viaje?> CVE: <?=$currentCve['clave']?>
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

											$outprint =
											'<tr>
												<td>'.$row["clave"].'</td><td>'.$row["descripcion"].'</td><td>
													<button onclick=\'setClaveNum("'.$row["clave"].'",'.$id_viaje.');\' class="btn btn-ar btn-success" type="button">
														SETEAR '.$row["clave"].'
													</button>
												</td>
											</tr>';
											switch ($currentCve['clave']){

												case 'A10':
													if(
															($row["clave"] == 'A11')||
															($row["clave"] == 'A14')
														){
														echo $outprint;
													}
												break;
												case 'A14':
												/*FIN DE PROCESO*/
												break;
												case 'A11':
													if(
															($row["clave"] == 'C8')||
															($row["clave"] == 'A14')
														){
														echo $outprint;
													}
												break;
												case 'C8':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'A2')||
															($row["clave"] == 'C9')||
															($row["clave"] == 'C10')||
															($row["clave"] == 'C12')
														){
														echo $outprint;
													}
												break;
												case 'A2':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'C9')
														){
														echo $outprint;
													}
												break;
												case 'C10':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'C11')
														){
														echo $outprint;
													}
												break;
												case 'C11':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'C9')||
															($row["clave"] == 'C10')||
															($row["clave"] == 'C12')
														){
														echo $outprint;
													}
												break;
												case 'C12':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'C9')||
															($row["clave"] == 'C10')
														){
														echo $outprint;
													}
												break;
												case 'C9':
												/*FIN DE PROCESO*/
												break;
												case 'F13':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'C8')
														){
														echo $outprint;
													}
												break;
												case 'F15':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'A11')
														){
														echo $outprint;
													}
												break;
												case 'T1':
													if(
															($row["clave"] == 'A14')||
															($row["clave"] == 'A11')
														){
														echo $outprint;
													}
												break;

												case 'T2':
												/*NO PREVISTO*/
												break;


												case 'C14':
												/*NO PREVISTO*/
												break;


												default:
												break;
											}
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
