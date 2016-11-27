<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
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