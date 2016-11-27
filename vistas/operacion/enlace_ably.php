<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="col-xs-12">
	<div class="row">
		<div class="col-xs-12">
			<h3 class="header smaller lighter blue">Operadores enlazados: <?=count($activos->items)?> (reales: <?=(count($activos->items))-$extras?>)</h3>
				<?php
				if($this->tiene_permiso('Operacion|broadcast_all')){
				?>
				<div class="col-md-12 column menu_header_content">
					<button class="btn btn-ar btn-primary" type="button" onclick="broadcast_all();">BroadCast</button>
				</div>
				<?php
				}
				?>
			<div>
				<table id="dynamic-table" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>N°</th>
							<th>Operador</th>
							<th>Sessiones</th>
							<th>Nombre</th>
							<th>ID</th>
							<th>Time</th>
							<th>Connexión</th>
							<th>Estado</th>
							<th>&nbsp;</th>
						</tr>
					</thead>

					<tbody>
					<?php
					foreach($enlazados as $num => $oper){
						if(in_array($oper['clientId'],$duplicado)){ $bgc = $cuantas[$oper['clientId']];}else{$bgc = '';}
					?>
						<tr>
							<td><?=$oper['num']?></td>
							<td><?=$oper['clientId']?></td>
							<td><?=$bgc?></td>
							<td><?=$oper['nombre']?></td>
							<td><?=$oper['id']?></td>
							<?php
							$date = new DateTime();
							$date->setTimestamp(($oper['timestamp']/1000));
							?>
							<td><?=$date->format('Y-m-d H:i:s')?></td>
							<td><?=$oper['connectionId']?></td>
							<td>
							<?php
							if($oper['c1orc2']=='C2'){
								echo '<i class="ace-icon fa fa-exclamation-triangle red bigger-130"></i><span style="font-size:1.3em;" class="red">'.$oper['c1orc2'].'</span>';
							}else if ($oper['c1orc2']=='C1') {
								echo '<i class="ace-icon fa fa-check bigger-110 green bigger-130"></i><span style="font-size:1.3em;" class="green">'.$oper['c1orc2'].'</span>';
							}else{
								echo 'IND';
							}
							?>
							</td>
							<td>
							<?php
							if($this->tiene_permiso('Operacion|mensajeria')){
							?>
								<a onclick="modal_mensajeria('<?=$oper['clientId']?>')" data-rel="tooltip" data-original-title="Enviar mensaje">
									<i class="fa fa-comment-o" style="font-size:1.8em; color:green;"></i>
								</a>
							<?php
							}
							if($this->tiene_permiso('Operacion|check_standinLine')){
							?>	
								<a onclick="check_standinLine('<?=$oper['clientId']?>')" data-rel="tooltip" data-original-title="Lista de verificacion">
									<i class="fa fa-tasks" style="font-size:1.8em; color:green;"></i>
								</a>
							<?php
							}if($this->tiene_permiso('Operadores|historia')){
							?>	
								<a onclick="historia_operador('<?=$oper['clientId']?>')" data-rel="tooltip" data-original-title="Historia">
									<i class="fa fa-clock-o" style="font-size:1.8em; color:green;"></i>
								</a>
							<?php
							}if($this->tiene_permiso('Operadores|set_page_remotly')){
							?>	
								<a onclick="set_page_remotly('<?=$oper['clientId']?>')" data-rel="tooltip" data-original-title="Establecer pantalla remota">
									<i class="fa fa-mobile" style="font-size:1.8em; color:green;"></i>
								</a>
							<?php
							}if($this->tiene_permiso('Gps|geolocalizacion')){
							?>	
								<a onclick="modal_geolocalizacion('<?=$oper['clientId']?>');" data-rel="tooltip" data-original-title="Geolocalizar Unidad">
									<i class="fa fa-globe" style="font-size:1.8em; color:green;"></i>
								</a>
							<?php
							}
							?>
							</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
$('[data-rel=tooltip]').tooltip();
</script>