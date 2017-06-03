<div class="container">
	<section class="margin-bottom">
	       <input type="hidden" name="id_concepto" id="id_concepto" value="<?php echo $id_concepto; ?>">
		<h3 class="header lighter green wow fadeInUp animated" style="font-size:2.5em !important; line-height:70px;">
		<?=$dataegreso['monto']?> por <?=$dataegreso['concepto']?>:
		</h3>
              <h3 class="header lighter green wow fadeInUp animated" style="font-size:2.5em !important; line-height:70px;">
                     <button onclick="checkallBoxes();" style="font-size:.5em" class="btn btn-success wow fadeInUp animated">Activar todos</button>
                     <button onclick="uncheckallBoxes();" style="font-size:.5em" class="btn btn-warning wow fadeInUp animated">Desactivar todos</button>
                     <button onclick="invertBoxes();" style="font-size:.5em" class="btn btn-primary wow fadeInUp animated">Invertir Selección</button>
              </h3>

		<?php
		foreach ($list_operadores as $num => $operador){

			$permiso = $egresos->getStatus($id_concepto,$operador['id_operador']);
			if($permiso == 1){$checked = 'checked';}else{$checked = '';}
		?>

			<div class="text-icon wow fadeInUp animated">
				<span style="float: left; position:relative;">
					<input onchange='establecer_cobro(<?php echo $operador['id_operador']; ?>)' data-operador="<?=$operador['id_operador']?>" data-num="<?=$operador['num']?>" id="permission_<?php echo $operador['id_operador']; ?>" name="permission_<?php echo $operador['id_operador']; ?>" class="ace ace-switch ace-switch-5" type="checkbox" <?php echo $checked; ?>/>
					<span class="lbl"></span>
				</span>
				<div class="text-icon-content">
					<h4 class="blue">
						<?php echo $operador['nombre'];?>
						<span style="font-size:1em; color:#ff9900">
							(<?php echo $operador['num']; ?>)
						</span>
					</h4>

					<div class="profile-activity clearfix" style="height:50px !important;">
						<div>
							<div class="time">
                                                        <span class="blink_me" style="color:red;">
                                                               <?php
                                                               $a = $operador['estado'];

                                                               if($a != 'Activo'){
                                                                      echo "Este usuario se encuentra suspendido";
                                                               }

                                                               ?>
                                                        </span>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php
		}
		?>
	</section>
       <script>
       function invertBoxes(){
              $("input[type=checkbox]").each(function(event){
                     if($(this).prop('checked') == false){
                            $(this).prop('checked', true);
                            $.post('egresosoperador/establecer_cobro/<?php echo $id_concepto; ?>/'+ $(this).data('operador') + '/true/' + $(this).data('num') );
                     }else{
                            $(this).prop('checked', false);
                            $.post('egresosoperador/establecer_cobro/<?php echo $id_concepto; ?>/'+ $(this).data('operador') + '/false/' + $(this).data('num') );
                     }
              })
       }
       function checkallBoxes(){
              $("input[type=checkbox]").each(function(event){
                     if($(this).prop('checked') == false){
                            $(this).prop('checked', true);
                            $.post('egresosoperador/establecer_cobro/<?php echo $id_concepto; ?>/'+ $(this).data('operador') + '/true/' + $(this).data('num') );
                     }
              })
       }
       function uncheckallBoxes(){
              $("input[type=checkbox]").each(function(event){
                     if($(this).prop('checked') == true){
                            $(this).prop('checked', false);
                            $.post('egresosoperador/establecer_cobro/<?php echo $id_concepto; ?>/'+ $(this).data('operador') + '/false/' + $(this).data('num') );
                     }
              })
       }
       </script>
</div>
