<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Modificar el tipo de viaje para el viaje: <?=$id_viaje?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<form id="setear_revision_viaje">
					Seleccione el nuevo tipo de viaje que estará relacionado al viaje: <?=$id_viaje?>

						Estado del operador después del seteo:<br><br>

						<div class="radio">
              <div class="radio">
                <label for="normal" class="col-sm-9 control-label no-padding-right">Normal</label>
                     <div class="col-sm-3">
                            <label style="position:relative; top:5px;">
                                   <input value="269" <?php if($revision_actual['id_cat'] == 269){echo 'checked="checked"';} ?> name="revision" id="normal" type="radio" class="ace input-lg" />
                                   <span class="lbl bigger-120">&nbsp;</span>
                            </label>
                     </div>
              </div><br>
               <div class="radio">
                 <label for="viaje_redondo" class="col-sm-9 control-label no-padding-right">Redondo</label>
                      <div class="col-sm-3">
                             <label style="position:relative; top:5px;">
                                    <input value="260" <?php if($revision_actual['id_cat'] == 260){echo 'checked="checked"';} ?> name="revision" id="viaje_redondo" type="radio" class="ace input-lg" />
                                    <span class="lbl bigger-120">&nbsp;</span>
                             </label>
                      </div>
               </div><br>
               <div class="radio">
                 <label for="multiusuario" class="col-sm-9 control-label no-padding-right">Multi-Usuario</label>
                      <div class="col-sm-3">
                             <label style="position:relative; top:5px;">
                                    <input value="261" <?php if($revision_actual['id_cat'] == 261){echo 'checked="checked"';} ?> name="revision" id="multiusuario" type="radio" class="ace input-lg" />
                                    <span class="lbl bigger-120">&nbsp;</span>
                             </label>
                      </div>
               </div><br>
               <div class="radio">
                 <label for="multidestino" class="col-sm-9 control-label no-padding-right">Multi-Destino</label>
                      <div class="col-sm-3">
                             <label style="position:relative; top:5px;">
                                    <input value="262" <?php if($revision_actual['id_cat'] == 262){echo 'checked="checked"';} ?> name="revision" id="multidestino" type="radio" class="ace input-lg" />
                                    <span class="lbl bigger-120">&nbsp;</span>
                             </label>
                      </div>
               </div><br>
               <div class="radio">
                 <label for="polanco_stafe" class="col-sm-9 control-label no-padding-right">Polanco-Sta FE Excepción</label>
                      <div class="col-sm-3">
                             <label style="position:relative; top:5px;">
                                    <input value="263" <?php if($revision_actual['id_cat'] == 263){echo 'checked="checked"';} ?> name="revision" id="polanco_stafe" type="radio" class="ace input-lg" />
                                    <span class="lbl bigger-120">&nbsp;</span>
                             </label>
                      </div>
                </div>


						</div>

					<br><br><br><br>¿Está seguro de continuar con esta acción?
				</form>
			</div>

			<div class="modal-footer">
				<button onclick="modificar_revision_set(<?=$id_viaje?>);" class="btn btn-ar btn-success" type="button">Modificar la revisión</button>
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>
