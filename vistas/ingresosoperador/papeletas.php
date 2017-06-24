<style>
div.table-responsive div#papeletas_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
					Papeletas
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="papeletas" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Número Económico</th>
							<th>Papeletas</th>
							<th>Operador</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#papeletas').dataTable( {
		"fnDrawCallback": function( row, data, start, end, display  ) {
			$('[data-rel=tooltip]').tooltip();
                     var api = this.api(), data;
                     if(api.rows().count() != '0'){accion_papeletas('papeletas');}
		},
        "processing": true,
        "serverSide": true,
	 "pageLength": 100,
	 "ordering": false,
	"ajax": {
            "url": "ingresosoperador/papeletasGet",
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
