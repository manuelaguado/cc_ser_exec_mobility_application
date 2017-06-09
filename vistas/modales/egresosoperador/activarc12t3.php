<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Activar el viaje para su proceso
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					Activa el viaje para su procesamiento, despues de activar esta opcion el viaje estará disponible en viajes del operador y podra se pausado o procesado desde
                                   viajes por operador.
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="activarc12t3_do(<?=$id_viaje?>)" class="btn btn-ar btn-success" type="button" id="add">Activar viaje</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
