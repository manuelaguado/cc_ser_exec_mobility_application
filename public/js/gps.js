function modal_geolocalizacion(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'gps/modal_geolocalizacion/' + id_operador,
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red GPS-01');}	
		});
	} );
}