function verifica_session(){
	$.ajax({
		url: url_app + 'login/verifica_session',
		type: 'POST',
		dataType: "json",
		success: function(respuesta){
			if(respuesta[0].resp=='timeout'){
				window.location = url_app +  "login";
			}
			if(respuesta[0].resp=='intime'){
				$('#message-center').html('');
			}
		},
		error: function(){
			console.log('error de conectividad');
			$('#message-center').html('Verifique la conectividad con internet');
		}
	});
}
function carga_archivo(div_contenedor,ruta,parametros){
	$('#'+div_contenedor).empty();
	$('#preloader').html('<span><img src="public/img/loading.gif"></span>');
	$('#'+div_contenedor).load(ruta,parametros, function(){
		$('#preloader').html('');
	});
}
function limpia_div(div){
	$('#'+div).html('');
}
function alerta(header,body){

	var modal =
	'<div class="modal fade" id="myModal" tabindex="-1" role="dialog"'+
		'aria-labelledby="myModalLabel" aria-hidden="true">'+
		'<div class="modal-dialog">'+
			'<div class="modal-content">'+
				'<div class="modal-header">'+
					'<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">×</button>'+
					'<h4 class="modal-title" id="myModalLabel">'+header+'</h4>'+
				  '</div>'+
				'<div class="modal-body">'+
					''+ body +''+
				'</div>'+
				'<div class="modal-footer">'+
					'<button data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>'+
				'</div>'+
			'</div>'+
		'</div>'+
	'</div>';
	$(modal).modal().on('shown.bs.modal',function(){
		//console.log(modal);
	}).on('hidden.bs.modal',function(){
		$(this).remove();
	});

}
function set_menu_active(id_menu) {
	$("#sidebar li").each(function(indice, elemento) {
		$(this).removeClass("active");
	});
	$( "#"+id_menu ).addClass( "active" );
}

function getNotificaciones(){
	setTimeout(function(){

			$.ajax({
			url: 'inicio/getAllMessages',
			type: 'POST',
			dataType: "json",
			success: function(respuesta){

				$("#notifiaciones").html(respuesta.html);
				$("#requisitados_validador").html(respuesta.html_req);


				$('#p_notif').slimScroll({height: '260px'});

				if(respuesta.count>0){
					$('#icon-msg-noti').addClass('icon-animated-vertical');
				}

				if(respuesta.total_planes>0){
					$('#icon-req-noti').addClass('icon-animated-vertical');
				}

			},
			error: function(){alerta('Alerta!','Error de conectividad de red GNRL-01');}
		});

	}, 100);

}
var normalize = (function() {
  var from = "ÃÀÁÄÂÈÉËÊÌÍÏÎÒÓÖÔÙÚÜÛãàáäâèéëêìíïîòóöôùúüûÑñÇç",
      to   = "AAAAAEEEEIIIIOOOOUUUUaaaaaeeeeiiiioooouuuunncc",
      mapping = {};

  for(var i = 0, j = from.length; i < j; i++ )
      mapping[ from.charAt( i ) ] = to.charAt( i );

  return function( str ) {
      var ret = [];
      for( var i = 0, j = str.length; i < j; i++ ) {
          var c = str.charAt( i );
          if( mapping.hasOwnProperty( str.charAt( i ) ) )
              ret.push( mapping[ c ] );
          else
              ret.push( c );
      }
      return ret.join( '' );
  }

})();
