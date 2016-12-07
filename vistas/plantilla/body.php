	<body class="no-skin" onload="verifica_tiempo_session();">

	
	<?php include ('navbar.php'); ?>
		
		
		
		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
			
			<?php include ('sidebar.php'); ?>
			<div class="main-content" id="contenedor_principal">
				<?php
					include(URL_VISTA.'inicio/index.php');
				?>			
			</div><!-- /.main-content -->
			
		<?php include('footer.php'); ?>	
	</body>