<div class="modal fade" id="myModal" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"+ aria-hidden="true">x</button>
				<h4 class="modal-title" id="myModalLabel">
					<?php echo (@$historia[0]['num'] != '')? 'Historia del operador '.$historia[0]['num'].', '.$historia[0]['solicita']:'Actualmente no hay historia para el operador'; ?>
				</h4>
			</div>
			<div class="modal-body" id="modal_content">
			<div id="timeline-2" class="tiemline_history">
				<div class="timeline-container timeline-style2">
				<?php
				$date = '';
				foreach($historia as $num => $capitulo){
				?>
					
						<?php
						$datex = substr($capitulo['fecha_alta'], 0, 10);
						if($date!=$datex){
						?>
						<span class="timeline-label">
							<b><?php
							setlocale(LC_TIME, 'es_MX.UTF-8');
							echo strftime('%A %d de %B de %Y', strtotime($capitulo['fecha_alta'])); 
							//echo time();
							?></b>
						</span>
						
						<div class="timeline-items">
						<?php
						}
						?>
							<div class="timeline-item clearfix">
								<div class="timeline-info">
									<span class="timeline-date"><?=substr($capitulo['fecha_alta'], 11, 8)?></span>
									<i class="timeline-indicator btn btn-info no-hover"></i>
								</div>
								<div class="widget-box transparent">
									<div class="widget-body">
										<div class="widget-main no-padding">
											<span class="bigger-110 action-buttons">
												<a style="cursor:default" class="purple bolder"><?=$capitulo['etiqueta']?></a>
												<?=$capitulo['valor']?>
											</span>
											
											<?php
											if(($capitulo['autoriza'] != $capitulo['solicita'])){
											?>
											<div class="action-buttons">
												<a style="cursor:default">
													<i class="ace-icon fa fa-thumbs-o-up green bigger-125"></i>
													<?=$capitulo['autoriza']?>
												</a>
											</div>										
											<?php
											}
											?>
										</div>
									</div>
								</div>
							</div>
						<?php
						if($date!=$datex){
						?>
						</div>
						<?php
						$date = $datex;
						}
						?>
					
				<?php
				}
				?>	
				</div>			
			</div>
			<style>
			.timeline-style2 .timeline-label {
				width:100%;
				text-align:left;
			}
			.action-buttons a:hover {
				transform: scale(2);
			}
			</style>			
			<script type="text/javascript">
				jQuery(function($) {
					$('.tiemline_history').ace_scroll({
						size: 300
					});
				})
			</script>								
			</div>
		</div>
	</div>
</div>