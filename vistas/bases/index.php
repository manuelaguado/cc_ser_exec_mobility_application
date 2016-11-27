<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#bases_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
		Listado de Bases
	</h1>
</div>
		<?php
		if($this->tiene_permiso('Bases|nueva_base')){
		?>	
		<div class="col-md-12 column menu_header_content">
			<button class="btn btn-ar btn-primary" type="button" onclick="modal_add_base();">Nueva Base</button>			
		</div>
		<?php
		}
		?>
	<div class="row clearfix">
			<div class="col-md-12 column">
				<div class="table-responsive">
					<table id="bases" class="display table table-striped" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>ID</th>
								<th>Descripcion</th>
								<th>Ubicacion</th>
								<th>Latitud</th>
								<th>Longitud</th>
								<th>Tipo</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#bases').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "bases/obtener_bases",
            "type": "POST"
        }
    } );
} );
<?php echo $this->tiene_permiso('Bases|edita_base')?"accion_bases('bases');":"" ?>

</script>