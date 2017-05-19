<style>
div.table-responsive div#listado_vigente_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
		Listado de operadores vigentes
	</h1>
</div>

	<div class="row clearfix">
			<div class="col-md-12 column">
				<div class="table-responsive">
					<table id="listado_vigente" class="display table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>NUM</th>
								<th>Nombre</th>
								<th>Número</th>
								<th>Short</th>
								<th>Marca</th>
								<th>Modelo</th>
								<th>Año</th>
								<th>Placas</th>
								<th>Color</th>
								<th></th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#listado_vigente').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "operadores/listado_vigente_get",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 0,
				"visible":false,
				"searchable":false
			},
			{
				"targets": 10,
				"searchable":false,
				"render": function (status) {
					return  status ;				
				}
			}
		]
    } );
} );
</script>