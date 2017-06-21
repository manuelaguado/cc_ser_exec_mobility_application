<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Error 404 - <?=SITE_NAME?></title>

		<meta name="description" content="404 Error Page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?=ACE?>css/bootstrap.css" />
		<link rel="stylesheet" href="<?=URL_PUBLIC?>components/font-awesome/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?=ACE?>css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?=ACE?>css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?=ACE?>css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?=ACE?>css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?=ACE?>js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?=ACE?>js/html5shiv.js"></script>
		<script src="<?=ACE?>js/respond.js"></script>
		<![endif]-->
	</head>


		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
			<div class="main-content">
				<?php
				include('404.php');
				?>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder"><?=SITE_NAME?></span>
						 &copy; 2013-2017
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
					</div>

					<!-- /section:basics/footer -->
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?=URL_PUBLIC?>components/jquery/dist/jquery.js'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?=ACE?>js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?=ACE?>js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?=URL_PUBLIC?>components/bootstrap/dist/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="<?=ACE?>js/src/elements.scroller.js"></script>
		<script src="<?=ACE?>js/src/elements.colorpicker.js"></script>
		<script src="<?=ACE?>js/src/elements.fileinput.js"></script>
		<script src="<?=ACE?>js/src/elements.typeahead.js"></script>
		<script src="<?=ACE?>js/src/elements.wysiwyg.js"></script>
		<script src="<?=ACE?>js/src/elements.spinner.js"></script>
		<script src="<?=ACE?>js/src/elements.treeview.js"></script>
		<script src="<?=ACE?>js/src/elements.wizard.js"></script>
		<script src="<?=ACE?>js/src/elements.aside.js"></script>
		<script src="<?=ACE?>js/src/ace.js"></script>
		<script src="<?=ACE?>js/src/ace.ajax-content.js"></script>
		<script src="<?=ACE?>js/src/ace.touch-drag.js"></script>
		<script src="<?=ACE?>js/src/ace.sidebar.js"></script>
		<script src="<?=ACE?>js/src/ace.sidebar-scroll-1.js"></script>
		<script src="<?=ACE?>js/src/ace.submenu-hover.js"></script>
		<script src="<?=ACE?>js/src/ace.widget-box.js"></script>
		<script src="<?=ACE?>js/src/ace.settings.js"></script>
		<script src="<?=ACE?>js/src/ace.settings-rtl.js"></script>
		<script src="<?=ACE?>js/src/ace.settings-skin.js"></script>
		<script src="<?=ACE?>js/src/ace.widget-on-reload.js"></script>
		<script src="<?=ACE?>js/src/ace.searchbox-autocomplete.js"></script>

		<!-- inline scripts related to this page -->

	</body>
</html>
