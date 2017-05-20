function modal_ruta(id_operador){
	$(document).ready(function() {
		$.ajax({
			url: 'kml/modal_ruta/' + id_operador,
			dataType: 'html',
			success: function(resp_success){			
				var modal =  resp_success;
				$(modal).modal().on('shown.bs.modal',function(){
					//console.log(modal);
				}).on('hidden.bs.modal',function(){
					$(this).remove();
				});
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red KML-01');}	
		});
	} );
}