<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Configuración de la paquetería
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
			
				<div class="control-group">

					<div class="checkbox">
						<label class="block">
							<input onclick="msgPaqArray()" value="Caja de archivo muerto"  name="msgPaq[]" id="paqueteria_1" type="checkbox" class="ace input-lg">
							<span class="lbl bigger-120"> Caja de archivo muerto</span>
						</label>
					</div>
					
					<div class="checkbox">
						<label class="block">
							<input onclick="msgPaqArray()" value="Cajas"  name="msgPaq[]" id="paqueteria_2" type="checkbox" class="ace input-lg">
							<span class="lbl bigger-120"> Cajas</span>
						</label>
					</div>
					
					<div class="checkbox">
						<label class="block">
							<input onclick="msgPaqArray()" value="Bulto"  name="msgPaq[]" id="paqueteria_3" type="checkbox" class="ace input-lg">
							<span class="lbl bigger-120"> Bulto</span>
						</label>
					</div>
					
					<div class="checkbox">
						<label class="block">
							<input onclick="msgPaqArray()" value="Bolsa"  name="msgPaq[]" id="paqueteria_4" type="checkbox" class="ace input-lg">
							<span class="lbl bigger-120"> Bolsa</span>
						</label>
					</div>
					
					<div class="checkbox">
						<label class="block">
							<input onclick="msgPaqArray()" value="Otro"  name="msgPaq[]" id="paqueteria_5" type="checkbox" class="ace input-lg">
							<span class="lbl bigger-120"> Otro</span>
						</label>
					</div>
					
				</div>

			</div>
			<div class="modal-footer">					
				<button data-dismiss="modal" class="btn btn-ar btn-success" type="button" id="add">Aceptar</button>
			</div>
		</div>
	</div>
</div>