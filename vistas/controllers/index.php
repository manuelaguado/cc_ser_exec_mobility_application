<style>
div.table-responsive div#controllers_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">
	<div class="page-content">
		<div class="page-header">
			<h1>
				Controladores
			</h1>
		</div><!-- /.page-header -->
	</div>
	<div class="row clearfix">
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_add_metodo();">Nuevo par</button>
		</div>
			<div class="col-md-12 column">
				<div class="table-responsive">
					<table id="controllers" class="display table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Controller</th>
								<th>Method</th>
								<th>Nombre</th>
								<th>Descripción</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#controllers').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "controllers/obtener_controllers",
            "type": "POST"
        }
    } );
} );
accion_controller('controllers');
</script>
