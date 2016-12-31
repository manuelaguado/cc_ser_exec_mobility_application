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
					<input value="<?=$cliente['nombre']?>" type="text" class="form-control text-field" id="nombre" name="nombre" placeholder="Nombre(s)" autocomplete="off">
				  </div>										  
			</div>
		</div>
	</div>
	<input type="hidden" id="id_cliente" name="id_cliente" value="<?=$id_cliente?>"/>
	<div class="clearfix form-actions">
		<a class="btn btn-success" onclick="editar_cliente_tree('<?=$id_cliente?>','<?=$parent?>')">Editar</a>
		<a class="btn btn-success" onclick="return_form_add(<?=$parent?>)">cancelar</a>
		<i id="spinner_edit" class="fa fa-circle-o-notch fa-spin fa-3x fa-fw green hidden" style="position:relative; float:right;"></i>
	</div>
</div>