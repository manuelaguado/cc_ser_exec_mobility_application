<style>
div.table-responsive div#gps_activos_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
					Localización GPS >> Activos >> <?=$activos?>
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="gps_activos" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Núm</th>
							<th>Nombre</th>
							<th>Batt</th>
							<th>Serie</th>
							<th>Teléfono</th>
							<th>Short</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Placas</th>
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
    $('#gps_activos').dataTable( {
		"pageLength": 50,
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "gps/gps_activo_get",
            "type": "POST"
        }
    } );
} );
</script>