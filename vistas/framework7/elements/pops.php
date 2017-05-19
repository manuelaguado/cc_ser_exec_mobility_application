
<div class="popup detalle_viaje" style="background-color:#efeff4 !important;">
    <div class="content-block">
        <a href="#" class="close-popup">
            <i style="font-size:2em;" class="fa fa-times-circle-o"></i>
        </a>
		<br>
		<div class="list-block">
			<div id="data_viaje">
				<a href="#" class="item-link item-content"><div class="item-inner"><div class="item-title">Sin datos de viaje</div></div></a>
			</div>
		</div>	
    </div>
</div>


<div class="popup detalle_cordon pop_cordon">
    <div class="content-block">
        <a href="#" class="close-popup" style="z-index:10">
             <i id="update_cordon" class="fa fa-refresh fa-spin spin_pop"></i>&nbsp;&nbsp;
			 <i class="fa fa-times-circle close_pop"></i>
        </a>
		<br>
		<div id="data_cordon">
			NO HAY DATOS DE CORDON
		</div>
    </div>
</div>

<script id="indicadores" type="text/template">
	<div id="data_verificacion">
		<div data-page="media-lists" class="page">
			<div class="page-content">
				<div class="content-block" style="margin: 60px 0 !important; color: #FFFFFF;">
					<p>Si la lista muestra todos los indicadores en verde y aun no se le asigna al cordón notifíquelo a la central</p>
				</div>
				<div class="content-block-title">Indicadores</div>
				<div class="list-block media-list">
					<ul>
						{{#each this}}
						<li>
							<a href="#" class="item-link item-content" style="color:{{color}} !important;">
								<div class="item-inner">
									<div class="item-title-row" style="background-image:none !important">
										<div class="item-title">{{indicador}}</div>
										<div class="item-after" style="color:{{color}}; ">{{estado}}</div>
									</div>
								</div>
							</a>
						</li>
						{{/each}}
					</ul>
				</div>
			</div>
		</div>
	</div>
</script>