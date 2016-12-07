<style>
span.input-icon {
    display: inline-block;
    width: 100% !important;
}
span.input-icon > textarea {
    padding-left: 24px;
}
</style>
			<!-- /section:basics/sidebar -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<?php
							if($this->tiene_permiso('Clientes|ubicacion')){
							?>
							<div style="position:relative; float:left; font-size:3em; top:-17px; cursor:pointer">
								<a onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes/ubicacion/<?=$id_cliente?>');" href="#">
									<i class="fa fa-caret-left orange">&nbsp;</i>
								</a>
							</div>
							<?php
							}
							?>							
							<h1>
								Nueva Dirección
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									<?=$cliente['nombre']?>
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<form class="form-horizontal" role="form" id="nueva_ubicacion">
									<!-- #section:elements.form -->
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> ¿Es fiscal? </label>
										
										<div class="col-sm-9">
											<label style="position:relative; top:7px;">
												<input type="checkbox" class="ace ace-switch ace-switch-6" name="fiscal" id="fiscal" onchange="mostrarRfc()">
												<span class="lbl"></span>
											</label>
										</div>
									</div>
									<div class="form-group hide" id="fieldRfc">
										<label class="col-sm-3 control-label no-padding-right">RFC Formato:(AAAA123456A23)</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input onchange="verificarRfc()" type="text" id="rfc" name="rfc" placeholder="RFC" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-file blue"></i>
											</span>
										</div>
									</div>	
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 
											Asentamiento <br>
											<a onclick="formAsentamiento('asentamiento','id_asentamiento')" href="#" class="blue">
												<i class="ace-icon fa fa-map-marker bigger-230 orange"></i>
											</a>
										</label>
			
										<div class="col-sm-9">
											<span class="input-icon">
												<textarea readonly="" id="asentamiento" name="asentamiento" placeholder="Estado, Ciudad, Colonia, CP." class="col-xs-10 col-sm-5"></textarea>
												<i class="ace-icon fa fa-map-marker blue"></i>
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> Calle </label>

										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" id="calle" name="calle" placeholder="Calle" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-road blue"></i>
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Exterior</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" id="num_ext" name="num_ext" placeholder="Número exterior" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-sign-out blue"></i>
											</span>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Interior</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" id="num_int" name="num_int" placeholder="Número interior" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-sign-in blue"></i>
											</span>
										</div>
									</div>									
									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Teléfono (10 digitos + EXT)</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" onchange="verificarTel()" id="telefono" name="telefono" placeholder="Número telefónico" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-phone blue"></i>
											</span>
										</div>
									</div>

									<!-- /section:elements.form -->
									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Celular (10 digitos)</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" onchange="verificarCel()" id="celular" name="celular" placeholder="Número celular" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-mobile blue"></i>
											</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Correo electrónico</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input type="text" onchange="verificarMail()" id="correo" name="correo" placeholder="Correo electrónico" class="col-xs-10 col-sm-5" />
												<i class="ace-icon fa fa-envelope blue"></i>
											</span>
										</div>
									</div>	

									<div class="space-4"></div>
									
									<input type="hidden" id="id_cliente" name="id_cliente" value="<?=$id_cliente?>" />
									<input type="hidden" id="id_asentamiento" name="id_asentamiento" value="" />
									
									<div class="clearfix form-actions">
										<div class="col-md-offset-3 col-md-9">
											<a class="btn btn-info" href="#" onclick="guardarUbicacion(<?=$id_cliente?>);">
												<i class="ace-icon fa fa-check bigger-110"></i>
												Guardar
											</a>
										</div>
									</div>
								</form>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
