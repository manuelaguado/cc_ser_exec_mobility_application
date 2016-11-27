<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" id="nuevo_mensaje">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
					<h4 class="modal-title" id="myModalLabel">
						Información adicional para el destino
					</h4>
				</div>
				<div class="modal-body" id="modal_content">


					<span class="input-icon">
						<input type="text" id="calle_destino" name="calle_destino" placeholder="Calle" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-road blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="num_ext_destino" name="num_ext_destino" placeholder="Número exterior" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-sign-out blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="num_int_destino" name="num_int_destino" placeholder="Número interior" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-sign-in blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="telefono_destino" name="telefono_destino" placeholder="Número telefónico" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-phone blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="celular_destino" name="celular_destino" placeholder="Número celular" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-mobile blue"></i>
					</span>


				</div>
				<div class="modal-footer">
					<button onclick="set_extra_destino();" class="btn btn-ar btn-success" type="button">Establecer</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
				<script>
					jQuery(function($) {
						$('#calle_destino').val($('#destino_calle').val());
						$('#num_ext_destino').val($('#destino_num_ext').val());
						$('#num_int_destino').val($('#destino_num_int').val());
						$('#telefono_destino').val($('#destino_telefono').val());
						$('#celular_destino').val($('#destino_celular').val());
					});
					function set_extra_destino(){
						$('#destino_calle').val($('#calle_destino').val());
						$('#destino_num_ext').val($('#num_ext_destino').val());
						$('#destino_num_int').val($('#num_int_destino').val());
						$('#destino_telefono').val($('#telefono_destino').val());
						$('#destino_celular').val($('#celular_destino').val());
						$('#myModal').remove();
						$('.modal-backdrop').remove();
						$('#destino_hide_ok').removeClass('hide');
					}
				</script>					
			</form>			
		</div>	
	</div>		
</div>