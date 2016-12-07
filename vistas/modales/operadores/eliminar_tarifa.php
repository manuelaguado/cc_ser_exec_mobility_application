<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Eliminar tarifa
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class="modal-footer">
					<button  onclick="tarifas_del_do(<?=$id_tarifa_operador?>);" class="btn btn-ar btn-success" type="button">Eliminar</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
				</div>
			</div>
		</div>
	</div>
</div>