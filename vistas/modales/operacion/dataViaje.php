<div class="modal fade" data-backdrop="" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Datos del viaje
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
					<table class="table  table-bordered table-hover">
					<?php
						foreach($data as $key => $val){
							if($val){
								echo '<tr>';
								echo '<td>'.$key.'</td><td>'.$val."</td>";
								echo '</tr>';
							}
						}
					?>
					</table>
			</div>
			<div class="modal-footer">					
				<button  data-dismiss="modal" class="btn btn-ar btn-default" type="button">Cerrar</button>
			</div>
		</div>
	</div>
</div>