<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Configuración de la mensajería
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				
				<div class="control-group">
					<div class="row">
						<div class="col-sm-6">
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Documentos"  name="msgPaq[]" id="mensajeria_1" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Documentos</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Boletos"  name="msgPaq[]" id="mensajeria_2" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Boletos</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Flores"  name="msgPaq[]" id="mensajeria_3" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Flores</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Bolsa"  name="msgPaq[]" id="mensajeria_4" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Bolsa</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Cartera"  name="msgPaq[]" id="mensajeria_5" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Cartera</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Lentes"  name="msgPaq[]" id="mensajeria_6" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Lentes</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Zapatos"  name="msgPaq[]" id="mensajeria_7" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Zapatos</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Traje"  name="msgPaq[]" id="mensajeria_8" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Traje</span>
								</label>
							</div>
							
						</div>
						
						<div class="col-sm-6">	
						
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Vestido"  name="msgPaq[]" id="mensajeria_9" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Vestido</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Ropa"  name="msgPaq[]" id="mensajeria_10" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Ropa</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Paraguas"  name="msgPaq[]" id="mensajeria_11" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Paraguas</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Cambio"  name="msgPaq[]" id="mensajeria_12" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Cambio</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Invitaciones"  name="msgPaq[]" id="mensajeria_13" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Invitaciones</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Vales"  name="msgPaq[]" id="mensajeria_14" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Vales</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Factura"  name="msgPaq[]" id="mensajeria_15" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Factura</span>
								</label>
							</div>
							
							<div class="checkbox">
								<label class="block">
									<input onclick="msgPaqArray()" value="Otro"  name="msgPaq[]" id="mensajeria_16" type="checkbox" class="ace input-lg">
									<span class="lbl bigger-120"> Otro</span>
								</label>
							</div>
						</div>
					</div>				
				</div>
			</div>
			<div class="modal-footer">					
				<button data-dismiss="modal" class="btn btn-ar btn-success" type="button" id="add">Aceptar</button>
			</div>
		</div>
	</div>
</div>