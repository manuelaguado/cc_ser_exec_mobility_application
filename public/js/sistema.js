function updateSettings(){

	var msj_error="";

       if( $('#costo_hora').val() == "" )
              {msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise el costo de la hora de espera o viaje.</li>';}
       if( $('#tiempo_cortesia').val() == "" )
              {msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise el tiempo de cortesía.</li>';}
       if( $('#km_cortesia').val() == "" )
              {msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise los km de cortesía.</li>';}
       if( $('#km_perimetro').val() == "" )
              {msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise los km predeterminados de viaje a partir de los cuales se cobra el servicio por km.</li>';}
       if( $('#comision_operadores').val() == "" )
              {msj_error+='<li><i class="ace-icon fa fa-times bigger-110 red"></i>Precise la comision que cobrará a los operadores.</li>';}

	if( !msj_error == "" ){
		alerta('Alerta','<ul class="list-unstyled spaced">'+msj_error+'</ul>');
		return false;
	}

	$(document).ready(function() {
		$.ajax({
			url: 'sistema/updateSettings',
			type: 'POST',
			data: $("#settings").serialize(),
			dataType: 'json',
			success: function(resp_success){
				if (resp_success['resp'] == true) {
					alerta('Satisfactorio','Se actualizó la configuración');
				}else{
					alerta('Alerta!','Error de conectividad de red SYS-01');
				}
			},
			error: function(respuesta){ alerta('Alerta!','Error de conectividad de red SYS-02');}
		});
	} );

}
