<style>
div.table-responsive div#programados_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
.nav-tabs > li > a {
    border-top-width: 3px;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus {
    border-top: 6px solid #000000;
	opacity: 1.0 !important;
}
</style>
<div class="container">
	<div class="row clearfix">
		<!-- /section:settings.box -->
		<div class="page-content">
			<div class="page-header">
				<h1>
					Programados
				</h1>
			</div><!-- /.page-header -->
		</div>
		<div class="col-sm-12">
			<div class="widget-box transparent" id="recent-box">

				<div class="widget-header">
					<h4 class="widget-title lighter smaller">
						<i class="ace-icon fa fa-car orange"></i>Status
					</h4>

					<div class="widget-toolbar no-border">
						<ul class="nav nav-tabs" id="recent-tab">
							<li class="active">
								<i class="fa fa-refresh fa-spin point_content contred" style="color:#ff0000; display:none;"></i>
								<a style="border-top-color:#ff0000; opacity: 0.3;" data-toggle="tab" href="#trojo" aria-expanded="false">
								<i class="fa fa-circle" style="color:#ff0000"></i>&nbsp;&nbsp;
								- de 60 </a>
							</li>

							<li class="">
								<i class="fa fa-refresh fa-spin point_content contorange" style="color:#ff7b00; display:none;"></i>
								<a style="border-top-color:#ff7b00; opacity: 0.3;" data-toggle="tab" href="#tnaranja" aria-expanded="false">
								<i class="fa fa-circle" style="color:#ff7b00"></i>&nbsp;&nbsp;
								+ de 60 y - de 90 </a>
							</li>

							<li class="">
								<i class="fa fa-refresh fa-spin point_content contyellow" style="color:#f3df02; display:none;"></i>
								<a style="border-top-color:#f3df02; opacity: 0.3;" data-toggle="tab" href="#tamarillo" aria-expanded="false">
								<i class="fa fa-circle" style="color:#f3df02"></i>&nbsp;&nbsp;
								+ de 90 y - de 1 día</a>
							</li>

							<li class="">
								<i class="fa fa-refresh fa-spin point_content contverde" style="color:#c4e62b; display:none;"></i>
								<a style="border-top-color:#c4e62b; opacity: 0.3;" data-toggle="tab" href="#tverde" aria-expanded="false">
								<i class="fa fa-circle" style="color:#c4e62b"></i>&nbsp;&nbsp;
								+ de 1 día</a>
							</li>

							<li class="">
								<a style="border-top-color:#bfbfbf; opacity: 0.3;" data-toggle="tab" href="#tgris" aria-expanded="false">
								<i class="fa fa-circle" style="color:#bfbfbf"></i>&nbsp;&nbsp;
								Caducos</a>
							</li>
						</ul>
					</div>
				</div>
				<div class="widget-body">
					<div class="widget-main padding-4">
						<div class="tab-content padding-8">
							<div id="trojo" class="tab-pane active">
								<table id="rojo" class="display table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>[STATUS]</th>
											<th>Hora</th>
											<th>Usuario</th>
											<th>Empresa</th>
											<th>Servicio</th>
											<th>Tipo</th>
											<th>NUM</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>
							<div id="tnaranja" class="tab-pane">
								<table id="naranja" class="display table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>[STATUS]</th>
											<th>Hora</th>
											<th>Usuario</th>
											<th>Empresa</th>
											<th>Servicio</th>
											<th>Tipo</th>
											<th>NUM</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>
							<div id="tamarillo" class="tab-pane">
								<table id="amarillo" class="display table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>[STATUS]</th>
											<th>Hora</th>
											<th>Usuario</th>
											<th>Empresa</th>
											<th>Servicio</th>
											<th>Tipo</th>
											<th>NUM</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>
							<div id="tverde" class="tab-pane">
								<table id="verde" class="display table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>[STATUS]</th>
											<th>Hora</th>
											<th>Usuario</th>
											<th>Empresa</th>
											<th>Servicio</th>
											<th>Tipo</th>
											<th>NUM</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>
							<div id="tgris" class="tab-pane">
								<table id="gris" class="display table table-striped" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>[STATUS]</th>
											<th>Hora</th>
											<th>Usuario</th>
											<th>Empresa</th>
											<th>Servicio</th>
											<th>Tipo</th>
											<th>NUM</th>
											<th>Acciones</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
	$('#rojo').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
			var oTable = $('#rojo').DataTable();
			var info = oTable.page.info();
			var count = info.recordsTotal;
			if(count > 0){ $('.contred').css("display", ""); }
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ajax": {
			"url": "operacion/programados_rojo",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
	$('#naranja').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
			var oTable = $('#naranja').DataTable();
			var info = oTable.page.info();
			var count = info.recordsTotal;
			if(count > 0){ $('.contorange').css("display", ""); }
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ajax": {
			"url": "operacion/programados_naranja",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
	$('#amarillo').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
			var oTable = $('#amarillo').DataTable();
			var info = oTable.page.info();
			var count = info.recordsTotal;
			if(count > 0){ $('.contyellow').css("display", ""); }
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ajax": {
			"url": "operacion/programados_amarillo",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
	$('#verde').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
			var oTable = $('#verde').DataTable();
			var info = oTable.page.info();
			var count = info.recordsTotal;
			if(count > 0){ $('.contverde').css("display", ""); }
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ajax": {
			"url": "operacion/programados_verde",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
	$('#gris').dataTable( {
		"fnDrawCallback": function( oSettings ) {
		  $('[data-rel=tooltip]').tooltip();
		},
		"processing": true,
		"serverSide": true,
		"pageLength": 30,
		"ajax": {
			"url": "operacion/programados_gris",
			"type": "POST"
		},
		"columnDefs": [
			{
				"targets": 1,
				"visible": false,
				"searchable":false
			}
		]
	} );
} );
</script>
