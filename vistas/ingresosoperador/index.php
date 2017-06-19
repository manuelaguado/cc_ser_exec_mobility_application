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
		<?php
		if($this->tiene_permiso('Ingresosoperador|index')){
			setlocale(LC_TIME,"es_MX.UTF-8");
			$dt_Ayer = date('m/d/Y', strtotime('-1 day')) ;
			$fecha = strftime("%A %e de %B", strtotime($dt_Ayer));
		?>
		<div class="col-md-12 column menu_header_content" id="boton_accion">
			<button class="btn btn-ar btn-primary" type="button" onclick="proceso249();">Procesar hasta el <?=ucwords($fecha)?></button>
		</div>
		<?php
		}
		?>
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
			$( api.column(2).footer() ).html('V: '+api.column(2).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(0));
			$( api.column(3).footer() ).html('$ '+api.column(3).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(4).footer() ).html('$ '+api.column(4).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(5).footer() ).html('$ '+api.column(5).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(6).footer() ).html(api.column(6).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2)+' km');
	              $( api.column(7).footer() ).html('$ '+api.column(7).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(8).footer() ).html('$ '+api.column(8).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			$( api.column(9).footer() ).html('$ '+api.column(9).data().reduce(function(a,b){return intVal(a) + intVal(b);},0).toFixed(2));
			if(api.rows().count() == '0'){$('#boton_accion').css( "visibility", "hidden" );}else{accion_operatorGroup('conceptos');}
			<?php
				if($vsp > 0){
			?>
				$('#boton_accion').html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>¡Atención!  </strong>Existen viajes sin pagar que requieren su atención&nbsp;&nbsp;<br></div>');
			<?php
				}
			?>
		},
        "processing": true,
        "serverSide": true,
	 "pageLength": 100,
	 "ordering": false,
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
</script>
