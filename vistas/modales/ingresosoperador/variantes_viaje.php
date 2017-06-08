<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 700px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Variantes del viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content" style="padding: 3px 15px 15px 15px">
					<?php
                                   foreach($variantes as $num => $variante){
                                          echo "<div class='row ace-thumbnails'>";
                                          echo "<div class='col-sm-6'>".$variante['file']."</div>";
                                          echo " <div class='col-sm-6'>
                                                        <span><h4>Ruta: ".$variante['sumario']."</h4></span><br>
                                                        <span><h5>Kilometros: ".$variante['km']."</h5></span><br>
                                                        <span><h5>Tiempo: ".$variante['minutos']."</h5></span>
                                                 </div>";
                                          echo "</div><hr>";
                                   }
                                   ?>
			</div>
		</div>
              <script>
              $(document).ready(function() {
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
              });
              </script>
	</div>
</div>
