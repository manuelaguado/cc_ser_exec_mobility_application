<style>
div.table-responsive div#unidades_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">
	<div class="row clearfix">
		<div class="page-header">
			<h1>
				Control de vehiculos
			</h1>
		</div>
		<?php
		if($this->tiene_permiso('Unidades|add_unidad')){
		?>	
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_nueva_unidad();">Nueva Unidad</button>
		</div>
		<?php
		}
		?>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="unidades" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Year</th>
							<th>Placas</th>
							<th>Motor</th>
							<th>Color</th>
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
    $('#unidades').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "unidades/obtener_unidades",
            "type": "POST"
        }
    } );
} );
accion_unidades('unidades');
</script>