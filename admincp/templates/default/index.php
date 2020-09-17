<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="<?php echo Handler::admincpTemplateBase('assets/img/favicon.ico'); ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Admin CP | <?php echo __WEBENGINE_NAME__; ?></title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
	
    <!-- Bootstrap core CSS     -->
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/animate.min.css'); ?>" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/light-bootstrap-dashboard-custom.css'); ?>" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/perfect-scrollbar.min.css'); ?>" rel="stylesheet"/>

    <!--  SweetAlert2 CDN CSS    -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.4.0/sweetalert2.min.css" rel="stylesheet"/>

    <!--  CodeMirror CDN CSS    -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/codemirror.min.css" rel="stylesheet">

    <!--  WebEngine Custom CSS    -->
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/webengine.css'); ?>" rel="stylesheet"/>

    <!--     Fonts and icons     -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link href="<?php echo Handler::admincpTemplateBase('assets/css/pe-icon-7-stroke.css'); ?>" rel="stylesheet" />
	<style>
	<!--
	.CodeMirror {
		border: 1px solid #eee;
		height: 500px;
	}
	-->
	</style>
</head>
<body>

	<div class="wrapper">
		<div class="sidebar" data-color="<?php echo (check(config('admincp_sidebar_color')) ? config('admincp_sidebar_color') : 'azure'); ?>" data-image="<?php echo Handler::admincpTemplateBase('assets/img/sidebar-background.jpg'); ?>">

		<!--

			Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
			Tip 2: you can also add an image using data-image tag

		-->

			<div class="sidebar-wrapper">
				<div class="logo">
					<a href="<?php echo admincp_base(); ?>" class="simple-text">
						<?php echo __WEBENGINE_NAME__; ?>
					</a>
				</div>

				<ul class="nav">
					<?php buildAdmincpSidebar(); ?>
				</ul>
			</div>
		</div>

		<div class="main-panel">
			<nav class="navbar navbar-default navbar-fixed">
				<div class="container-fluid">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="<?php echo admincp_base(); ?>">Admin Panel</a>
					</div>
					<div class="collapse navbar-collapse">
						<ul class="nav navbar-nav navbar-right">
							<li><a href="<?php echo __BASE_URL__; ?>" target="_blank">Website Home</a></li>
							<li><a href="<?php echo __BASE_URL__; ?>logout">Log out</a></li>
						</ul>
					</div>
				</div>
			</nav>


			<div class="content">
				<div class="container-fluid">
					<?php Handler::loadAdmincpModule(); ?>
				</div>
			</div>


			<footer class="footer">
				<div class="container-fluid">
					<p class="copyright pull-right">
						&copy; <script>document.write(new Date().getFullYear())</script> <a href="<?php echo __WEBENGINE_WEBSITE__; ?>" target="_blank"><?php echo __WEBENGINE_NAME__; ?></a>
					</p>
				</div>
			</footer>

		</div>
	</div>

	<!--   Core JS Files   -->
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="<?php echo Handler::admincpTemplateBase('assets/js/bootstrap.min.js'); ?>" type="text/javascript"></script>
	<script src="<?php echo Handler::admincpTemplateBase('assets/js/perfect-scrollbar.jquery.min.js'); ?>" type="text/javascript"></script>

	<!--  Notifications Plugin    -->
	<script src="<?php echo Handler::admincpTemplateBase('assets/js/bootstrap-notify.js'); ?>"></script>

	<!-- Light Bootstrap Table Core javascript and methods for Demo purpose -->
	<script src="<?php echo Handler::admincpTemplateBase('assets/js/light-bootstrap-dashboard.js'); ?>"></script>

	<!-- SweetAlert2 CDN JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.4.0/sweetalert2.min.js"></script>
	
	<!-- CodeMirror CDN JS -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/codemirror.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/xml/xml.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/javascript/javascript.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/css/css.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/htmlmixed/htmlmixed.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/clike/clike.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.33.0/mode/php/php.min.js"></script>
	
	<script src="//cdn.ckeditor.com/4.8.0/full/ckeditor.js"></script>

	<!-- WebEngine AdminCP Main JS -->
	<script src="<?php echo Handler::admincpTemplateBase('assets/js/main.js'); ?>"></script>

	<script>
		$('.sidebar-wrapper').perfectScrollbar();
	</script>
</body>
</html>

