<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Enviar operador al final de la fila
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente acción enviará al operador al final de la fila,
					<br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">					
				<button onclick="aut_f14(<?=$id_operador_unidad?>,<?=$id_base?>);" class="btn btn-ar btn-success" type="button" id="add">Enviar al final</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>