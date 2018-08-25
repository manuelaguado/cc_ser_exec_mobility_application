<style>
span.input-icon {
    display: inline-block;
    width: 100% !important;
}
span.input-icon > textarea {
    padding-left: 24px;
}
#cordon_kpmg_wrapper > div:nth-child(1){display:none;}
#cordon_ejnal_wrapper > div:nth-child(1){display:none;}
#tabla_pendientes_wrapper > div:nth-child(1){display:none;}
#tabla_proceso_wrapper > div:nth-child(1){display:none;}
#tabla_asignados_wrapper > div:nth-child(1){display:none;}
#cordon_kpmg > tbody > tr > td:nth-child(8){padding: 0px;vertical-align: middle;}
#cordon_kpmg > tbody > tr > td:nth-child(8) > a:nth-child(1){position:relative; top:-7px;}
.autocomplete-suggestions{width:auto !important;}
.inside{
       position: relative;
       font-size: .7em;
       left: -10px;
       top: 5px;
       color: #000;
}
</style>
<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="widget-box transparent">
				<div class="widget-header widget-header-flat">
					<h1 class="widget-title lighter">
						Solicitud de servicio
					</h1>
					<div class="widget-toolbar">
						<a href="#" data-action="collapse">
							<i class="ace-icon fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="widget-body">
					<form class="form-horizontal" role="form" id="nuevo_servicio">
						<div class="page-header" style="margin-bottom: 26px;">
							<h1>&nbsp;<br>

							<a class="btn btn-info" href="#" onclick="procesar_servicio()" style="position:relative; float:right; top:-30px;">
								<i class="ace-icon fa fa-gear bigger-110"></i>
								Procesar
							</a>
							</h1>
						</div>
                                          <span id="input_pasajeros"></span>
                                          <input type="hidden" id="origen_calle" name="origen_calle" value="" />
                                          <input type="hidden" id="origen_num_ext" name="origen_num_ext" value="" />
                                          <input type="hidden" id="origen_num_int" name="origen_num_int" value="" />
                                          <input type="hidden" id="origen_telefono" name="origen_telefono" value="" />
                                          <input type="hidden" id="origen_celular" name="origen_celular" value="" />

                                          <input type="hidden" id="destino_calle" name="destino_calle" value="" />
                                          <input type="hidden" id="destino_num_ext" name="destino_num_ext" value="" />
                                          <input type="hidden" id="destino_num_int" name="destino_num_int" value="" />
                                          <input type="hidden" id="destino_telefono" name="destino_telefono" value="" />
                                          <input type="hidden" id="destino_celular" name="destino_celular" value="" />

                                          <input type="hidden" id="id_operador_unidad" name="id_operador_unidad" value="" />
                                          <input type="hidden" id="id_operador" name="id_operador" value="" />
                                          <input type="hidden" id="id_operador_turno" name="id_operador_turno" value="" />
                                          <input type="hidden" id="turno_apartado" name="turno_apartado" value="" />
                                          <input type="hidden" id="numero_economico" name="numero_economico" value="" />

                                          <input type="hidden" id="msgPaqArray" name="msgPaqArray" value="" />

                                          <input type="hidden" id="temporicidad" name="temporicidad" value="184" />
                                          <input type="hidden" id="exist_tarifa" name="exist_tarifa" value="0" />

                                          <input type="hidden" id="refmod_aux" name="refmod_aux" value="" />

						<div class="row">
								<div class="col-sm-3">
									<div class="widget-header widget-header-flat">
										<h4 class="widget-title lighter">
											<i class="ace-icon fa fa-user blue"></i>
											<span id="dta_opt">Opciones</span>
										</h4>
									</div><br>
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="forma_pago"> Forma de pago </label>

										<div class="col-sm-8">
											<select  class="form-control" id="forma_pago" name="forma_pago">
												<?php echo $formaPago; ?>
											</select>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<label class="col-sm-2 control-label no-padding-right" for="cat_tiposervicio"> Servicio </label>

										<div class="col-sm-5">
											<select onchange="verifyServicio();" class="form-control" id="cat_tiposervicio" name="cat_tiposervicio">
												<?php echo $tiposServicios; ?>
											</select>
										</div>

										<div class="col-sm-5">
											<label style="position:relative; top:5px;">
												<input onchange="apartadoActive()" value="1" name="tipo_temporicidad" id="tipo_temporicidad" class="ace ace-switch ace-switch-6" type="checkbox" />
												<span class="lbl">&nbsp;Apartado</span>
											</label>
										</div>

									</div>
									<hr />
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="cat_tipo_salida"> Salida </label>

										<div class="col-sm-8">
											<select onchange="verifySalida();" class="form-control" id="cat_tipo_salida" name="cat_tipo_salida">
												<?php echo $tipoSalida; ?>
											</select>
										</div>
										<div class="col-sm-offset-4 col-sm-12 radio hide" id="select_fs">
											<label>
												<input name="sitio_select_oper" id="sitio_select_oper1" value="1" type="radio" class="ace" />
												<span class="lbl"> Primero</span>
											</label>
											<label>
												<input name="sitio_select_oper" id="sitio_select_oper2" value="2" type="radio" class="ace" />
												<span class="lbl"> Segundo</span>
											</label>
										</div>

									</div>
									<hr />
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="fecha_hora"> Fecha & hora </label>

										<div class="col-sm-8">
											<div class="input-group">
												<input id="fecha_hora" name="fecha_hora" type="text" class="form-control" />
												<span class="input-group-addon">
													<i class="fa fa-clock-o bigger-110"></i>
												</span>
											</div>
										</div>
									</div>
									<hr />
									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-eye bigger-230 orange icon_ztop"></i>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<textarea type="text" id="observaciones" name="observaciones" placeholder="Observaciones" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-question-circle blue"></i>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="widget-header widget-header-flat">
										<h4 class="widget-title lighter">
											<i class="ace-icon fa fa-user blue"></i>
											<span id="dta_name">Usuario</span>
										</h4>
									</div><br>

									<div class="form-group">
										<label class="col-sm-3 control-label no-padding-right">Usuario</label>
										<div class="col-sm-9">
											<span class="input-icon">
												<input value="" type="text" id="user" name="user" placeholder="Usuario" class="col-xs-12 col-sm-12" autocomplete="off"/>
												<i class="ace-icon fa fa-user blue"></i>
												<i class="right_align fa fa-plus blue add_user_form"></i>
											</span>
										</div>
									</div>

									<div class="form-group hide" id="user_list">
										<label class="col-sm-3 control-label no-padding-right">Pasajeros</label>
										<div class="col-sm-9" id="pasajeros_list"></div>
									</div>


									<div class="form-group hide" id="spinDestinos">
										<label class="col-sm-3 control-label no-padding-right">Destinos</label>
										<div class="col-sm-9">
											<input readonly="" type="text" class="input-sm" id="spinDestino" name="spinDestino"/>
										</div>
									</div>
                                                              <div class="radio">
                                            <label for="normal" class="col-sm-9 control-label no-padding-right">Normal</label>
                                                                     <div class="col-sm-3">
                                                                            <label style="position:relative; top:5px;">
                                                                                   <input value="269" checked="checked" name="revision" id="normal" type="radio" class="ace input-lg" />
                                                                                   <span class="lbl bigger-120">&nbsp;</span>
                                                                            </label>
                                                                     </div>
                    </div>
                                                               <div class="radio">
										<label for="viaje_redondo" class="col-sm-9 control-label no-padding-right">Redondo</label>
                                                                      <div class="col-sm-3">
                                                                             <label style="position:relative; top:5px;">
                                                                                    <input value="260" name="revision" id="viaje_redondo" type="radio" class="ace input-lg" />
                                                                                    <span class="lbl bigger-120">&nbsp;</span>
                                                                             </label>
                                                                      </div>
									</div>
                                                               <div class="radio">
										<label for="multiusuario" class="col-sm-9 control-label no-padding-right">Multi-Usuario</label>
                                                                      <div class="col-sm-3">
                                                                             <label style="position:relative; top:5px;">
                                                                                    <input value="261" name="revision" id="multiusuario" type="radio" class="ace input-lg" />
                                                                                    <span class="lbl bigger-120">&nbsp;</span>
                                                                             </label>
                                                                      </div>
									</div>
                                                               <div class="radio">
										<label for="multidestino" class="col-sm-9 control-label no-padding-right">Multi-Destino</label>
                                                                      <div class="col-sm-3">
                                                                             <label style="position:relative; top:5px;">
                                                                                    <input value="262" name="revision" id="multidestino" type="radio" class="ace input-lg" />
                                                                                    <span class="lbl bigger-120">&nbsp;</span>
                                                                             </label>
                                                                      </div>
									</div>
                                                               <div class="radio">
										<label for="polanco_stafe" class="col-sm-9 control-label no-padding-right">Polanco-Sta FE Excepción</label>
                                                                      <div class="col-sm-3">
                                                                             <label style="position:relative; top:5px;">
                                                                                    <input value="263" name="revision" id="polanco_stafe" type="radio" class="ace input-lg" />
                                                                                    <span class="lbl bigger-120">&nbsp;</span>
                                                                             </label>
                                                                      </div>
									</div>


								</div>
								<div class="col-sm-3">
									<div class="widget-header widget-header-flat">
										<h4 class="widget-title lighter">
											<i class="ace-icon fa fa-map-marker blue"></i>
											Origen
										</h4>
                    <div id="aproximateTimeO" class="widget-title lighter" style="font-size:1.3em; position:relative; float:right; padding-right:10px;"><i onclick="aproximateTimeO()" class="ace-icon fa fa-clock-o blue"></i></div>
									</div><br>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-bookmark bigger-230 orange icon_ztop"></i>
										</label>

										<div class="col-sm-11">
											<select onchange="cleanOrigen();" class="form-control" id="id_cliente_origen" name="id_cliente_origen">
												<option value="" disabled selected>Origenes guardados</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<a onclick="iframeSetReference(); cleanSavedOrigen();" href="javascript:void(0);">
												<i class="ace-icon fa fa-globe bigger-230 orange icon_ztop"></i>
											</a>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<textarea readonly="" id="geocodificacion_inversa_origen" name="geocodificacion_inversa_origen" placeholder="Geocodificacion inversa" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-globe blue"></i>
											</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-question-circle bigger-230 orange icon_ztop"></i>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<textarea  id="origen_referencia" onchange="cleanSavedOrigen();" name="origen_referencia" placeholder="Referencia" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-question-circle blue"></i>
											</span>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-thumb-tack bigger-230 orange icon_ztop"></i>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<input readonly="" type="text" id="geocoordenadas_origen" name="geocoordenadas_origen" placeholder="Geocoordenadas" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-thumb-tack blue"></i>
											</span>
										</div>
									</div>
									<div class="space-4"></div>
									<a class="btn btn-light" href="#" onclick="modal_extra_origen();" style="position:relative; float:right; top:-8px;">
										<i class="ace-icon fa fa-home bigger-110"></i>
										Más datos
									</a>
									<i id="origen_hide_ok" class="fa fa-check-circle-o origen_destiono_set hide" aria-hidden="true"></i>

								</div>
								<div class="col-sm-3">
									<div class="widget-header widget-header-flat">
										<h4 class="widget-title lighter">
											<i class="ace-icon fa fa-map-marker blue"></i>
											Destino
										</h4>
                    <div id="aproximateTime" class="widget-title lighter" style="font-size:1.3em; position:relative; float:right; padding-right:10px;"><i onclick="aproximateTime()" class="ace-icon fa fa-clock-o blue"></i></div>
									</div><br>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-bookmark bigger-230 green icon_ztop"></i>
										</label>

										<div class="col-sm-11">
											<select onchange="cleanDestino();" class="form-control" id="id_cliente_destino" name="id_cliente_destino">
												<option value="" disabled selected>Destinos guardados</option>
											</select>
										</div>
									</div>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<a onclick="iframeSetReferenceD(); cleanSavedDestino();" href="javascript:void(0);">
												<i class="ace-icon fa fa-globe bigger-230 green icon_ztop"></i>
											</a>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<textarea readonly="" id="geocodificacion_inversa_destino" name="geocodificacion_inversa_destino" placeholder="Geocodificacion inversa" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-globe blue"></i>
											</span>
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-question-circle bigger-230 green icon_ztop"></i>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<textarea id="destino_referencia" onchange="cleanSavedDestino();" name="destino_referencia" placeholder="Referencia" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-question-circle blue"></i>
											</span>
										</div>
									</div>


									<div class="form-group">
										<label class="col-sm-1 control-label no-padding-right" for="form-field-1">
											<i class="ace-icon fa fa-thumb-tack bigger-230 green icon_ztop"></i>
										</label>
										<div class="col-sm-11">
											<span class="input-icon">
												<input readonly="" type="text" id="geocoordenadas_destino" name="geocoordenadas_destino" placeholder="Geocoordenadas" class="col-sm-12 autosize-transition form-control"></textarea>
												<i class="ace-icon fa fa-thumb-tack blue"></i>
											</span>
										</div>
									</div>

									<div class="space-4"></div>
									<a class="btn btn-light" href="#" onclick="modal_extra_destino();" style="display:relative; float:right; top:-8px;">
										<i class="ace-icon fa fa-home bigger-110"></i>
										Más datos
									</a>
									<i id="destino_hide_ok" class="fa fa-check-circle-o origen_destiono_set hide" aria-hidden="true"></i>

								</div>
						</div>
					</form>
				</div>
			</div>
			<hr/>
			<div class="col-sm-12">
				<div class="widget-box transparent" id="recent-box">
					<div class="widget-header">
						<h4 class="widget-title lighter smaller">
							<i class="ace-icon fa fa-car orange"></i>Tablas
						</h4>

						<div class="widget-toolbar no-border">
							<ul class="nav nav-tabs" id="recent-tab">
								<li class="active">
									<a data-toggle="tab" href="#table_kpmg" aria-expanded="false">
                                                               <span id="fillcordon" style="z-index:500"><i class="fa fa-plus heartbeat animate-infinite-heartbeat" style="color:#bf5f06;"></i></span>
                                                               &nbsp;&nbsp;&nbsp;KPMG</a>
								</li>

								<li class="">
									<a data-toggle="tab" href="#table_pendientes" aria-expanded="false">
									<i class="fa fa-chain-broken" style="color:#c40b0b;"></i>
									Pendientes</a>
								</li>
								<li class="">
									<a data-toggle="tab" href="#table_asignados" aria-expanded="false">
									<i class="fa fa-calendar-check-o" style="color:#32b200"></i>
									Asignados</a>
								</li>

							</ul>
						</div>
					</div>

					<div class="widget-body">
						<div class="widget-main padding-4">
							<div class="tab-content padding-8">
								<div id="table_kpmg" class="tab-pane active">
									<table id="cordon_kpmg" class="display table table-striped" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>Turno</th>
												<th>NUM&nbsp;EQ</th>
												<th>Nombre</th>
												<th>Marca</th>
												<th>Modelo</th>
												<th>Color</th>
												<th>Llegada/Espera</th>
                        <th></th>
                        <th></th>

											</tr>
										</thead>
									</table>
								</div>

								<div id="table_pendientes" class="tab-pane">
									<table id="tabla_pendientes" class="display table table-striped" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>[STATUS]</th>
												<th>Hora</th>
												<th>Usuario</th>
												<th>Empresa</th>
												<th>Servicio</th>
												<th>Tipo</th>
												<th>Acciones</th>
											</tr>
										</thead>
									</table>
								</div>
								<div id="table_asignados" class="tab-pane">
									<table id="tabla_asignados" class="display table table-striped" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th>ID</th>
												<th>[STATUS]</th>
												<th>Hora</th>
												<th>Usuario</th>
												<th>Empresa</th>
												<th>Servicio</th>
												<th>Tipo</th>
												<th>NUM</th>
												<th>Acciones</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var pasajeros = [];
function removeClient(id){
	$('#client'+id).remove();
	$('#usuario_'+id).remove();

       var i = pasajeros.indexOf( id );
       pasajeros.splice( i, 1 );

	var destinos = $('#pasajeros_list > div').length;
	if(destinos == 0){
		$('#user_list').addClass('hide');
		$('#spinDestinos').addClass('hide');
		$('#dta_name').html('Usuario');
		$("#id_cliente_origen").val('');
		$("#id_cliente_destino").val('');
		$("#id_cliente_origen").html('<option value="" disabled selected>Origenes guardados</option>');
		$("#id_cliente_destino").html('<option value="" disabled selected>Destinos guardados</option>');
		globalTypeUser = 'init';
		pasajeros = [];
	}
}

var solicitudInit = function () {
       return {
              init: function () {
                     var globalTypeUser = 'init';
                     $('#user').autocomplete({
                            serviceUrl: 'operacion/busqueda_usuario',
                            minChars: 3,
                            onSelect: function (suggestion) {
                                   if((globalTypeUser == suggestion.parent)||(globalTypeUser == 'init')){

                                          globalTypeUser = suggestion.parent;

                                          if(jQuery.inArray(suggestion.id, pasajeros) === -1){

                                                 pasajeros.push(suggestion.id);
                                                 $('#input_pasajeros').append('<input type="hidden" id="usuario_'+suggestion.id+'" name="usuario_'+suggestion.id+'" value="'+suggestion.id+'" />');
                                                 $('#pasajeros_list').append('<div class="tipo_cliente" id="client'+suggestion.id+'"><a onclick="removeClient('+suggestion.id+');" href="javascript:void(0);"><i class="fa fa-times-circle orange" aria-hidden="true"></i></a>&nbsp;&nbsp;'+suggestion.nombre+'</div>');

                                                 $('#dta_name').html(suggestion.etiqueta + ' > ' +  suggestion.parent);

                                                 $('#user').val('');
                                                 $('#user_list').removeClass('hide');
                                                 $('#spinDestinos').removeClass('hide');


                                                 var destinos = $('#pasajeros_list > div').length;
                                                 if(destinos == 0){
                                                        $('#user_list').addClass('hide');
                                                        $('#spinDestinos').addClass('hide');
                                                        $('#dta_name').html('user');
                                                        $("#id_cliente_origen").val('');
                                                        $("#id_cliente_destino").val('');
                                                        $("#id_cliente_origen").html('<option value="" disabled selected>Origenes guardados</option>');
                                                        $("#id_cliente_destino").html('<option value="" disabled selected>Destinos guardados</option>');
                                                        globalTypeUser = 'init';
                                                        pasajeros = [];
                                                 }

                                                 if(destinos == 1){
                                                        if($('#cat_tiposervicio').val() == ''){
                                                               var urltar='getTarifa';
                                                               $('#cat_tiposervicio').val('165');
                                                        }else{
                                                               if($('#cat_tiposervicio').val() == 254){
                                                                      var urltar='getTarifaC';
                                                                      var mssgNoTarifa = 'Falta incluir una tarifa de cortesía para el cliente <span class="tipo_cliente" style="font-size:1em; top:0px;">'+suggestion.nombre+'</span>';
                                                               }else{
                                                                      var urltar='getTarifa';
                                                                      var mssgNoTarifa = 'No existen tarifas relacionadas al cliente <span class="tipo_cliente" style="font-size:1em; top:0px;">'+suggestion.nombre+'</span>';
                                                               }
                                                        }
                                                        $.ajax({
                                                               url: 'operacion/' + urltar + '/' + suggestion.id,
                                                               dataType: 'json',
                                                                      success: function(resp_success){
                                                                             if (resp_success['resp'] == true) {
                                                                                    if(resp_success['tarifa'] == false){
                                                                                           alerta('Cuenta sin tarifa',mssgNoTarifa);
                                                                                    }else{
                                                                                           $("#exist_tarifa").val('1');
                                                                                    }
                                                                             }
                                                                      },
                                                               error: function(respuesta){ alerta('Info!','Error de al seleccionar los origenes');}
                                                        });
                                                        if(suggestion.tipocliente == '201'){
                                                               $("#id_cliente_origen").html('<option value="" disabled selected>No disponible para usuario concentrador</option>');
                                                               $("#id_cliente_destino").html('<option value="" disabled selected>No disponible para usuario concentrador</option>');
                                                        }else{
                                                               $.ajax({
                                                                      url: 'operacion/selectOrigenes/'+suggestion.id,
                                                                      dataType: 'html',
                                                                             success: function(resp_success){
                                                                                    if( resp_success == "<option value=''>Seleccione...</option>"){
                                                                                           $('#id_cliente_origen').html("<option disabled selected value=''>Sin datos de origenes</option>");
                                                                                    }else{
                                                                                           $('#id_cliente_origen').html(resp_success);
                                                                                    }
                                                                             },
                                                                      error: function(respuesta){ alerta('Info!','Error de al seleccionar los origenes');}
                                                               });
                                                               $.ajax({
                                                                      url: 'operacion/selectDestinos/'+suggestion.id,
                                                                      dataType: 'html',
                                                                             success: function(resp_success){
                                                                                    if( resp_success == "<option value=''>Seleccione...</option>"){
                                                                                           $('#id_cliente_destino').html("<option disabled selected value=''>Sin datos de destinos</option>");
                                                                                    }else{
                                                                                           $('#id_cliente_destino').html(resp_success);
                                                                                    }
                                                                             },
                                                                      error: function(respuesta){ alerta('Info!','Error de al seleccionar los destinos');}
                                                               });
                                                        }
                                                 }

                                          }else{

                                                 alerta('Pasajero duplicado','El pasajero <span class="tipo_cliente" style="font-size:1em; top:0px;">'+suggestion.nombre+'</span> ya estaba en la lista.');
                                                 $('#user').val('');

                                          }

                                   }else{

                                          alerta('Cuentas diferentes','Los usuarios deben de pertenecer a la misma cuenta, no mezcle usuarios de diferentes cuentas en un mismo viaje.');
                                          $('#user').val('');

                                   }
                            }
                     });
                     Date.prototype.toMysqlFormat = function() {
                         return this.getFullYear() + "-" + twoDigits(1 + this.getMonth()) + "-" + twoDigits(this.getDate()) + " " + twoDigits(this.getHours()) + ":" + twoDigits(this.getMinutes()) + ":" + twoDigits(this.getSeconds());
                     };
                     var time = new Date();
                     $('#fecha_hora').datetimepicker({
                            minDate: moment(time.toMysqlFormat()),
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
                     }).next().on(ace.click_event, function(){
                            $(this).prev().focus();
                     });


                     autosize($('textarea[class*=autosize]'));

                     $('#spinDestino').ace_spinner({value:1,min:1,max:50,step:1, touch_spinner: true, icon_up:'ace-icon fa fa-caret-up bigger-110', icon_down:'ace-icon fa fa-caret-down bigger-110'});

                     $('#cordon_kpmg').dataTable( {
                            "fnDrawCallback": function( oSettings ) {
                              $('[data-rel=tooltip]').tooltip();
                              $('.dataTables_empty').attr('colspan',9);
                            },
                            "ordering": false,
                            "processing": true,
                            "serverSide": true,
                            "pageLength": 20,

                            "ajax": {
                                   "url": "operacion/cordon_kpmg_get",
                                   "type": "POST"
                            },
                            "columnDefs": [
                                   {
                                          "targets": 9,
                                          "visible": false,
                                          "searchable":false
                                   }
                            ]
                     } );

                     $('#tabla_pendientes').dataTable( {
                            "fnDrawCallback": function( oSettings ) {
                              $('[data-rel=tooltip]').tooltip();
                              $('.dataTables_empty').attr('colspan',8);
                            },
                            "ordering": false,
                            "processing": true,
                            "serverSide": true,
                            "pageLength": 20,
                            "ajax": {
                                   "url": "operacion/servicios_pendientes",
                                   "type": "POST"
                            },
                            "columnDefs": [
                                   {
                                          "targets": 1,
                                          "visible": false,
                                          "searchable":false
                                   }
                            ]
                     } );
                     $('#tabla_asignados').dataTable( {
                            "fnDrawCallback": function( oSettings ) {
                              $('[data-rel=tooltip]').tooltip();
                              $('.dataTables_empty').attr('colspan',8);
                            },
                            "ordering": false,
                            "processing": true,
                            "serverSide": true,
                            "pageLength": 20,

                            "ajax": {
                                   "url": "operacion/servicios_asignados",
                                   "type": "POST"
                            },
                            "columnDefs": [
                                   {
                                          "targets": 1,
                                          "visible": false,
                                          "searchable":false
                                   }
                            ]
                     } );
              }
       };
}();

jQuery(document).ready(function() {
       solicitudInit.init();
});
</script>
