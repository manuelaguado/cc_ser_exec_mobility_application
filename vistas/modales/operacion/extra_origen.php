<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<form role="form" id="nuevo_mensaje">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
					<h4 class="modal-title" id="myModalLabel">
						Información adicional para el origen
					</h4>
				</div>
				<div class="modal-body" id="modal_content">


					<span class="input-icon">
						<input type="text" id="calle_origen" name="calle_origen" placeholder="Calle" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-road blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="num_ext_origen" name="num_ext_origen" placeholder="Número exterior" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-sign-out blue"></i>
					</span>
					
					<span class="input-icon">
						<input type="text" id="num_int_origen" name="num_int_origen" placeholder="Número interior" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-sign-in blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="telefono_origen" name="telefono_origen" placeholder="Número telefónico" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-phone blue"></i>
					</span>

					<span class="input-icon">
						<input type="text" id="celular_origen" name="celular_origen" placeholder="Número celular" class="col-xs-10 col-sm-12" />
						<i class="ace-icon fa fa-mobile blue"></i>
					</span>


				</div>
				<div class="modal-footer">
					<button onclick="set_extra_origen();" class="btn btn-ar btn-success" type="button">Establecer</button>
					<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
				</div>
				<script>
					jQuery(function($) {
						$('#calle_origen').val($('#origen_calle').val());
						$('#num_ext_origen').val($('#origen_num_ext').val());
						$('#num_int_origen').val($('#origen_num_int').val());
						$('#telefono_origen').val($('#origen_telefono').val());
						$('#celular_origen').val($('#origen_celular').val());
					});
					function set_extra_origen(){
						$('#origen_calle').val($('#calle_origen').val());
						$('#origen_num_ext').val($('#num_ext_origen').val());
						$('#origen_num_int').val($('#num_int_origen').val());
						$('#origen_telefono').val($('#telefono_origen').val());
						$('#origen_celular').val($('#celular_origen').val());
						$('#myModal').remove();
						$('.modal-backdrop').remove();
						$('#origen_hide_ok').removeClass('hide');
					}
				</script>				
			</form>			
		</div>	
	</div>		
</div>