		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<!-- #section:basics/sidebar.mobile.toggle -->
				<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
					<span class="sr-only">Toggle sidebar</span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>

					<span class="icon-bar"></span>
				</button>

				<!-- /section:basics/sidebar.mobile.toggle -->
				<div class="navbar-header pull-left" style="z-index:5;">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="floatstar">
						<div class="iconstar">
							<i class="icon-centralcar_star"></i>
						</div>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->

					<!-- /section:basics/navbar.toggle -->
				</div>
				<div class="blink_me" id="message-center"></div>
				<!-- #section:basics/navbar.dropdown -->
				<div class="navbar-buttons navbar-header pull-right" role="navigation">
					<ul class="nav ace-nav">

						<?php include('notificaciones.php'); ?>

						<!-- #section:basics/navbar.user_menu -->
						<li class="light-blue">
							<a data-toggle="dropdown" href="#" class="dropdown-toggle">
								<img id="avatar_top" class="nav-user-photo" src="plugs/timthumb.php?src=<?=$avatar_usr_circ?>&w=32&h=32&a=t" alt="Avatar" />
								<span class="user-info">
									<div id="name_top"><?=$usuario_name?></div>
									<span style="font-variant: small-caps; font-size:1em;"><?=strtolower($credenciales_top['rol'])?></span>
								</span>

								<i class="ace-icon fa fa-caret-down"></i>
							</a>

							<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
								<?php
								if(
									($this->tiene_permiso('Usuarios|index')) OR
									($this->tiene_permiso('Controllers|index'))
								)
								{
									if($this->tiene_permiso('Usuarios|index')){
									?>
									<li>
										<!-- <a href="<?=URL_APP?>usuarios"> -->
										<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios');">
											<i class="fa fa-users"></i>
											Control de usuarios
										</a>
									</li>
									<?php
									}
									if($this->tiene_permiso('Controllers|index')){
									?>
									<li>
										<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>controllers');">
											<i class="fa fa-key"></i>
											Controladores & Metodos
										</a>
									</li>

									<li class="divider"></li>

								<?php
									}
								}
								if($this->tiene_permiso('Usuarios|perfil')){
								?>
									<li>
										<!-- <a href="<?=URL_APP?>usuarios"> -->
										<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios/perfil');">
											<i class="menu-icon fa fa-user"></i>
											Perfil
										</a>
									</li>
								<?php
								}
								if(isset($_SESSION['token'])){
								?>
								<li>
									<a onclick="salir();" href="javascript:void(0)">
										<i class="fa fa-sign-out"></i>
										Salir
									</a>
								</li>
								<?php
								}
								?>
							</ul>
						</li>

						<!-- /section:basics/navbar.user_menu -->
					</ul>
				</div>
				<!-- /section:basics/navbar.dropdown -->
			</div><!-- /.navbar-container -->
		</div>
