	
<script src="<?=URL_PUBLIC?>js/dac_acl.js"></script>

<div>
<?php
//print("<pre>".print_r($permisos,true)."</pre>");
?>
</div>
<div class="container">
	<section class="margin-bottom">
	<form id="dac_acl">
		<input type="hidden" name="user" id="user" value="<?=$usuario['id_usuario']?>">
		<?php
		$printhead = 1;
		$titulo = '';
		$count = 0;
		for($i=0;$i<count($fullpermission);$i++){
			if($fullpermission[$i]['extension'][1] != $titulo){$printhead = 1;}
			if($printhead == 1){
				?>
				<h3 class="header lighter green wow fadeInUp animated" style="font-size:2.5em !important; padding-bottom:20px; padding-top:20px;">
				<span style="float: left; position:relative;">
					<input id="<?=$fullpermission[$i]['extension'][0]?>" class="ace ace-switch ace-switch-5" type="checkbox" onchange="set_acl_extension('<?=$fullpermission[$i]['extension'][0]?>')">
					<span class="lbl"></span>
				</span>
				<div style="text-indent:10px;"><?=$fullpermission[$i]['extension'][1];?></div>
				</h3>
				<?php
				$printhead = 0;
				$titulo = $fullpermission[$i]['extension'][1];
			}
			$count_arreglo = count($fullpermission[$i])-1;
			for($k=0;$k<$count_arreglo;$k++){				
			?>
			<div class="text-icon wow fadeInUp animated">
				<span style="float: left; position:relative;">
					<input id="<?=$fullpermission[$i]['extension'][0]?>-<?=$fullpermission[$i][$k]['controlador'][0]?>" class="ace ace-switch ace-switch-5" type="checkbox" onchange="set_acl_par('<?=$fullpermission[$i]['extension'][0]?>-<?=$fullpermission[$i][$k]['controlador'][0]?>')">
					<span class="lbl"></span>
				</span>
				<div class="text-icon-content">
					<h4 class="blue" style="text-indent:10px;">
						<?=$fullpermission[$i][$k]['controlador'][1]?>							
					</h4>
					<?php
					$metodos_sidebar = $fullpermission[$i][$k]['metodo'];
					for($n=0;$n<count($metodos_sidebar);$n++){
						if(is_array($permisos)){
							$tercio = $fullpermission[$i]['extension'][0].'|'.$fullpermission[$i][$k]['controlador'][0].'|'.$metodos_sidebar[$n][0];
							if(in_array($tercio,$permisos)){
								$activo = "checked='checked'";
							}else{
								$activo = "";
							}
						}else{
							$activo = "";
						}

					?>
					<div class="profile-activity clearfix" style="height:50px !important; text-indent:50px;">
						<div>
							<span style="float: left; position:relative;">
								<input <?php echo $activo; ?> id="<?=$fullpermission[$i]['extension'][0]?>-<?=$fullpermission[$i][$k]['controlador'][0]?>-<?=$metodos_sidebar[$n][0]?>" class="ace ace-switch ace-switch-6" type="checkbox" onclick="set_acl_tercio('<?=$fullpermission[$i]['extension'][0]?>-<?=$fullpermission[$i][$k]['controlador'][0]?>-<?=$metodos_sidebar[$n][0]?>')">
								<span class="lbl"></span>
							</span>
							<div class="time" style="text-indent:-40px;">
								<?=$metodos_sidebar[$n][1]?>
							</div>
						</div>
					</div>
					<?php
					}
					$count++;
					?>
					<script>
						inicializar_estados('<?=$fullpermission[$i]['extension'][0]?>','<?=$fullpermission[$i][$k]['controlador'][0]?>');
					</script>
				</div>
			</div>
			<?php
			}
		}
			?>
	</form>
	</section>
</div>