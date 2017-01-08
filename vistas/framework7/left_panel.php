<div class="panel panel-left panel-reveal">
    <div class="line"></div>

    <div class="logo-box">
        <h2><?=SITE_NAME?></h2>
        <div><?=SLOGAN_NAME?></div>
    </div>

    <div class="list-block mt-15">
        <div class="list-group">
            <nav>
                <ul>
                    <li class="divider">
                        Menu
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content">
                            <div class="item-media">
                                <i class="fa fa-map-o"></i>
                            </div>
                            <div data-popup=".detalle_viaje" class="open-popup menu-link1 close-panel item-inner">
                                <div class="item-title">Detalles del viaje</div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content">
                            <div class="item-media">
                                <i class="fa fa-code-fork"></i>
                            </div>
                            <div data-popup=".detalle_cordon" class="open-popup menu-link1 close-panel item-inner">
                                <div class="item-title">Cordón</div>
                            </div>
                        </a>
                    </li>
					<!--onclick="deleteDatabase('serexecutive');"-->
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content">
                            <div class="item-media">
                                <i class="fa fa-database"></i>
                            </div>
                            <div onclick="reloadlibs()"; class="menu-link1 close-panel item-inner">
                                <div class="item-title">Database v.<span id="allow_update"></span></div>
                            </div>
                        </a>
                    </li>
					
					<li class="divider">
                        Esquema de color
                    </li>
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content esquema_verde">
                            <div class="item-media">
                                <div class="esquema_botones_1">&nbsp;</div>&nbsp;
								<div class="esquema_barras_1">&nbsp;</div>&nbsp;
								<div class="esquema_fondo_1">&nbsp;</div>&nbsp;
								<div class="esquema_fuente_1">&nbsp;</div>&nbsp;
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Verde</div>
                            </div>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content esquema_rojo">
                            <div class="item-media">
                                <div class="esquema_botones_2">&nbsp;</div>&nbsp;
								<div class="esquema_barras_2">&nbsp;</div>&nbsp;
								<div class="esquema_fondo_1">&nbsp;</div>&nbsp;
								<div class="esquema_fuente_1">&nbsp;</div>&nbsp;
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Rojo</div>
                            </div>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content esquema_naranja">
                            <div class="item-media">
                                <div class="esquema_botones_3">&nbsp;</div>&nbsp;
								<div class="esquema_barras_3">&nbsp;</div>&nbsp;
								<div class="esquema_fondo_1">&nbsp;</div>&nbsp;
								<div class="esquema_fuente_1">&nbsp;</div>&nbsp;
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Naranja</div>
                            </div>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content esquema_azul">
                            <div class="item-media">
                                <div class="esquema_botones_4">&nbsp;</div>&nbsp;
								<div class="esquema_barras_4">&nbsp;</div>&nbsp;
								<div class="esquema_fondo_1">&nbsp;</div>&nbsp;
								<div class="esquema_fuente_1">&nbsp;</div>&nbsp;
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Azul</div>
                            </div>
                        </a>
                    </li>
					<li>
                        <a href="javascript:void(0)" class="item-link close-panel item-content esquema_contraste">
                            <div class="item-media">
                                <div class="esquema_botones_5">&nbsp;</div>&nbsp;
								<div class="esquema_barras_5">&nbsp;</div>&nbsp;
								<div class="esquema_fondo_5">&nbsp;</div>&nbsp;
								<div class="esquema_fuente_5">&nbsp;</div>&nbsp;
                            </div>
                            <div class="item-inner">
                                <div class="item-title">Alto contraste</div>
                            </div>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>