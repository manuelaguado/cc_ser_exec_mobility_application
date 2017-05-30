<div class="modal fade" id="myModal2" tabindex="-1">
<style>
strong{
	font-weight: bold;
       color: #97b300;
       font-size: 1.3em;
       font-style: italic;
}
</style>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Confirme el seteo de Clave
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					La siguiente acción Setea el viaje:<br><br>

                                   <strong>N° <?=$id_viaje?>  >> <?=$clave?></strong>

                                   <br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="setClaveOk(<?=$id_viaje?>,'<?=$clave?>');" class="btn btn-ar btn-success" type="button" id="add">Si, setear clave</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
			</div>
		</div>
	</div>
</div>
