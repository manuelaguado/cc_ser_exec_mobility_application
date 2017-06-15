<ul class="nav nav-list">
	<?php
	if(
		($this->tiene_permiso('Operacion|solicitud')) OR
		($this->tiene_permiso('Operacion|programados')) OR
		($this->tiene_permiso('Operacion|cordon_kpmg')) OR
		($this->tiene_permiso('Operacion|cordon_ejnal')) OR
		($this->tiene_permiso('Operacion|activos')) OR
		($this->tiene_permiso('Operacion|inactivos')) OR
		($this->tiene_permiso('Operacion|suspendidas')) OR
		($this->tiene_permiso('Usuarios|logueados')) OR
		($this->tiene_permiso('Operacion|listado_completados')) OR
		($this->tiene_permiso('Operacion|listado_cancelados')) OR
		($this->tiene_permiso('Clientes|showtarifas')) OR
		($this->tiene_permiso('Egresosoperador|index'))

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
		if(
			($this->tiene_permiso('Operacion|listado_completados')) OR
			($this->tiene_permiso('Operacion|listado_cancelados')) OR
			($this->tiene_permiso('Egresosoperador|index')) OR
			($this->tiene_permiso('Ingresosoperador|index'))
		)
		{
		?>
		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-car" style="color:#3f8b00;"></i>
				<span class="menu-text">
					Viajes
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Ingresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>ingresosoperador');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> No procesados </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Ingresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>ingresosoperador/pausados');">
						<i class="menu-icon fa fa-taxi"></i>
						<span class="menu-text"> Pausados </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Ingresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>ingresosoperador/procesados');">
						<i class="menu-icon fa fa-money"><i class="fa fa-arrow-left fa_sup" aria-hidden="true"></i></i>
						<span class="menu-text"> Procesados </span>
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
						<span class="menu-text"> Viajes del ciclo </span>
					</a>
				</li>
				<?php
				}
				?>

				<?php
				if($this->tiene_permiso('Egresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>egresosoperador/tabuladosEnC12');">
						<i class="menu-icon fa fa-map-signs"></i>
						<span class="menu-text"> Por revisar C12 y T3 </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Ingresosoperador|index')){
				?>
				<li class="">
					<a href="#" class="dropdown-toggle">
						<i class="menu-icon fa fa-caret-right"></i>

						Archivo
						<b class="arrow fa fa-angle-down"></b>
					</a>

					<b class="arrow"></b>

					<ul class="submenu nav-show" style="display: none;">
						<li class="">
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>ingresosoperador/archivo');">
								<i class="menu-icon fa fa-archive"></i>
								<span class="menu-text"> Archivo </span>
							</a>

							<b class="arrow"></b>
						</li>
						<li class="">
							<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/listado_cancelados');">
								<i class="menu-icon fa fa-taxi"></i>
								<span class="menu-text"> Cancelados </span>
							</a>

							<b class="arrow"></b>
						</li>
					</ul>
				</li>
				<?php
				}
				?>
			</ul>
		</li>

		<li>
			<a class="dropdown-toggle" href="javascript:void(0)">
				<i class="menu-icon fa fa-money" style="color:#3f8b00;"></i>
				<span class="menu-text">
					Finanzas Operador
				</span>

				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu">
				<?php
				if($this->tiene_permiso('Egresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>egresosoperador');">
						<i class="menu-icon fa fa-money"><i class="fa fa-arrow-up fa_sup" aria-hidden="true"></i></i>
						<span class="menu-text"> Conceptos de cobro </span>
					</a>
				</li>
				<?php
				}
				?>
				<?php
				if($this->tiene_permiso('Ingresosoperador|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>ingresosoperador/papeletas');">
						<i class="menu-icon fa fa-file-pdf-o"></i>
						<span class="menu-text"> Papeletas </span>
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
				<?php
				if($this->tiene_permiso('Sistema|index')){
				?>
				<li>
					<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>sistema');">
						<i class="menu-icon fa fa-cogs"></i>
						<span class="menu-text">Sistema</span>
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
