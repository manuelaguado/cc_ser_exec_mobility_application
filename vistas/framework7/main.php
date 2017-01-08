<?php include('templates/bottom.php'); ?>
<div class="views">
	<div class="navbar" style="z-index:6000 !important;">
		<div class="navbar-inner navbar-on-left">
			<div class="left"></div>
			<div class="center sliding"></div>
			<div class="right"></div>
		</div>
		<div class="navbar-inner navbar-on-center" id="color_marco">
			<div class="left sliding" style="transform: translate3d(0px, 0px, 0px);">
				<a href="#" class="link icon-only open-panel">
					<span class="kkicon icon-menu" style="color:#fff;"></span>
				</a>		
			</div>
			<?php $titlr_bar = ($_SESSION['cat_statusoperador'] == 10)?'S U S P E N D I D O':SITE_NAME; ?>
			<?php $tiling = ($_SESSION['cat_statusoperador'] == 10)?'parpadea':''; ?>
			<div class="center sliding <?=$tiling?>" style="left: -6.5px; transform: translate3d(0px, 0px, 0px);"><?=$titlr_bar?></div>
			<div class="right">
				<a href="javascript:void(0);" onclick="location.reload(true);" class="infolink icon-only" id="infolink">
					<span class="kkicon icon-link" style="color:#3c99fc !important"></span>
				</a>
			</div>
		</div>
	</div>
    <div class="view view-main">
        <div class="pages navbar-fixed toolbar-fixed">
            <div data-page="index" class="page page-bg">
                <div class="page-content">
					<nav class="dashboard-menu">

					</nav>
                </div>
            </div>
        </div>
		
	</div>
</div>