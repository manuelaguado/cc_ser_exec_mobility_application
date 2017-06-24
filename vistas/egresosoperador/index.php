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
					Conceptos de cobro
				</h1>
			</div><!-- /.page-header -->
		</div>
		<?php
		if($this->tiene_permiso('Egresosoperador|add_concepto')){
		?>
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_nuevo_cobro();">Nuevo cobro</button>
		</div>
		<?php
		}
		?>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="conceptos" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Concepto</th>
							<th>Monto</th>
							<th>Periodicidad</th>
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
    $('#conceptos').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "egresosoperador/obtener_conceptos",
            "type": "POST"
        }
    } );
} );
</script>
