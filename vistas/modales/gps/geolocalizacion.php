<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog" style="width: 1024px;">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					Geolocalizando ...
				</h4>
			</div>
			<div class="modal-body" id="modal_content">		
				<div class="row">
					<iframe width="100%" height="750px" frameborder="0" src="gps/tracker/<?=$id_operador?>" scrolling="no"></iframe>
				</div>
			</div>
		</div>
	</div>
</div>