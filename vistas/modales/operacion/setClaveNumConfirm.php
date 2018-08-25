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

					<div class="col-sm-6">
						La siguiente acción Setea el viaje: a las:
					</div>

					<div class="col-sm-6">
						<div class="input-group">
							<input id="fecha_horax" name="fecha_hora" type="text" class="form-control" />
							<input id="automan" name="automan" type="hidden" value="auto" />
							<span class="input-group-addon">
								<i class="fa fa-clock-o bigger-110"></i>
							</span>
						</div>
					</div>


					<script>
					$( document ).ready(function() {
					var time = new Date();
						$('#fecha_horax').datetimepicker({
									 minDate: moment().subtract(1, 'days').millisecond(0).second(0).minute(0).hour(0),
									 defaultDate: moment(time.toMysqlFormat()),
									 format: 'YYYY-MM-DD HH:mm',
									 locale: 'es',
									 icons: {
													time: 'fa fa-clock-o',
													date: 'fa fa-calendar',
													up: 'fa fa-chevron-up',
													down: 'fa fa-chevron-down',
													previous: 'fa fa-chevron-left',
													next: 'fa fa-chevron-right',
													today: 'fa fa-arrows ',
													clear: 'fa fa-trash',
													close: 'fa fa-times'
									 }
						}).on(ace.click_event, function(){
									 $("#automan").val('man');
						});
					});
					</script>


                                   <br><br><strong>N° <?=$id_viaje?>  >> <?=$clave?></strong>

                                   <br><br>¿Está seguro de continuar con esta acción?
			</div>
			<div class="modal-footer">
				<button onclick="setClaveOk(<?=$id_viaje?>,'<?=$clave?>');" class="btn btn-ar btn-success" type="button" id="add">Si, setear clave</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cancelar</button>
			</div>
		</div>
	</div>
</div>
