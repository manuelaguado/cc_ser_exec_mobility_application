<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					<?=$user_movil['nombres']?> <?=$user_movil['apellido_paterno']?> <?=$user_movil['apellido_materno']?><br>
					<?=$user_movil['marca']?> <?=$user_movil['modelo']?> <?=$user_movil['color']?>
				</h4>
			</div>

			<section class="margin-bottom">
				<div class="row wow fadeInUp animated">
					<div class="col-xs-12">
						<table class="table table-striped table-bordered table-hover" id="simple-table">
							<thead>
								<tr>
									<th class="center" style="width:200px;">
										<label class="pos-rel">
											Asignar
										</label>
									</th>
									<th style="width:400px;">Base</th>
								</tr>
							</thead>

							<tbody>
							<?php
							foreach ($listaBases as $num => $base){
								$bases_operador_unidad = $modelo->getPermisos($operador_unidad,$base->id_base);
								if($bases_operador_unidad['permiso'] >= 1){$checked = 'checked';}else{$checked = '';}
								
							?>
								<tr>
									<td class="center">
										<span style="float: left; position:relative;">
											<input onchange='asignarBase(<?=$operador_unidad?>,<?=$base->id_base?>);' id="idenbase_<?php echo $base->id_base; ?>" name="idenbase_<?php echo $base->id_base; ?>" class="ace ace-switch ace-switch-5" type="checkbox" <?php echo $checked; ?>/>
											<span class="lbl"></span>
										</span>
									</td>
									<td><?=$base->descripcion?></td>
								</tr>
							<?php
							}
							?>
							</tbody>
						</table>
					</div>
				</div>		
			</section>
		</div>
	</div>
</div>