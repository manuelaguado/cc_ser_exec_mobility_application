<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Domicilios del operador
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
			<?php
			if(count($domicilios)>0){
			?>
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Dirección</th>
							<th>Tipo</th>
							<th>Status</th>
						</tr>
					</thead>

					<tbody>
					<?php
					foreach($domicilios as $num => $domi){
					?>
						<tr>
							<td><?=$domi['domicilio']?></td>
							<td><?=$domi['lab1']?></td>
							<td><?=$domi['lab2']?></td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			<?php
			}else{
			?>
			<div class="alert alert-info">
				<strong>Sin domicilios</strong>
				¡Este operador no tiene domicilios ligados a su cuenta!
				<br>
			</div>
			<?php
			}
			?>
			</div>
		</div>
	</div>
</div>