<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#logger_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
					GPS
					<small>
						<i class="ace-icon fa fa-angle-double-right"></i>
						Logger
					</small>
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="logger" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>NUM</th>
							<th>Short</th>
							<th>Latitud</th>
							<th>Longitud</th>
							<th>Tiempo</th>
							<th>Tiempo Server</th>
							<th>Serie</th>
							<th>Prescisión</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#logger').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "gps/logger_get",
            "type": "POST"
        }
    } );
} );
</script>