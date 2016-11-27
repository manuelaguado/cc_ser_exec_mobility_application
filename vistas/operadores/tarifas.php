<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#tarifa_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
</style>
<div class="container">
	<div class="row clearfix">
		<!-- /section:settings.box -->
		<div class="page-content">
			<div class="page-header">
				<h1>
					Tarifas
				</h1>
			</div><!-- /.page-header -->
		</div>
		<?php
		if($this->tiene_permiso('Operadores|nueva_tarifa')){
		?>	
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_nueva_tarifa(<?=$id_operador?>);">Agregar Tarifa</button>
		</div>
		<?php
		}
		?>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="tarifa" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Nombre</th>
							<th>Base</th>
							<th>Km Extra</th>
							<th>Forma de pago</th>
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
    $('#tarifa').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "operadores/obtener_tarifas/<?=$id_operador?>",
            "type": "POST"
        }
    } );
} );
</script>