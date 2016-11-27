<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Enviar mensaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form role="form" id="nuevo_mensaje">
					<div id="msj_info">
						<input type="hidden" id="id_operador" name="id_operador" value="<?php echo $id_operador; ?>">
						<textarea class="mensaje_box" id="mensaje" name="mensaje"></textarea>
					</div>
					<!--<div id="msj_verify" class="msj_verify" style="display:none">
						<i class="fa fa-refresh fa-spin"></i>
					</div>
					<div id="msj_delivery" class="msj_delivery" style="display:none">
						<i class="fa fa-check"></i> Entregado
					</div>-->
					<div class="modal-footer">
						<button onclick="guardar_mensaje();" class="btn btn-ar btn-success" type="button" id="add">Enviar</button>
						<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>