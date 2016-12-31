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
			<?=$cliente['nombre']?>
			<small>
				<i class="ace-icon fa fa-angle-double-right"></i>
				Administración de usuarios
			</small>
		</h1>
	</div><!-- /.page-header -->
	<div class="row">
		<div class="col-xs-12">
			<!-- PAGE CONTENT BEGINS -->
			<div class="row">
				<div class="col-sm-6">
					<!-- INICIO DEL ARREGLO -->
					<form id="new_order" role="form">
						<div class="dd" id="nestable">
							<ol class="dd-list" id="ruta_ensamble">
							<?php
							function for_children($childrens, $parent){
								foreach($childrens as $children){
								?>
									<li class="dd-item item-blue2" data-id="<?=$children['id_cliente']?>" id="dataClientNestable_<?=$children['id_cliente']?>">
										<div class="dd-handle">
											<div id="nombre_nestable_<?=$children['id_cliente']?>"><?=$children['nombre']?></div>
										</div>
										<div class="pull-right action-buttons" style="position:relative; top:-33px; left:-13px; z-index:500">
											<i id="spinnerClient_<?=$children['id_cliente']?>" class="fa fa-circle-o-notch fa-spin fa-fw blue hidden"></i>
											<?php
											if(Controller::tiene_permiso('Clientes|clientpage')){
											?>
												<a class="blue" href="#" onclick="carga_archivo('contenedor_principal','<?=URL_APP?>clientes/clientpage/<?=$children['id_cliente']?>|<?=$parent?>|<?=$children['padre']?>');">
													<i class="ace-icon fa fa-gear bigger-130"></i>
												</a>
											<?php
											}
											?>
											<a class="blue" href="#" onclick="getFormClientEdit('<?=$children['id_cliente']?>','<?=$parent?>')">
												<i class="ace-icon fa fa-pencil bigger-130"></i>
											</a>
											<a class="red" href="#" onclick="deleteClient('<?=$children['id_cliente']?>','<?=$children['padre']?>')">
												<i class="ace-icon fa fa-trash-o bigger-130"></i>
											</a>
										</div>
										<?php
										bucle_children($children['childrens'], $parent);
										?>
									</li>
								<?php
								}
							}
							function bucle_children($childrens, $parent){
								$open_ol = '';
								if(count($childrens) > 0){
									if($open_ol != 1){
										echo '<ol class="dd-list">'; 
										$open_ol = 1; 
										$close_ol = '';
									}
									for_children($childrens, $parent);
									if( $close_ol != 1){
										echo '</ol>'; 
										$close_ol = 1; 
										$open_ol='';
									}
								}	
							}							
							for_children($childrens, $parent);
							?>
							</ol>
						</div>
						<input type="hidden" id="newjson" name="newjson" value="" />
						<input type="hidden" id="parent" name="parent" value="<?=$parent?>"/>
					</form>
					<!-- FINAL DEL ARREGLO -->
				</div>
				<?php
				if($this->tiene_permiso('Clientes|add_client_children')){
				?>
				<form id="cliente_nuevo" role="form">
					<div class="col-sm-6">
						<div class="panel-body">			
							<div class="row">
								<div class="col-md-6">
									  <div class="form-group">
										<label for="cat_tipocliente">Tipo de Cliente</label>
										  <select  class="form-control" id="cat_tipocliente" name="cat_tipocliente">
											<?php echo $tiposClientes; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="id_rol">Rol</label>
										  <select class="form-control" id="id_rol" name ="id_rol">
											<?php echo $roles; ?>
										  </select>
									  </div>	
								</div>
								<div class="col-md-6">
									  <div class="form-group">
										<label for="cat_statuscliente">Status del Cliente</label>
										  <select  class="form-control" id="cat_statuscliente" name="cat_statuscliente">
											<?php echo $satatusCliente; ?>
										  </select>
									  </div>
									  <div class="form-group">
										<label for="nombre">Nombre</label>
										<input type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre(s)" autocomplete="off">
									  </div>										  
								</div>
							</div>
						</div>
						<input type="hidden" id="padre" name="padre" value="<?=$parent?>"/>
						<div class="clearfix form-actions">
							<a class="btn btn-success" onclick="agregar_cliente_tree()">Agregar</a>
						</div>
					</div>
				</form>
				<?php
				}else{
				?>
				<div class="col-sm-6"></div>
				<?php
				}
				?>
				<div class="col-sm-12">
					<div class="clearfix form-actions">
						<div class="col-sm-3">
							<button class="btn btn-primary" onclick="guardar_disposicion()">Guardar disposición</button>
							<i id="spinner_change" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw blue hidden" style="position:relative; float:right;"></i>
						</div>
					</div>				
				</div>
			</div><!-- PAGE CONTENT ENDS -->
		</div><!-- /.col -->
	</div><!-- /.row -->
</div><!-- /.page-content -->		
<script type="text/javascript">
$(document).ready(function()
{
    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
        } else {
            output.val('JSON browser support required for this demo.');
        }
    };
    $('#nestable').nestable({
        group: 1,
		maxDepth: 9
    })
    .on('change', updateOutput);
    updateOutput($('#nestable').data('output', $('#newjson')));

});
</script>
