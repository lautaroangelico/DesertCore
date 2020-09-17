<?php
/**
 * DesertCore CMS
 * https://desertcore.com/
 * 
 * @version 1.2.0
 * @author Lautaro Angelico <https://lautaroangelico.com/>
 * @copyright (c) 2018-2020 Lautaro Angelico, All Rights Reserved
 * 
 * Licensed under the MIT license
 * http://opensource.org/licenses/MIT
 * 
 * Donate to the Project:
 * https://desertcore.com/donate
 * 
 * Contribute:
 * https://github.com/lautaroangelico/DesertCore
 */

// Access
if(!defined('access') or !access) die();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<title><?php echo Handler::websiteTitle(); ?></title>
		<meta name="generator" content="WebEngine <?php echo Handler::getDesertCoreVersion(); ?>"/>
		<meta name="author" content="<?php echo Handler::getWebEngineAuthor(); ?>"/>
		<meta name="description" content="<?php echo Handler::getWebsiteDescription(); ?>"/>
		<meta name="keywords" content="<?php echo Handler::getWebsiteKeywords(); ?>"/>
		
		<link rel="shortcut icon" href="<?php echo Handler::templateBase(); ?>favicon.ico"/>
		<link href="//fonts.googleapis.com/css?family=Marcellus" rel="stylesheet">
		<link href="//fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
		<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.4.0/sweetalert2.min.css" rel="stylesheet"/>
		<link href="<?php echo Handler::templateCSS('main.css'); ?>" rel="stylesheet" media="screen">
		<link href="<?php echo Handler::templateCSS('profiles.css'); ?>" rel="stylesheet" media="screen">
		<script>
			var baseUrl = '<?php echo Handler::websiteLink(); ?>';
		</script>
	</head>
	<body>
		
		<header>
			<!-- Fixed navbar -->
			<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
				<div class="container">
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#websitenav" aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="websitenav">
						<ul class="navbar-nav mr-auto">
							<li class="nav-item">
								<a class="nav-link" href="<?php echo Handler::websiteLink(); ?>"><?php echo lang('menu_txt_1'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo Handler::websiteLink('connect'); ?>"><?php echo lang('menu_txt_17'); ?></a>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_txt_8'); ?></a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('shop/items'); ?>"><?php echo lang('menu_txt_19'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('shop/cash'); ?>"><?php echo lang('menu_txt_20'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('shop/redeem'); ?>"><?php echo lang('menu_txt_21'); ?></a>
								</div>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?php echo Handler::websiteLink('downloads'); ?>"><?php echo lang('menu_txt_7'); ?></a>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_txt_14'); ?></a>
								<div class="dropdown-menu" aria-labelledby="dropdown01">
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('rankings/level'); ?>"><?php echo lang('menu_txt_15'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('rankings/online'); ?>"><?php echo lang('menu_txt_16'); ?></a>
								</div>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_txt_2'); ?></a>
								<div class="dropdown-menu" aria-labelledby="dropdown02">
									<a class="dropdown-item" href="<?php echo config('website_forum_link'); ?>" target="_blank"><?php echo lang('menu_txt_9'); ?></a>
									<a class="dropdown-item" href="<?php echo config('discord_link'); ?>" target="_blank"><?php echo lang('menu_txt_10'); ?></a>
								</div>
							</li>
						</ul>
						<ul class="navbar-nav">
						<?php
						if(config('language_switch_active')) {
							$langPack = Language::getInstalledLanguagePacks();
							if(is_array($langPack)) {
								$customLangPack = check($_SESSION['default_language']) ? $_SESSION['default_language'] : null;
								$langPackActive = check($customLangPack) ? $langPack[$_SESSION['default_language']] : $langPack[config('language_default')];
								if(is_array($langPackActive)) {
						?>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $langPackActive['short_name']; ?></a>
									<div class="dropdown-menu" aria-labelledby="dropdown02">
									<?php
									foreach($langPack as $langPackData) {
										if($langPackData['active'] != 1) continue;
										echo '<a class="dropdown-item" href="'.Handler::websiteLink('language/switch/'.$langPackData['short_name']).'">'.$langPackData['name'].'</a>';
									}
									?>
									</div>
								</li>
						<?php
								}
							}
						}
						?>
						<?php if(!isLoggedIn()) { ?>
							<li class="nav-item">
								<a class="nav-link navbar-red-link" href="<?php echo Handler::websiteLink('register'); ?>"><?php echo lang('menu_txt_3'); ?></a>
							</li>
							<li class="nav-item">
								<a class="nav-link navbar-red-link" href="<?php echo Handler::websiteLink('login'); ?>"><?php echo lang('menu_txt_4'); ?></a>
							</li>
						<?php } else { ?>
							<?php if(isAdmin()) { ?>
							<li class="nav-item">
								<a class="nav-link navbar-red-link" href="<?php echo Handler::websiteLink('admincp'); ?>/"><?php echo lang('admincp'); ?></a>
							</li>
							<?php } ?>
							<li class="nav-item dropdown">
								<a class="nav-link navbar-red-link dropdown-toggle" href="#" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo lang('menu_txt_5'); ?></a>
								<div class="dropdown-menu" aria-labelledby="dropdown02">
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('account/profile'); ?>"><?php echo lang('menu_txt_11'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('account/characters'); ?>"><?php echo lang('menu_txt_12'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('account/exchange'); ?>"><?php echo lang('menu_txt_23'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('shop/history'); ?>"><?php echo lang('menu_txt_22'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('account/vote'); ?>"><?php echo lang('menu_txt_13'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('account/tickets/list'); ?>"><?php echo lang('menu_txt_18'); ?></a>
									<a class="dropdown-item" href="<?php echo Handler::websiteLink('logout'); ?>"><?php echo lang('menu_txt_6'); ?></a>
								</div>
							</li>
						<?php } ?>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		
		<div class="bg-content bg-<?php echo Handler::getLoadedModule(); ?>">
			<div class="header-logo">
				<a href="<?php echo Handler::websiteLink(); ?>"><img src="<?php echo Handler::templateIMG('logo.png'); ?>"></a>
			</div>
		</div>
		
		<!-- Begin page content -->
		<main role="main" class="container">
			<div class="main-title">
				<div class="row">
					<div class="col-12">
						<h1><?php echo Handler::getModuleTitle(); ?></h1>
					</div>
				</div>
			</div>
			<div class="main-content">
				<?php Handler::loadModule(); ?>
			</div>
		</main>
		
		<div class="social">
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-1 text-center">
						<a href="#facebook" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_facebook.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="#instagram" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_instagram.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="#twitter" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_twitter.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="#youtube" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_youtube.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="#twitch" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_twitch.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="<?php echo config('discord_link'); ?>" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_discord.png'); ?>" /></a>
					</div>
					<div class="col-1 text-center">
						<a href="<?php echo config('website_forum_link'); ?>" target="_blank"><img class="social-icon" src="<?php echo Handler::templateIMG('social_forum.png'); ?>" /></a>
					</div>
				</div>
			</div>
		</div>
		
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-8">
						<p><?php echo lang('footer_txt_1', array(date("Y"), config('server_name'), Handler::websiteLink('terms-of-service'), Handler::websiteLink('privacy-policy'), Handler::websiteLink('contact'))); ?></p>
						<p><?php echo lang('footer_txt_2'); ?></p>
						<br />
						<p><?php echo lang('footer_txt_3'); ?></p>
						<br />
						<p><a href="<?php echo Handler::getWebEngineWebsite(); ?>" target="_blank" title="<?php echo Handler::getDesertCoreVersion(true); ?>"><img src="<?php echo Handler::templateIMG('desertcore_footer_logo.png'); ?>"/></a></p>
					</div>
					<div class="col-4">
						<div class="row">
							<div class="col-6 text-center">
								<span><?php echo lang('server_time'); ?></span><br />
								<span class="footer-time"><time id="tServerTime"></time></span>
							</div>
							<div class="col-6 text-center">
								<span><?php echo lang('user_time'); ?></span><br />
								<span class="footer-time"><time id="tLocalTime"></time></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		
		<!-- JS -->
		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
		<script src="<?php echo Handler::templateJS('main.js'); ?>"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.4.0/sweetalert2.min.js"></script>
	</body>
</html>