<style>
div.table-responsive div#viajes_operador_wrapper.dataTables_wrapper.form-inline.dt-bootstrap.no-footer div.row div.col-sm-12{
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
				Viajes procesados del operador <?=$id_operador?>
			</h1>
		</div>
		<div class="col-md-12 column">
			<div class="table-responsive">
				<table id="viajes_operador" class="display table table-striped" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th></th>
							<th></th>
                                                 <th></th><!--Costos-->
							<th></th><!--Adicional (hide)-->
							<th>Neto (hide)</th>
							<th></th><!--Distancias de google-->
							<th>Km MIN (hide)</th>
							<th>Time MAX (hide)</th>
							<th>Time MIN (hide)</th>
							<th></th><!--Datos de operador-->
							<th></th><!--Espera-->
							<th></th>
							<th></th><!--status de viaje-->
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody class="ace-thumbnails clearfix">
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
	    $('#viajes_operador').dataTable( {
			"fnDrawCallback": function( oSettings ) {
			  $('[data-rel=tooltip]').tooltip();

			  	var $overflow = '';
			  	var colorbox_params = {
				  	reposition:true,
				  	scalePhotos:true,
				  	scrolling:false,
				  	previous:'<i class="ace-icon fa fa-arrow-left"></i>',
				  	next:'<i class="ace-icon fa fa-arrow-right"></i>',
				  	close:'&times;',
				  	current:'{current} of {total}',
				  	maxWidth:'100%',
				  	maxHeight:'100%',
					slideshow: false,
				  	onOpen:function(){
					  	$overflow = document.body.style.overflow;
					  	document.body.style.overflow = 'hidden';
				  	},
				  	onClosed:function(){
				  		document.body.style.overflow = $overflow;
				  	},
				  	onComplete:function(){
				  		$.colorbox.resize();
				  	}
			  	};

			  	$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
			  	$("#cboxLoadingGraphic").html("<i class='ace-icon fa fa-spinner orange fa-spin'></i>");//let's add a custom loading icon


			  	$(document).one('ajaxloadstart.page', function(e) {
			  		$('#colorbox, #cboxOverlay').remove();
			  	});

			},
	        "processing": true,
	        "serverSide": true,
		 "ordering": false,
		 "searching": false,
		 "pageLength": 100,
		"ajax": {
	            "url": "ingresosoperador/ver_viajes_archivados_get/<?=$id_operador?>",
	            "type": "POST"
	     	},
		"columnDefs": [
  			 {
  				 "targets": 1,
  				 "visible": false,
  				 "searchable":false
  			 },
			 {
  				 "targets": 4,
  				 "visible": false,
  				 "searchable":false
  			 },
			 {
  				 "targets": 6,
  				 "visible": false,
  				 "searchable":false
  			 },
			 {
  				 "targets": 7,
  				 "visible": false,
  				 "searchable":false
  			 },
			 {
  				 "targets": 8,
  				 "visible": false,
  				 "searchable":false
  			 },
			 {
  				 "targets": 14,
  				 "visible": false,
  				 "searchable":false
  			 }
  		 ]

	    } );
	} );

</script>
