<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Teléfonos de contacto
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
			<?php
			if(count($telefonos)>0){
			?>			
				<table class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>Número</th>
							<th>Tipo</th>
							<th>Status</th>
						</tr>
					</thead>

					<tbody>
					<?php
					foreach($telefonos as $num => $tel){
					?>
						<tr>
							<td><?=$tel['numero']?></td>
							<td><?=$tel['lab1']?></td>
							<td><?=$tel['lab2']?></td>
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
				<strong>Sin teléfonos</strong>
				¡Este operador no tiene teléfonos ligados a su cuenta!
				<br>
			</div>
			<?php
			}
			?>				
			</div>
		</div>
	</div>
</div>