<style>
div.table-responsive div#operadores_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
		Operadores
	</h1>
</div>

	<div class="row clearfix">
			<div class="col-md-12 column">
				<div class="table-responsive">
					<table id="operadores" class="display table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID Operador</th>
								<th>Usuario</th>
								<th>Correo</th>
								<th>Nombre</th>
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
    $('#operadores').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "operadores/obtener_operadores",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 4,
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
				"targets": 5,
				"visible":false,
				"searchable":false
			}
		]
    } );
} );
</script>