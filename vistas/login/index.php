<!DOCTYPE html>
<html lang="es">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta charset="utf-8" />
	<title><?=SITE_NAME?> - Ingreso</title>

	<meta name="description" content="User login page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

	<!-- bootstrap & fontawesome -->
	<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/bootstrap.css" />
	<link rel="stylesheet" href="<?=URL_PUBLIC?>components/font-awesome/css/font-awesome.css" />
	<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/mediasite.css" />
	<link rel="icon" href="<?=FW7?>assets/img/favicons/favicon-32x32.png?v="<?=$this->token(6);?> />
	<!-- text fonts -->
	<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace-fonts.css" />

	<!-- ace styles -->
	<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

	<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?=ACE?>css/ace-part2.css" />
		<![endif]-->
	<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace-rtl.css" />

	<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?=ACE?>css/ace-ie.css" />
		<![endif]-->

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

	<!--[if lt IE 9]>
		<script src="<?=ACE?>js/html5shiv.js"></script>
		<script src="<?=ACE?>js/respond.js"></script>
		<![endif]-->
</head>
<body class="login-layout login-layout blur-login">
	<div class="main-container">
		<div class="main-content">
			<div class="row">
				<div class="col-sm-10 col-sm-offset-1">
					<div class="login-container">
						<div class="center">				
							<h1>
									<i class="icon-centralcar_star_gradient"></i><br>
									<span class="red"><?=SITE_NAME?></span>
								</h1>
							<h4 class="blue" id="id-company-text">&copy; <?=SLOGAN_NAME?></h4>
						</div>

						<div class="space-6"></div>

						<div class="position-relative">
							<div id="login-box" class="login-box visible widget-box no-border">
								<div class="widget-body">
									<div class="widget-main">
										<h4 class="header blue lighter bigger">
												<i class="ace-icon fa fa-coffee green"></i>
												Porfavor ingrese sus credenciales
											</h4>

										<div class="space-6"></div>

										<form role="form" id="login">
											<fieldset>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
															<input  id="usuario" name="usuario" type="text" class="form-control" placeholder="usuario" onkeypress="valida_logeo(event,'noDec','2');" />
															<i class="ace-icon fa fa-user"></i>
														</span>
												</label>
												<label class="block clearfix">
													<span class="block input-icon input-icon-right">
															 <input  id="password" name="password" type="password" class="form-control" placeholder="Contraseña" onkeypress="valida_logeo(event,'noDec','2');" />
															<i class="ace-icon fa fa-lock"></i>
														</span>
												</label>

												<div class="space"></div>

												<div class="clearfix">
													<a onclick="valida_logeo(event,'noDec','1');" class="width-35 pull-right btn btn-sm btn-primary">
														<i class="icon-key"></i> Ingresar
													</a>
												</div>

												<div class="space-4"></div>
											</fieldset>
										</form>
									</div>
								</div>
								<!-- /.widget-body -->
							</div>
						</div>
						<!-- /.position-relative -->
					</div>
				</div>
				<!-- /.col -->
			</div>
			<!-- /.row -->
		</div>
		<!-- /.main-content -->
	</div>
	<!-- /.main-container -->

	<!--[if !IE]> -->
	<script type="text/javascript">
		window.jQuery || document.write("<script src='<?=URL_PUBLIC?>components/jquery/dist/jquery.js'>"+"<"+"/script>");
	</script>
	<!-- <![endif]-->

	<script type="text/javascript">
		if ('ontouchstart' in document.documentElement) document.write("<script src='<?=ACE?>js/jquery.mobile.custom.js'>" + "<" + "/script>");
	</script>

	<!-- inline scripts related to this page -->
	<script type="text/javascript">
		jQuery(function($) {
			$(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible'); //hide others
				$(target).addClass('visible'); //show target
			});
		});
		function show_box(id) {
			jQuery('.widget-box.visible').removeClass('visible');
			jQuery('#' + id).addClass('visible');
		}
	</script>
	<script>
		var url_app = '<?=URL_APP?>';
	</script>
	<script src="<?=URL_PUBLIC?>js/generales.js"></script>
	<script src="<?=URL_PUBLIC?>js/common.js"></script>
	<script src="<?=URL_PUBLIC?>components/bootstrap/dist/js/bootstrap.js"></script>
</body>

</html>