<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Activar abandono
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente acción activa el botón para que el operador pueda abandonar el viaje,
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">					
				<button onclick="activar_abandono_do(<?=$id_viaje?>);" class="btn btn-ar btn-success" type="button" id="add">Activar botón</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>