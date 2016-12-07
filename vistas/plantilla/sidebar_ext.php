<?php

	include_once(URL_CONTROLADOR.'sidebar.php');
	$sidebar = new Sidebar();
	$extensiones = $sidebar->obtenerExtensiones();
	$extensiones = array_reverse($extensiones);

	//print("<pre>".print_r($extensiones,true)."</pre>");
	

	foreach($extensiones as $num1 => $valor1){
		
		?>
		<ul class="nav nav-list" data="manuel">
			<li class="open">
				<a href="javascript:void(0)" class="dropdown-toggle">
					<i class="<?=$extensiones[$num1]['extension'][2]?>"></i>
					<span class="menu-text"> <?=$extensiones[$num1]['extension'][1]?> </span>
					<b class="arrow icon-angle-down"></b>
				</a>
				<ul class="submenu" style="display: none;">
					<li class="open">
					<?php
					foreach($valor1 as $num2 => $valor2){
						if(is_array($extensiones[$num1][$num2]['controlador'])){
						?>
						<a href="<?=URL_CONTROLLER_EXT.strtolower($extensiones[$num1]['extension'][0]).'/'.strtolower($extensiones[$num1][$num2]['controlador'][0])?>" class="dropdown-toggle">
							<i class="icon-double-angle-right"></i>
							<?=$extensiones[$num1][$num2]['controlador'][1]?>
							<b class="arrow icon-angle-down"></b>
						</a>
						<?php
						}
						if(is_array($extensiones[$num1][$num2]['metodo'])){
						?>
						<ul class="submenu" style="display: none;">
							<li class="open">
						<?php
							foreach($extensiones[$num1][$num2]['metodo'] as $num3 => $valor3){
							?>
								<a href="javascript:void(0)" onclick="carga_archivo('contenedor_principal','<?= URL_CONTROLLER_EXT.strtolower($extensiones[$num1]['extension'][0]).'/'.strtolower($extensiones[$num1][$num2]['controlador'][0]).'/'.strtolower($extensiones[$num1][$num2]['metodo'][$num3][0])?>');"> 
									<i class="<?=$extensiones[$num1][$num2]['metodo'][$num3][2]?>"></i>
									<?=$extensiones[$num1][$num2]['metodo'][$num3][1]?>
								</a>
							<?php
							}
							?>
							</li>
						</ul>
						<?php								
						}
					}
					?>
					</li>
				</ul>
			</li>
		</ul>	
	<?php		
	}
	unset($extensiones);
?>