<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
                                   <?php
                                   setlocale(LC_TIME,"es_MX.UTF-8");
                                   $dt_Ayer = date('m/d/Y', strtotime('-1 day')) ;
                                   $fecha = strftime("%A %e de %B", strtotime($dt_Ayer))
                                   ?>
					Procesar hasta el <?=$fecha?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					El proceso de emisión, bloquea todos los viajes hasta el <?=$fecha?> para proceder con su liquidación,
                                   cuando un viaje esta emitido se bloquea el viaje para costos adicionales y cambios de tarifa,
                                   los viajes que se necuentran para revision por ser tabulados con cambio de ruta y los viajes pausados no se procesan hasta cambiar de estado.
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="proceso249_do()" class="btn btn-ar btn-success" type="button" id="add">Procesar viajes</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
