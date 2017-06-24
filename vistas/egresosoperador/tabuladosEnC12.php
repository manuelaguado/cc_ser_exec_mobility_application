<style>
div.table-responsive div#c12_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
					Por revisar C12 y T3
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="c12" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>[STATUS]</th>
							<th>Hora</th>
							<th>Usuario</th>
							<th>Empresa</th>
							<th>Servicio</th>
							<th>NUM</th>
							<th>Apartado</th>
							<th>Acciones</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<input type="hidden" id="refmod_aux" name="refmod_aux" value="" />
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#c12').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ordering": false,
		"ajax": {
			"url": "egresosoperador/tabuladosEnC12Get",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
} );
</script>
