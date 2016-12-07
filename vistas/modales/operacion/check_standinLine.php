<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Lista de verificación
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nuevo_mensaje">
					<div id="msj_info">

					
						<div class="row">
							<div class="col-xs-12">
								<ul class="list-unstyled spaced">
									<li>
									<?php
									$ok = 'ace-icon fa fa-check bigger-110 green';
									$bad = 'ace-icon fa fa-times bigger-110 red';
									?>
										<?php $session=($session)?$ok:$bad; ?>
										<i class="<?=$session?>"></i>
										El operador tiene una sesión activa y está logueado
									</li>
									<li>
										<?php $connected=($connected)?$ok:$bad; ?>
										<i class="<?=$connected?>"></i>
										Los websockets estan activos abiertos y en espera de órdenes
									</li>
									<li>
										<?php $engeocercaa=($engeocerca1)?$ok:$bad; ?>
										<i class="<?=$engeocercaa?>"></i>
										¿La ubicación física es correcta y está dentro de la geocerca 1?
									</li>
									<li>
										<?php $engeocercab=($engeocerca2)?$ok:$bad; ?>
										<i class="<?=$engeocercab?>"></i>
										¿La ubicación física es correcta y está dentro de la geocerca 2?
									</li>
									<li>
										<?php $intime=($intime)?$ok:$bad; ?>
										<i class="<?=$intime?>"></i>
										El ultimo paquete de geolocalización es vigente
									</li>
									<li>
										<?php $estaEnC1=($estaEnC1 == 'C1')?$ok:$bad; ?>
										<i class="<?=$estaEnC1?>"></i>
										El estado del operador es C1
									</li>
									<li>
										<?php $solicitud=($solicitud)?$ok:$bad; ?>
										<i class="<?=$solicitud?>"></i>
										Existe una solicitud activa F14 por parte del operador
									</li><br>
									
									<?php if($encordon1){ ?>
									<li class="text-warning bigger-110 green">
										<i class="ace-icon fa fa-exclamation-triangle"></i>
										El operador se encuentra en el cordón 1 y tiene asignado un numero en la fila.
										
										<?php echo (!$engeocerca1)?'
										<div style="color:red;">
										<i class="ace-icon fa fa-exclamation-triangle"></i>
										El operador esta fuera de la geocerca ¡Verifique la situación!
										</div>':'';?>
										
									</li>
									<?php } ?>
									
									<?php if($encordon2){ ?>
									<li class="text-warning bigger-110 orange">
										<i class="ace-icon fa fa-exclamation-triangle"></i>
										El operador se encuentra en el cordón 2 y tiene asignado un numero en la fila.
										
										<?php echo (!$engeocerca2)?'
										<div style="color:red;">
										<i class="ace-icon fa fa-exclamation-triangle"></i>
										El operador esta fuera de la geocerca ¡Verifique la situación!
										</div>':'';?>
										
									</li>
									<?php } ?>
								</ul>
							</div>
						</div>
					
					
					</div>

					<div class="modal-footer">
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>