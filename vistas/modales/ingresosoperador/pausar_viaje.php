<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Intercambiar el el procesamiento del viaje n° <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente acción cambia el estado para procesamiento evitando o permitiendo su pago mientras esta en pausa,
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="pausar_viaje_do(<?=$id_viaje?>);" class="btn btn-ar btn-success" type="button" id="add">Cambiar estado del viaje</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
