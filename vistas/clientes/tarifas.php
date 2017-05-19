<style>
div.table-responsive div#tarifas_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">
	<div class="row clearfix">
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="tarifas" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Cliente</th>
							<th>$Base</th>
							<th>$KM</th>
							<th>Descripcion</th>
							<th>Nombre</th>
							<th>Inicio</th>
							<th>Fin</th>
							<th>Estado</th>
							<th>Tipo</th>
							<th>Tabulado</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#tarifas').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"pageLength": 30,
		"ajax": {
            "url": "clientes/getTarifas",
            "type": "POST"
        }
    } );
} );
</script>