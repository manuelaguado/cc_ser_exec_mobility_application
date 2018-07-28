<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Reprocesar C9 del viaje: <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente accion reprocesa todos los datos del viaje por C9
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="reprocesar_c9_do(<?=$id_viaje?>);" class="btn btn-ar btn-success" type="button" id="add">Reprocesar C9</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
