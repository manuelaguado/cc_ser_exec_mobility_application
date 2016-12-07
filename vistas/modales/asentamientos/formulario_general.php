<div id="myModal" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="smaller lighter blue no-margin">Selección del asentamiento</h3>
			</div>
			<form class="form-horizontal" role="form" id="nueva_ubicacion">
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> CP </label>

						<div class="col-sm-9">
							<span class="input-icon">
								<input value="" type="text" id="cp" name="cp" placeholder="Código postal" class="col-xs-11 col-sm-11" autocomplete="off"/>
								<i class="ace-icon fa fa-qrcode blue"></i>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">Estado</label>
						<div class="col-sm-9">
							<span class="input-icon">
								<input readonly="" value="Distrito Federal" type="text" id="estado" name="estado" placeholder="Estado" class="col-xs-11 col-sm-11" />
								<i class="ace-icon fa fa-globe blue"></i>
							</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">Municipio</label>
						<div class="col-sm-9">
							<span class="input-icon">
								<input readonly="" value="" type="text" id="municipio" name="municipio" placeholder="Municipio" class="col-xs-11 col-sm-11"/>
								<i class="ace-icon fa fa-map blue"></i>
							</span>
						</div>
					</div>									
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">Ciudad</label>
						<div class="col-sm-9">
							<span class="input-icon">
								<input value="Ciudad de México" type="text" id="ciudad" name="ciudad" placeholder="Ciudad" class="col-xs-11 col-sm-11" autocomplete="off"/>
								<i class="ace-icon fa fa-building blue"></i>
								<i id="get_city" class="ace-icon fa fa-check hide" style="position:relative; float:right !important;"></i>
							</span>
						</div>
					</div>
					
					<div class="space-4"></div>
					
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right">Colonia</label>
						<div class="col-sm-9">
							<span class="input-icon">
								<input value="" type="text" id="colonia" name="colonia" placeholder="Colonia" class="col-xs-11 col-sm-11" autocomplete="off"/>
								<i class="ace-icon fa fa-building-o blue"></i>
							</span>
						</div>
					</div>
				</div>
				<div class="modal-footer hide" id="listo">
					<div id="seleccion_actual" style="position:relative; float:left; font-size:1.3em; color:#7F7F7F;"></div>
					<a class="btn btn-sm btn-success pull-right" data-dismiss="modal">
						<i class="ace-icon fa fa-check"></i>
						¡ Listo !
					</a>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	<script>
	$('#ciudad').autocomplete({
		serviceUrl: 'asentamientos/busqueda_ciudad',
		minChars: 3,
		onSelect: function (suggestion) {
			$('#estado').val(suggestion.estado);
			$('#ciudad').val(suggestion.ciudad);
			$.ajax({
				url: 'asentamientos/set_busqueda_ciudad/' + suggestion.data,
				dataType: 'html',
					success: function(resp_success){
						$('#get_city').removeClass('hide');
						$('#get_city').css('color',"#" + Math.random().toString(16).slice(2, 8));
					},
				error: function(respuesta){ alerta('Alerta!','Error al definir la ciudad');}	
			});	
		},
	});
	$('#colonia').autocomplete({
		serviceUrl: 'asentamientos/busqueda_colonia',
		minChars: 3,
		onSelect: function (suggestion) {
			$('#cp').val(suggestion.cp);
			$('#estado').val(suggestion.estado);
			$('#municipio').val(suggestion.municipio);
			$('#ciudad').val(suggestion.ciudad);
			$('#colonia').val(suggestion.colonia);
			$('#<?=$id_asentamiento?>').val(suggestion.data);
			$('#<?=$identificador?>').val(suggestion.colonia+', '+suggestion.cp+',  '+suggestion.ciudad+', '+suggestion.municipio+', '+suggestion.estado);
			$('#listo').removeClass('hide');
			$('#seleccion_actual').html('Actual: '+suggestion.colonia);
		},
	});
	$('#cp').autocomplete({
		serviceUrl: 'asentamientos/busqueda_cp',
		minChars: 3,
		onSelect: function (suggestion) {
			$('#cp').val(suggestion.cp);
			$('#estado').val(suggestion.estado);
			$('#municipio').val(suggestion.municipio);
			$('#ciudad').val(suggestion.ciudad);
			$('#colonia').val(suggestion.colonia);
			$('#<?=$id_asentamiento?>').val(suggestion.data);
			$('#<?=$identificador?>').val(suggestion.colonia+', '+suggestion.cp+',  '+suggestion.ciudad+', '+suggestion.municipio+', '+suggestion.estado);
			$('#listo').removeClass('hide');
			$('#seleccion_actual').html('Actual: '+suggestion.colonia);
		},
	});
	</script>	
</div>