				<div class="sidebar-shortcuts" id="sidebar-shortcuts">
					<div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
						<?php
						if(
							($this->tiene_permiso('Operacion|cordon_kpmg'))
						)
						{
						?>					
											<a onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operacion/cordon_kpmg');" class="btn btn-operacion">
												<i class="menu-icon fa fa-cog fa-spin"></i>
											</a>
						<?php
						}
						if(
							($this->tiene_permiso('Operadores|index'))
						)
						{
						?>
											<a class="btn btn-bases" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>operadores');">
												<i class="menu-icon fa fa-building"></i>
											</a>
						<?php
						}
						if(
							($this->tiene_permiso('Clientes|index'))
						)
						{
						?>
											<a class="btn btn-clientes" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes');">
												<i class="menu-icon fa fa-users"></i>
											</a>
						<?php
						}
						if(
							($this->tiene_permiso('Usuarios|index'))
						)
						{
						?>
											<a class="btn btn-configuracion" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>usuarios');">
												<i class="menu-icon fa fa-cogs"></i>
											</a>
						<?php
						}
						?>
						<!-- /section:basics/sidebar.layout.shortcuts -->
					</div>

					<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
						<?php
						if(
						($this->tiene_permiso('Operacion|index'))
						)
						{
						?>					
							<span class="btn btn-operacion"></span>
						<?php
						}
						if(
						($this->tiene_permiso('Unidades|index')) OR
						($this->tiene_permiso('Operadores|index')) OR
						($this->tiene_permiso('Bases|index'))
						)
						{
						?>
							<span class="btn btn-bases"></span>
						<?php
						}
						if(
						($this->tiene_permiso('Clientes|index')) OR
						($this->tiene_permiso('Clientes|allthem'))
						)
						{
						?>
							<span class="btn btn-clientes"></span>
						<?php
						}
						if(
						($this->tiene_permiso('Usuarios|index')) OR
						($this->tiene_permiso('Controllers|index'))
						)
						{
						?>
							<span class="btn btn-configuracion"></span>
						<?php
						}
						?>
					</div>
				</div><!-- /.sidebar-shortcuts -->			