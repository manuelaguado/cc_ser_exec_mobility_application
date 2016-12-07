<div class="modal fade" data-backdrop="" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Activar salida por sitio F13
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente accion activa la salida por sitio para el operador,
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">					
				<button onclick="activar_f13_do(<?=$id_operador_unidad?>);" class="btn btn-ar btn-success" type="button" id="add">Activar F13</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>