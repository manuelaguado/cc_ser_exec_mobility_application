<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#localizar_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
				<h1>
					Localización GPS - via <?=SITE_NAME?>
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="localizar" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Núm</th>
							<th>Nombre</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Color</th>
							<th>Placas</th>
							<th>Año</th>
							<th>Serie</th>
							<th>Cel</th>
							<th>Mrk Cel</th>
							<th>Mod Cel</th>
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
    $('#localizar').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "gps/localizar_get",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 0,
				"visible":false,
				"searchable":false
			}
		]
    } );
} );
</script>