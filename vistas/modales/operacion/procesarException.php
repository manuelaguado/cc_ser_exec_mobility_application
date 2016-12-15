<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Excepción al procesamiento de entrega de datos
				</h4>
			</div>
			<div class="modal-body" id="modal_content">

					El operador muestra incongruencias a la entrega del viaje, por lo siguiente:
					
					<br><br>Excepciones:<br><br>
					
					<ul>
					<?php
					if(!$alAire){
					?>
					<li>El operador no se encuentra en tiempo a la base</li>
					<?php
					}
					if($formado){
					?>
					<li>El operador se encuentra formado en la base <?=$formado?></li>
					<?php
					}
					if(!$vigente){
					?>
					<li>El viaje ha caducado, no se puede procesar un viaje caduco.</li>
					<?php
					}	
					?>
					</ul>
			</div>
			
			<div class="modal-footer">					
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>