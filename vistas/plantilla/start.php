<!DOCTYPE html>
<html lang="es">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?=SITE_NAME?></title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=URL_PUBLIC?>components/jquery/dist/jquery.js'>"+"<"+"/script>");
		</script>
		<style>
		body {
			overflow: hidden;
		}
		#initpreloader {
			position: fixed;
			top:0; left:0;
			right:0; bottom:0;
			background: #000;
			z-index: 100;
		}
		#initloader {
			width: 120px;
			height: 120px;
			position: absolute;
			left:50%; top:50%;
			background: url(dist/images/gears.svg) no-repeat center 0;
			margin:-50px 0 0 -50px;
		}
		#initloader:before {
			content: "<?=SITE_NAME?>";
			display: inline-block;
			color: #FFFFFF;
			background-color: black;
			position: relative;
			font-size: 2em;
			top: 120px;
			width: 270px;
			left: -70px;;
			text-align: center;
		}
		</style>
		<script>
		$(window).load(function() {
			$('#initpreloader').fadeOut('slow');
			$('body').css({'overflow':'visible'});
			var intro;
			$(function() {
				intro = new buzz.sound( "dist/audio/intro", {
					formats: ['mp3']
				}).setVolume(100).play();
			});
		})
		</script>
		<!-- <![endif]-->
		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		 <link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace-ie.css" />
		<![endif]-->
		<!--Component Styles-->
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/_mod/jquery-ui.custom/jquery-ui.custom.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/chosen/chosen.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/bootstrap-timepicker/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/bootstrap-daterangepicker/daterangepicker.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/jquery-colorbox/example1/colorbox.css" />

		<!-- Fuente mediasite -->
		<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/mediasite.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace-fonts.css" />

		<!-- bootstrap & fontawesome icofont-->
		<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/font-awesome/css/font-awesome.min.css" />
		<link rel='stylesheet' id='icofont-main-css' href='<?=URL_PUBLIC?>components/icofont/css/icofont.css?ver=4.6.1' type='text/css' media='all'/>

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=URL_PUBLIC?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--Icon-->
		<link rel="icon" href="<?=FW7?>assets/img/favicons/favicon-32x32.png?v="<?=$this->token(6);?> />

		<!-- Estilo de sitio -->
		<link rel="stylesheet" href="<?=URL_PUBLIC?>dist/css/aplicacion.css" >
		<link rel="stylesheet" href="<?=URL_PUBLIC?>dist/css/animate.min.css" media="screen">

		<!-- Javascript -->
		<script>var url_app = '<?=URL_APP?>';</script>

		<script>
			/*milesimas 20000 = 20 segundos*/
			function verifica_tiempo_session(){
				setInterval("verifica_session()",10000);
			}
		</script>
		<?php if(DEVELOPMENT){ ?>
		<style>
			/*.navbar {
				background: #FF6C00;
			}
			.ace-nav > li.light-blue > a {
				background-color: #FF6C00;
			}
			.ace-nav > li.light-blue > a:hover, .ace-nav > li.light-blue > a:focus, .ace-nav > li.open.light-blue > a {
				background-color: #FF6C00;
			}*/
		</style>
		<?php } ?>
	</head>
	<?php include ('body.php');?>
</html>
