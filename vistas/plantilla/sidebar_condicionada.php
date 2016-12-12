<ul class="nav nav-list">
	<?php
	if(
		($this->tiene_permiso('Operacion|solicitud')) OR
		($this->tiene_permiso('Operacion|programados')) OR
		($this->tiene_permiso('Operacion|panorama_c1')) OR 
		($this->tiene_permiso('Operacion|panorama_c8')) OR 
		($this->tiene_permiso('Operacion|panorama_a11')) OR 
		($this->tiene_permiso('Operacion|panorama_kpmg')) OR 
		($this->tiene_permiso('Operacion|panorama_ejnal')) OR 
		($this->tiene_permiso('Operacion|panorama_tb')) OR		
		($this->tiene_permiso('Operacion|cordon_kpmg')) OR 
		($this->tiene_permiso('Operacion|cordon_ejnal')) OR 
		($this->tiene_permiso('Operacion|tiempo_base'))	OR	
		($this->tiene_permiso('Operacion|enlazados')) OR
		($this->tiene_permiso('Operacion|activos')) OR
		($this->tiene_permiso('Operacion|inactivos')) OR
		($this->tiene_permiso('Operacion|suspendidas')) OR
		($this->tiene_permiso('Usuarios|logueados')) OR
		($this->tiene_permiso('Operacion|listado_completados')) OR
		($this->tiene_permiso('Operacion|listado_cancelados')) OR
		($this->tiene_permiso('Clientes|showtarifas'))
		
	)
	{
	?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-cog fa-spin" style="color:#72F209;"></i>
				<span class="menu-text">
					Operación
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>			
			
			
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Operacion|solicitud')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/solicitud');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Solicitud </span>
					</a>
				</li>
				<?php
				}
				?>

				<?php
				if($this->tiene_permiso('Operacion|listado_completados')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/listado_completados');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Completados </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|listado_cancelados')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/listado_cancelados');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Cancelados </span>
					</a>
				</li>
				<?php
				}
				?>
				
				<?php
				if($this->tiene_permiso('Operacion|programados')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/programados');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Programados </span>
					</a>
				</li>
				<?php
				}
				?>			
				<?php
				if(
					($this->tiene_permiso('Operacion|panorama_c1')) OR 
					($this->tiene_permiso('Operacion|panorama_c8')) OR 
					($this->tiene_permiso('Operacion|panorama_a11')) OR 
					($this->tiene_permiso('Operacion|panorama_kpmg')) OR 
					($this->tiene_permiso('Operacion|panorama_ejnal')) OR 
					($this->tiene_permiso('Operacion|panorama_tb'))
				){
				?>	
				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						Panoramas
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu">
						<?php
						if($this->tiene_permiso('Operacion|panorama_c1')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_c1');">
								<i class="menu-icon fa fa-map-marker orange"></i>
								Unidades en C1
							</a>
						</li>
						<?php
						}
						?>
						<?php
						if($this->tiene_permiso('Operacion|panorama_c8')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_c8');">
								<i class="menu-icon fa fa-map-marker orange"></i>
								Unidades en C8
							</a>
						</li>
						<?php
						}
						?>
						<?php
						if($this->tiene_permiso('Operacion|panorama_a11')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_a11');">
								<i class="menu-icon fa fa-map-marker orange"></i>
								Unidades en A11
							</a>
						</li>
						<?php
						}
						?>
						<?php
						if($this->tiene_permiso('Operacion|panorama_kpmg')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_kpmg');" class="dropdown-toggle">
								<i class="menu-icon fa fa-map-marker orange"></i>
								KPMG
								<?php
								if($this->tiene_permiso('Operacion|panorama_kpmg_cordon')){
								?>
									<b class="arrow fa fa-angle-down"></b>
								<?php
								}
								?>
							</a>
							<?php
							if($this->tiene_permiso('Operacion|panorama_kpmg_cordon')){
							?>
							<b class="arrow"></b>
							<ul class="submenu">
								<li class="">
									<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_kpmg_cordon');">
										<i style="font-size:1.5em; position:relative; top:-5px;" class="icon-centralcar_geolocalizacion"></i>
										Cordón
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
							<?php
							}
							?>
						</li>
						<?php
						}
						?>
						<?php
						if($this->tiene_permiso('Operacion|panorama_ejnal')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_ejnal');" class="dropdown-toggle">
								<i class="menu-icon fa fa-map-marker orange"></i>
								Ejercito Nacional
								<?php
								if($this->tiene_permiso('Operacion|panorama_ejnal_cordon')){
								?>
									<b class="arrow fa fa-angle-down"></b>
								<?php
								}
								?>								
							</a>
							<?php
							if($this->tiene_permiso('Operacion|panorama_ejnal_cordon')){
							?>
							<b class="arrow"></b>
							<ul class="submenu">
								<li class="">
									<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_ejnal_cordon');">
										<i style="font-size:1.5em; position:relative; top:-5px;" class="icon-centralcar_geolocalizacion"></i>
										Cordón
									</a>

									<b class="arrow"></b>
								</li>
							</ul>
							<?php
							}
							?>							
						</li>
						<?php
						}
						?>
						<?php
						if($this->tiene_permiso('Operacion|panorama_tb')){
						?>						
						<li>
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/panorama/panorama_tb');">
								<i class="menu-icon fa fa-map-marker orange"></i>
								Tiempo a la Base
							</a>
						</li>
						<?php
						}
						?>
					</ul>
				</li>
				<?php
				}
				?>			
				
				<?php
				if($this->tiene_permiso('Operacion|cordon_kpmg') || $this->tiene_permiso('Operacion|cordon_ejnal')){
				?>
				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						Cordón
						<b class="arrow fa fa-angle-down"></b>
					</a>
					<b class="arrow"></b>
					<ul class="submenu nav-hide" style="display: none;">
						<?php
						if($this->tiene_permiso('Operacion|cordon_kpmg')){
						?>					
						<li class="">
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/cordon_kpmg');">
								<i class="menu-icon fa fa-taxi green"></i>
								KPMG
							</a>
							<b class="arrow"></b>
						</li>
						<?php
						}
						?>						
						<?php
						if($this->tiene_permiso('Operacion|cordon_ejnal')){
						?>											
						<li class="">
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/cordon_ejnal');">
								<i class="menu-icon fa fa-taxi green"></i>
								Ejercito Nacional
							</a>
							<b class="arrow"></b>
						</li>
						<?php
						}
						?>
					</ul>
				</li>				
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|tiempo_base')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/tiempo_base');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Tiempo a la base </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|enlazados')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/enlazados');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Realtime </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|activos')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/activos');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Activos - C1 </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|inactivos')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/inactivos');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Inactivos - C2 </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operacion|suspendidas')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/suspendidas');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Suspendidas - F6 </span>
					</a>
				</li>
				<?php
				}
				?>
				<li class="divider"></li>
			</ul>
		</li>
	<?php
	}
	?>
	<?php
	if(
		($this->tiene_permiso('Unidades|index')) OR
		($this->tiene_permiso('Operadores|index')) OR
		($this->tiene_permiso('Bases|index')) OR
		($this->tiene_permiso('Telefonia|index')) OR
		($this->tiene_permiso('Operadores|listado_vigente'))
	)
	{
	?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-building" style="color:#C60909;"></i>
				<span class="menu-text">
					Listas
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>			
			
			
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Unidades|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>unidades');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Unidades </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operadores|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operadores');">
						<i class="menu-icon fa fa-users"></i>
						<span class="menu-text"> Operadores </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Bases|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>bases');">
						<i class="menu-icon fa fa-building"></i>
						<span class="menu-text"> Bases </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Telefonia|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>telefonia');">
						<i class="menu-icon fa fa-mobile bigger-150"></i>
						<span class="menu-text"> Telefonía </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Operadores|listado_vigente')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operadores/listado_vigente');">
						<i class="menu-icon fa fa-mobile bigger-150"></i>
						<span class="menu-text"> Listado operadores </span>
					</a>
				</li>
				<?php
				}
				?>
				<li class="divider"></li>
			</ul>
		</li>
	<?php
	}
	?>
	<?php
	if(
		($this->tiene_permiso('Clientes|index')) OR
		($this->tiene_permiso('Clientes|allthem')) OR
		($this->tiene_permiso('Clientes|showtarifas'))
	)
	{
	?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-users" style="color: #09B4F2"></i>
				<span class="menu-text">
					Clientes
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>			
			
			
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Clientes|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes');">
						<i class="menu-icon fa fa-users"></i>
						<span class="menu-text"> Cuentas </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Clientes|showtarifas')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes/showtarifas');">
						<i class="menu-icon fa fa-credit-card-alt"></i>
						<span class="menu-text"> Tarifas </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Clientes|allthem')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes/allthem');">
						<i class="menu-icon fa fa-users"></i>
						<span class="menu-text"> Todos los Clientes </span>
					</a>
				</li>
				<?php
				}
				?>
				<li class="divider"></li>
			</ul>
		</li>
	<?php
	}
	?>
	
	<?php
	if(
		($this->tiene_permiso('Gps|logger')) OR
		($this->tiene_permiso('Gps|localizar')) OR
		($this->tiene_permiso('Gps|gps_activo'))
	)
	{
	?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-map-marker" style="color: #09B4F2"></i>
				<span class="menu-text">
					Control GPS
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>			
			
			
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Gps|logger')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>gps/logger');">
						<i class="menu-icon fa fa-map-o"></i>
						<span class="menu-text"> Logger </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Gps|localizar')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>gps/localizar');">
						<i class="menu-icon fa fa-map-o"></i>
						<span class="menu-text"> Localizar </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Gps|gps_activo')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>gps/gps_activo');">
						<i class="menu-icon fa fa-map-o"></i>
						<span class="menu-text"> GPS Activo </span>
					</a>
				</li>
				<?php
				}
				?>
				<li class="divider"></li>
			</ul>
		</li>
	<?php
	}
	?>	
	
	<?php
	if(
		($this->tiene_permiso('Usuarios|index')) OR
		($this->tiene_permiso('Controllers|index')) OR
		($this->tiene_permiso('Usuarios|logueados'))
	)
	{
	?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-cogs" style="color: #D39000;"></i>
				<span class="menu-text">
					Configuración
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>			
			
			
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Usuarios|index')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios');">
						<i class="menu-icon fa fa-users"></i>
						<span class="menu-text"> Control de usuarios </span>
					</a>
				</li>
				<?php
				}
				if($this->tiene_permiso('Usuarios|logueados')){
				?>								
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios/logueados');">
						<i class="menu-icon fa fa-lock"></i>
						<span class="menu-text"> Control de logins </span>
					</a>
				</li>
				<?php
				}
				if($this->tiene_permiso('Controllers|index')){
				?>	
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>controllers');">
						<i class="menu-icon fa fa-key"></i>
						<span class="menu-text">Controladores</span>
					</a>
				</li>
				
				<?php
				}
				if($this->tiene_permiso('Catalogo|index')){
				?>	
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>catalogo');">
						<i class="menu-icon fa fa-book"></i>
						<span class="menu-text">Catálogo</span>
					</a>
				</li>
				
				<?php
				}
				?>
			</ul>
		</li>
	<?php
	}
	?>	
</ul>