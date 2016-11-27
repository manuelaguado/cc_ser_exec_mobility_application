function formAsentamiento(identificador,id_asentamiento){
	$(document).ready(function() {
		$.ajax({
			url: 'asentamientos/form_general/' + identificador + '/' + id_asentamiento,
			dataType: 'html',
				success: function(resp_success){			
					var modal =  resp_success;
					$(modal).modal().on('shown.bs.modal',function(){
						//console.log(modal);
					}).on('hidden.bs.modal',function(){
						$(this).remove();
					});					

				},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red ASNT-01');}	
		});
	} );
}