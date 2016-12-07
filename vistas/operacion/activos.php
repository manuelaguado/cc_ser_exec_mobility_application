<style>
div.table-responsive div#activos_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
.btn.btn-app.btn-xs {
    width: 32px;
    font-size: 12px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
}
</style>
<div class="container">
	<div class="row clearfix">
		<!-- /section:settings.box -->
		<div class="page-content">
			<div class="page-header">
				<h1>
					Activos
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="activos" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>NUM EQ</th>
							<th>Nombre</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Color</th>
							<th>id_operador</th>
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
    $('#activos').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "operacion/activos_get",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 5,
				"visible": false,
				"searchable":false
			}
		]
    } );
} );
</script>