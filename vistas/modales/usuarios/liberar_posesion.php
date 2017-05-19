<div class="modal fade" id="myModal" tabindex="-1">
<style>
.ghost_pos {
    width: 40px;
    border-radius: 8px;
    padding-top: 8px;
	position:relative;
    font-size: 1.6em;
    left: 10px;
	color:#2a6496;
}
.ghost_pos:hover {
	color:#ffbf00;
}
</style>
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Exorcizar id:<?=$id_usuario?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
				<div class ="row">
					<div class="form-group">
						<div class="col-sm-12" style="text-align:center;">
							<input type="hidden" id="id_usuario" name="id_usuario" value="<?=$id_usuario?>" />
							<a href="javascript:;" onclick="exorcizar()" class="ghost_pos">
								<i class="ace-icon fa fa-snapchat-ghost bigger-160"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">					
				<button data-dismiss="modal" class="btn btn-ar btn-success" type="button" id="add">Cerrar</button>
			</div>			
		</div>
	</div>
</div>
