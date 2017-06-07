<style>
div.table-responsive div#conceptos_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
				Viajes por operador
			</h1>
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="conceptos" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>NUM</th>
							<th>Nombre</th>
							<th>Viajes</th>
							<th>Total</th>
							<th>Costo de Viajes</th>
							<th>Costos adicionales</th>
							<th>Kilometros</th>
							<th>Programado</th>
							<th>Deuda</th>
							<th>Pago tentativo</th>
							<th>ID</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#conceptos').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
	 "pageLength": 100,
	"ajax": {
            "url": "ingresosoperador/operadorGroup",
            "type": "POST"
     }/*
		,
		 "columnDefs": [
			 {
				 "targets": 11,
				 "visible": false,
				 "searchable":false
			 }
		 ]
	*/
    } );
} );
accion_operatorGroup('conceptos');
</script>
