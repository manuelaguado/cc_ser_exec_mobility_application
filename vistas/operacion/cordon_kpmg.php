<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<style>
div.table-responsive div#cordon_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
	padding: 0px !important;
}
.container {
    max-width: 100% !important;
	width: 100% !important;
}
.btn.btn-app.btn-xs {
    width: 32px;
    font-size: 12px;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    vertical-align: middle;
}
</style>
<div class="container">
	<div class="row clearfix">
		<!-- /section:settings.box -->
		<div class="page-content">
			<div class="page-header">
				<h1>
					Cordon KPMG
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="cordon" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>Turno</th>
							<th>NUM EQ</th>
							<th>Nombre</th>
							<th>Marca</th>
							<th>Modelo</th>
							<th>Color</th>
							<th>Llegada/Espera</th>
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
    $('#cordon').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
        "processing": true,
        "serverSide": true,
		"ajax": {
            "url": "operacion/cordon_kpmg_get",
            "type": "POST"
        },
		"columnDefs": [
			{
				"targets": 8,
				"visible": false,
				"searchable":false
			}
		]
    } );
} );

<?php
if(SOCKET_PROVIDER == 'ABLY'){
?>
	var conn = new Ably.Realtime('<?=ABLY_API_KEY?>');
	conn.connection.on('connected', function() {
	  console.log('✓ Servicio de actualización de cordón activo');
	})

	var updChannel = conn.channels.get('updcrd1');
	updChannel.subscribe(function(resp_success){
		$('#cordon').DataTable().ajax.reload();
	});
<?php
}elseif(SOCKET_PROVIDER == 'PUSHER'){
?>
	var pusher = new Pusher('<?=PUSHER_KEY?>', {
		encrypted: true
	});
	
	var updChannel = pusher.subscribe('updcrd1');
	
	pusher.connection.bind('connected', function() {
		console.log('✓ Servicio de actualización de cordón activo');
	})
	updChannel.bind('evento', function(data) {
		$('#cordon').DataTable().ajax.reload();
	});
<?php
}elseif(SOCKET_PROVIDER == 'PUBNUB'){
?>
	var WsPubNub = PUBNUB.init({
		publish_key: '<?=PUBNUB_PUBLISH?>',
		subscribe_key: '<?=PUBNUB_SUSCRIBE?>',
		ssl: true
	});
	
	WsPubNub.subscribe({
		channel: 'updcrd1',
		message: function(m){
			$('#cordon').DataTable().ajax.reload();
		}
	});
	
<?php
}
?>
</script>