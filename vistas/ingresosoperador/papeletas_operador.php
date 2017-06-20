<style>
div.table-responsive div#papeletas_op_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
				Papeletas del operador <?=$id_operador?>
			</h1>
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="papeletas_op" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>URL</th>
							<th>user_alta</th>
							<th>user_mod</th>
                                                 <th>fecha_alta</th>
                                                 <th>fecha_mod</th>
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
    $('#papeletas_op').dataTable( {
		"fnDrawCallback": function( row, data, start, end, display  ) {
			$('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
	 "pageLength": 100,
	 "ordering": false,
	"ajax": {
            "url": "ingresosoperador/papeletas_operadorGet/" + <?=$id_operador?>,
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
