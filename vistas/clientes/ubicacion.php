<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
.profile-info-row{
	width:100% !important;
}
.profile-info-value{
	width:100% !important;
}
</style>
<div class="page-content">
	<!-- /section:settings.box -->
	<div class="page-header">
		<?php
		if($this->tiene_permiso('Clientes|index')){
		?>
		<div style="position:relative; float:left; font-size:3em; top:-17px; cursor:pointer">
			<a onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes');" href="#">
				<i class="fa fa-caret-left orange">&nbsp;</i>
			</a>
		</div>
		<?php
		}
		?>	
		<h1>
			 Direcciones&nbsp;
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				<?=utf8_decode($cliente['nombre'])?>
			</small>
		</h1>
	</div><!-- /.page-header -->
</div>
<?php
if($this->tiene_permiso('Clientes|add_direccion')){
?>	
<div class="col-md-12 column menu_header_content">
	<a class="btn btn-ar btn-primary" type="button" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes/add_direccion/<?=$id_cliente?>');">Nueva Dirección</a>
</div>
<?php
}
?>
<div class="col-xs-12">
	<!-- PAGE CONTENT BEGINS -->
	<div class="row">
		<?php
		$colors = array('307ECC','FFC657','404040','82AF6F','848484','7B68B0','84B4DD','FEE188','EDFFAF','BDCA13','6F00CA','BBCA00');
		foreach($direcciones as $num => $direccion){
		?>
		<div class="col-xs-6 col-sm-4 pricing-box" id="box_address_<?=$direccion['id_datos_fiscales']?>">
		<style>
			.widget-color-custom<?=$num?> > .widget-header {
				background: #<?=$colors[$num]?> none repeat scroll 0 0;
				border-color: #<?=$colors[$num]?>;
			}
			.widget-color-custom<?=$num?> {
				border-color: #<?=$colors[$num]?>;
			}			
		</style>
			<div class="widget-box widget-color-custom<?=$num?>">
				<div class="widget-header">
					<h5 class="widget-title bigger lighter" id="titulo_<?=$direccion['id_datos_fiscales']?>">
					<?php 
					if($direccion['predeterminar'] == 0){$pred = '';}
					elseif($direccion['predeterminar'] == 1){$pred='<span id="pred">(Predeterminada)</span>';}
					if($direccion['rfc'] != ''){
					?>
					Dirección Fiscal <?=$pred?>
					<?php
					}else{
					?>
					Dirección Fisica
					<?php
					}
					?>
					</h5>
				</div>
				<div class="widget-body">
					<div class="widget-main">
							<?php
							if($direccion['rfc'] != ''){
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> RFC </div>

								<div class="profile-info-value">
									<i class="fa fa-gavel light-orange bigger-110"></i>
									<span id="username"><?=$direccion['rfc']?></span>
								</div>
							</div>
							<?php
							}
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> Calle </div>

								<div class="profile-info-value">
									<i class="fa fa-map-marker light-orange bigger-110"></i>
									<span id="username"><?=$direccion['calle']?></span>
								</div>
							</div>

							<div class="profile-info-row">
								<div class="profile-info-name"> Exterior </div>

								<div class="profile-info-value">
									<i class="fa fa-sign-out light-orange bigger-110"></i>
									<span id="country"><?=$direccion['num_ext']?></span>
								</div>
							</div>
							<?php
							if($direccion['num_int'] != ''){
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> Interior </div>

								<div class="profile-info-value">
									<i class="fa fa-sign-in light-orange bigger-110"></i>
									<span id="country"><?=$direccion['num_int']?></span>
								</div>
							</div>
							<?php
							}
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> <?=$direccion['d_tipo_asenta']?> </div>

								<div class="profile-info-value">
									<span id="country"><?=$direccion['asentamiento']?></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Ciudad </div>

								<div class="profile-info-value">
									<span id="country"><?=$direccion['ciudad']?></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Municipio </div>

								<div class="profile-info-value">
									<span id="country"><?=$direccion['municipio']?></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Estado </div>

								<div class="profile-info-value">
									<span id="country"><?=$direccion['estado']?></span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> CP </div>

								<div class="profile-info-value">
									<span id="country"><?=$direccion['codigo_postal']?></span>
								</div>
							</div>
							<?php
							if($direccion['telefono'] != ''){
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> Teléfono </div>

								<div class="profile-info-value">
									<i class="fa fa-phone light-orange bigger-110"></i>
									<a href="tel://<?=$direccion['telefono']?>">
										<span><?=$direccion['telefono']?></span>
									</a>
								</div>
							</div>
							<?php
							}
							if($direccion['celular'] != ''){
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> Celular </div>

								<div class="profile-info-value">
									<i class="fa fa-mobile light-orange bigger-110"></i>
									<a href="tel://<?=$direccion['celular']?>">
										<span id="signup"><?=$direccion['celular']?></span>
									</a>
								</div>
							</div>
							<?php
							}
							if($direccion['correo'] != ''){
							?>
							<div class="profile-info-row">
								<div class="profile-info-name"> Correo </div>

								<div class="profile-info-value">
									<i class="fa fa-envelope light-orange bigger-110"></i>
									<a href="mailto:<?=$direccion['correo']?>" target="_top">
										<span id="login"><?=$direccion['correo']?></span>
									</a>
								</div>
							</div>
							<?php
							}
							?>
					</div>
					<div class="widget-header">
						<span class="widget-toolbar">
							<i id="boxload_<?=$direccion['id_datos_fiscales']?>" class="ace-icon fa fa-refresh fa-spin bigger-110 blue hide"></i>
							<?php
							if(Controller::tiene_permiso('Clientes|predeterminarUbicacion')){
							?>
							<a onclick="predeterminar_box_adr(<?=$direccion['id_datos_fiscales']?>,<?=$id_cliente?>);" data-rel="tooltip" data-original-title="Predeterminar" class="tooltip-success" data-action="settings" href="#">
								<i class="ace-icon fa fa-university"></i>
							</a>
							<?php
							}
							if(Controller::tiene_permiso('Clientes|eliminarUbicacion')){
							?>
							<a onclick="eliminar_box_adr(<?=$direccion['id_datos_fiscales']?>);" data-rel="tooltip" data-original-title="Eliminar" class="tooltip-success" data-action="reload" href="#">
								<i class="ace-icon fa fa-times"></i>
							</a>
							<?php
							}
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php
		}
		?>
	</div>
	<div class="space-24"></div>
</div>
<script>
$('[data-rel=tooltip]').tooltip();
</script>