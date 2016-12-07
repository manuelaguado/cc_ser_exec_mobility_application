<style>
div.table-responsive div#listado_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">
	<div class="row clearfix">
		<!-- /section:settings.box -->
		<div class="page-content">
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
					Todos Los Usuarios
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="listado" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Tipo</th>
							<th>Status</th>
							<th>Rol</th>
							<th>Acciones</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#listado').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "clientes/allthem_data",
            "type": "POST"
        }
    } );
} );
</script>