<div class="page-content">
	<div id="user-profile-3" class="user-profile row">
		<div class="col-sm-offset-1 col-sm-10">

			<div class="space"></div>

			<form class="form-horizontal" id="editar_perfil">
				<div class="tabbable">
					<ul class="nav nav-tabs padding-16">
						<li class="active">
							<a data-toggle="tab" href="#edit-basic">
								<i class="green icon-edit bigger-125"></i>
								Información básica
							</a>
						</li>

						<li>
							<a data-toggle="tab" href="#edit-settings">
								<i class="purple icon-cog bigger-125"></i>
								Configuración
							</a>
						</li>

						<li>
							<a data-toggle="tab" href="#edit-password">
								<i class="blue icon-key bigger-125"></i>
								Contraseña
							</a>
						</li>
					</ul>

					<div class="tab-content profile-edit-tab-content">
						<div id="edit-basic" class="tab-pane in active">
							<div class="row">
								<div class="space-10"></div>
								<?php
								if($this->tiene_permiso('Usuarios|upload_avatar')){
								?>
								<div class="col-xs-12 col-sm-4">

									<label class="ace-file-input ace-file-multiple" id="dropbox">
										<span data-title="Cambiar Avatar" class="ace-file-container">
											<span data-title="No File ..." class="ace-file-name">
												<i class=" ace-icon ace-icon fa fa-cloud-upload"></i>
											</span>
										</span>
										<a href="#" class="remove">
											<i class=" ace-icon fa fa-times"></i>
										</a>
									</label>
									
									<?php
									if ($perfil['avatar']){
									?>
									<div id="avatar_actual">
										<label class="col-sm-12">Actual Avatar:</label><br>
										<center><img src="plugs/timthumb.php?src=tmp/<?=$avatar?>&w=230"></center>
									</div>
									<?php
									}else{
									?>
									<div id="avatar_actual"></div>
									<?php
									}
									?>
								</div>
								
								<div class="vspace-xs"></div>
								<?php
								}
								?>
								<div class="col-xs-12 col-sm-8">
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-username">Nombre de usuario</label>

										<div class="col-sm-8">
											<input readonly class="col-xs-12 col-sm-10" type="text" id="usuario" name="usuario" placeholder="Usuario" value="<?=$usuario['usuario']?>" />
										</div>
									</div>

									<div class="space-4"></div>

									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-first">Nombre</label>

										<div class="col-sm-8">
											<input class="col-xs-12 col-sm-10" type="text" id="nombres" name="nombres" placeholder="Nombre (s)" value="<?=$usuario['nombres']?>" /><br><br>
											<input class="col-xs-12 col-sm-10" type="text" id="apellido_paterno" name="apellido_paterno" placeholder="apellido paterno" value="<?=$usuario['apellido_paterno']?>" /><br><br>
											<input class="col-xs-12 col-sm-10" type="text" id="apellido_materno" name="apellido_materno" placeholder="apellido materno" value="<?=$usuario['apellido_materno']?>" /><br><br>
										</div>
									</div>
									
									<div class="form-group">
										<label class="col-sm-4 control-label no-padding-right" for="form-field-first">Correo</label>

										<div class="col-sm-8">
											<input class="col-xs-12 col-sm-10" type="email" id="correo" name="correo" placeholder="Correo" value="<?=$usuario['correo']?>" />
										</div>
									</div>
									
								</div>
							</div>
							<div class="space-4"></div>
						</div>

						<div id="edit-settings" class="tab-pane">
							<div class="space-10"></div>

							<div>
								<label class="inline">
									<input <?php $perfil['activar_paginado'] == 't' ? print 'checked' : print  '' ;?> type="checkbox" name="activar_paginado" id="activar_paginado" class="ace" />
									<span class="lbl">Establecer el páginado de forma predeterminada </span>
								</label>

								<label class="inline">
									<span class="space-2 block"></span>

									en
									<input type="text" id="paginacion" name="paginacion" class="input-mini" maxlength="3" value="<?=$perfil['paginacion']?>"/>
									registros por página
								</label>
							</div>
						</div>

						<div id="edit-password" class="tab-pane">
							<div class="space-10"></div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-pass1">Nueva contraseña</label>

								<div class="col-sm-9">
									<input type="password" id="password" name="password" value="no_seas_miron" />
								</div>
							</div>

							<div class="space-4"></div>

							<div class="form-group">
								<label class="col-sm-3 control-label no-padding-right" for="form-field-pass2">Confirmar contraseña</label>

								<div class="col-sm-9">
									<input type="password" id="password2" name="password2" value="no_seas_miron" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php
				if($this->tiene_permiso('Usuarios|editar_perfil')){
				?>
				<div class="clearfix form-actions">
					<div class="col-md-offset-3 col-md-9">
						<button onclick="editar_perfil();" class="btn btn-info" type="button">
							<i class="icon-ok bigger-110"></i>
							Guardar
						</button>
					</div>
				</div>
				<?php
				}
				?>
			</form>
		</div><!-- /span -->
	</div><!-- /user-profile -->
</div><!-- /.page-content -->
<script>
/*file dropbox*/
$(function(){
	var dropbox = $('#dropbox'),
		message = $('.message', dropbox);
	dropbox.filedrop({
		paramname:'pic',
		maxfiles: 1,
    	maxfilesize: 2,
		url: url_app + 'usuarios/upload_avatar',
		uploadFinished:function(i,file,response){
			$.data(file).addClass('done');
			$('#avatar_actual').html('<label class="col-sm-12">Actual Avatar:</label><br><center><img src="plugs/timthumb.php?src=tmp/'+response['status']+'&w=230"></center>');
			$('#avatar_top').attr('src','plugs/timthumb.php?src=tmp/' + response['status'] + '&w=32&h=32&a=t');
		},
    	error: function(err, file) {
			switch(err) {
				case 'BrowserNotSupported':
					showMessage('Su explorador no soporta carga de archivos HTML5!');
					break;
				case 'TooManyFiles':
					alerta('Alerta!','Demasiados archivos arrastre de montos de 10 maximo');
					break;
				case 'FileTooLarge':
					alerta('Alerta!','El archivo '+file.name+' es muy grande! 2Mb maximo permitido');
					break;
				default:
					break;
			}
		},
		beforeEach: function(file){
			if(!file.type.match(/^image\//)){
				alerta('Alerta!','Solo se permiten imagenes!');
				return false;
			}
		},
		uploadStarted:function(i, file, len){
			createImage(file);
		},
		progressUpdated: function(i, file, progress) {
			$.data(file).find('.progress').width(progress);
		}
	});
	var template = '<div class="preview">'+
						'<span class="imageHolder">'+
							'<img />'+
							'<span class="uploaded"></span>'+
						'</span>'+
						'<div class="progressHolder">'+
							'<div class="progress"></div>'+
						'</div>'+
					'</div>'; 
	function createImage(file){
		var preview = $(template), 
			image = $('img', preview);
		var reader = new FileReader();
		image.width = 100;
		image.height = 100;
		reader.onload = function(e){
			image.attr('src',e.target.result);
		};
		reader.readAsDataURL(file);
		message.hide();
		//preview.appendTo(dropbox);
		$(dropbox).html(preview);
		$.data(file,preview);
	}
	function showMessage(msg){
		message.html(msg);
	}
});
/*fin file dropbox*/
</script>