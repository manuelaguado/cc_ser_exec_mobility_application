<div class="page-content">
	<div class="page-header">
		<h1>
			Configuracion de CentralCar
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Configuracion de variables del sistema
			</small>
		</h1>
	</div>

	<div class="row">
		<div class="col-xs-12">
			<form class="form-horizontal" role="form" id="settings">

                            <div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-6">Costo de una hora</label>

					<div class="col-sm-9">
						<input type="text" id="costo_hora" name="costo_hora" placeholder="ingrese el costo" title="" data-placement="bottom" value="<?=(Controlador::getConfig(1,'costo_hora'))['valor']?>">
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Vgr: 130" title="" data-original-title="Ingrese el valor de la hora de espera o viaje">?</span>
					</div>
				</div>

                            <div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-6">Tiempo de cortesía</label>

					<div class="col-sm-9">
						<input type="text" id="tiempo_cortesia" name="tiempo_cortesia" placeholder="ingrese el tiempo" title="" data-placement="bottom" value="<?=(Controlador::getConfig(1,'tiempo_cortesia'))['valor']?>">
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Vgr: 00:15:00" title="" data-original-title="Ingrese el valor en minutos de tiempo">?</span>
					</div>
				</div>

                            <div class="form-group">
                                   <label class="col-sm-3 control-label no-padding-right" for="form-field-6">Km de perimetro predeterminado</label>

                                   <div class="col-sm-9">
                                          <input type="text" id="km_perimetro" name="km_perimetro" placeholder="ingrese los kilometros" title="" data-placement="bottom" value="<?=(Controlador::getConfig(1,'km_perimetro'))['valor']?>">
                                          <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Vgr: 4" title="" data-original-title="Ingrese el valor en kilometros">?</span>
                                   </div>
                            </div>

                            <div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-6">Km de perimetro en una cortesía</label>

					<div class="col-sm-9">
						<input type="text" id="km_cortesia" name="km_cortesia" placeholder="ingrese los kilometros" title="" data-placement="bottom" value="<?=(Controlador::getConfig(1,'km_cortesia'))['valor']?>">
						<span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Vgr: 5" title="" data-original-title="Ingrese el valor en kilometros">?</span>
					</div>
				</div>

                            <div class="form-group">
                                   <label class="col-sm-3 control-label no-padding-right" for="form-field-6">Comision de cobro a los operadores</label>

                                   <div class="col-sm-9">
                                          <input type="text" id="comision_operadores" name="comision_operadores" placeholder="ingrese la comisión" title="" data-placement="bottom" value="<?=(Controlador::getConfig(1,'comision_operadores'))['valor']?>">
                                          <span class="help-button" data-rel="popover" data-trigger="hover" data-placement="right" data-content="Vgr: 25" title="" data-original-title="Ingrese el porcentaje de la comisión">?</span>
                                   </div>
                            </div>

				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<a class="btn btn-info" onclick="updateSettings()" href="javascript:;" >
							<i class="ace-icon fa fa-check bigger-110"></i>
							Actualizar
						</a>
					</div>
				</div>

				<div class="hr hr-24"></div>

			</form>
		</div><!-- /.col -->
	</div><!-- /.row -->
</div>
<script type="text/javascript">
       jQuery(function($) {
              $('[data-rel=tooltip]').tooltip({container:'body'});
              $('[data-rel=popover]').popover({container:'body'});
       });
</script>
