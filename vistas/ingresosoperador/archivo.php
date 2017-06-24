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
		<div class="page-content">
			<div class="page-header">
				<h1>
					Archivo
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="conceptos" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
                                                 <th>ID</th>
							<th>NUM</th>
							<th>Nombre</th>
							<th>Viajes</th>
							<th>Total</th>
							<th>Costo de Viajes</th>
							<th>Costos adicionales</th>
							<th>Kilometros</th>

							<th>Deuda</th>
							<th>Pago Neto</th>
                                                 <th></th><!--acciones-->
						</tr>
					</thead>
					<tfoot>
			                  <tr>
			                      <th></th>
			                      <th></th>
						 <th></th>
						 <th></th>
						 <th></th>
						 <th></th>
						 <th></th>

						 <th></th>
						 <th></th>
						 <th></th>
                                           <th></th>
			                  </tr>
			              </tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#conceptos').dataTable( {
		"fnDrawCallback": function( row, data, start, end, display  ) {
			$('[data-rel=tooltip]').tooltip();
			var api = this.api(), data;
	              var intVal = function ( i ) {
	                  return typeof i === 'string' ? i.replace(/[\MXN,]/g, '')*1 : typeof i === 'number' ? i : 0;
	              };
			$( api.column(3).footer() ).html('V: '+api.column(3).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(0));
			$( api.column(4).footer() ).html('$ '+api.column(4).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(5).footer() ).html('$ '+api.column(5).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(6).footer() ).html('$ '+api.column(6).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(7).footer() ).html(api.column(7).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2)+' km');
			$( api.column(8).footer() ).html('$ '+api.column(8).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(9).footer() ).html('$ '+api.column(9).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
		},
        "processing": true,
        "serverSide": true,
	 "pageLength": 100,
	 "ordering": false,
	"ajax": {
            "url": "ingresosoperador/archivo_get",
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
</script>
