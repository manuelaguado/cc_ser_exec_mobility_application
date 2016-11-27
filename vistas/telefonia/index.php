<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#telefonia_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">

<div class="page-header">
	<h1>
		Telefonía
	</h1>
</div>
		<?php
		if($this->tiene_permiso('Telefonia|nuevo_cel')){
		?>	
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_add_celular();">Nuevo celular</button>			
		</div>
		<?php
		}
		?>
	<div class="row clearfix">
			<div class="col-md-12 column">
				<div class="table-responsive">
					<table id="telefonia" class="display table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Serie</th>
								<th>IMEI</th>
								<th>Número</th>
								<th>#Corto</th>
								<th>Modelo</th>
								<th>SO</th>
								<th>Ver</th>
								<th>Acciones</th>
								<th>Externo</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#telefonia').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "telefonia/obtener_celulares",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 8,
				"searchable":false,
				"render": function (status) {
					return  status ;				
				}
			},
			{
				"targets": 0,
				"visible":false,
				"searchable":false
			},
			{
				"targets": 9,
				"visible":false,
				"searchable":false
			}
		]
    } );
} );
</script>