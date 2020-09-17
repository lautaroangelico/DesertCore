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

// module configs
$cfg = loadModuleConfig('login');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// recaptcha configs
$recaptchaCfg = loadConfig('recaptcha');
if(!is_array($recaptchaCfg)) throw new Exception(lang('error_66'));

// Login Process
if(check($_POST['webengineLogin_submit'])) {
	try {
		if($recaptchaCfg['login']) {
			$recaptcha = new \ReCaptcha\ReCaptcha($recaptchaCfg['secret_key']);
			
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], Handler::userIP());
			if(!$resp->isSuccess()) {
				// recaptcha failed
				$errors = $resp->getErrorCodes();
				throw new Exception(lang('error_18'));
			}
		}
		
		$Login = new AccountLogin();
		if(Validator::Email($_POST['webengineLogin_user'])) {
			$Login->setEmail($_POST['webengineLogin_user']);
		} else {
			$Login->setUsername($_POST['webengineLogin_user']);
		}
		$Login->setPassword($_POST['webengineLogin_pwd']);
		$Login->login();
		
		redirect('account/profile');
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-6">';
		echo '<p><strong>'.lang('login_txt_6').'</strong></p>';
		echo '<div>';
			echo '<a href="'.Handler::websiteLink('social/login/facebook').'" class="facebook-button"><span class="social-logo"></span><span class="social-text">'.lang('login_txt_7').'</span></a>';
			echo '<a href="'.Handler::websiteLink('social/login/google').'" class="google-button"><span class="social-logo"></span><span class="social-text">'.lang('login_txt_8').'</span></a>';
		echo '</div>';
	echo '</div>';
	echo '<div class="col-6">';
		echo '<form action="" method="post">';
			echo '<div class="form-group">';
				echo '<input type="text" class="form-control" name="webengineLogin_user" placeholder="'.lang('login_txt_1').'" required autofocus>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<input type="password" class="form-control" name="webengineLogin_pwd" placeholder="'.lang('login_txt_2').'" required>';
			echo '</div>';
			
			if($recaptchaCfg['login']) {
				// recaptcha v2
				echo '<div class="form-group">';
					echo '<div class="g-recaptcha" data-sitekey="'.$recaptchaCfg['site_key'].'"></div>';
				echo '</div>';
				echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
			}
			
			echo '<div class="form-group">';
				echo '<button type="submit" name="webengineLogin_submit" value="submit" class="btn btn-login btn-block">'.lang('login_txt_3').'</button>';
			echo '</div>';
			echo '<div class="form-group login-help-block">';
				echo '<p>'.lang('login_txt_9', array(Handler::websiteLink('terms-of-service'))).'</p>';
				echo '<ul>';
					echo '<li>';
						echo lang('login_txt_10');
						echo ' <a href="'.Handler::websiteLink('recovery').'" class="pull-right">'.lang('login_txt_11').'</a>';
					echo '</li>';
					echo '<li>';
						echo lang('login_txt_12');
						echo ' <a href="'.Handler::websiteLink('register').'" class="pull-right">'.lang('login_txt_13').'</a>';
					echo '</li>';
				echo '</ul>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
echo '</div>';


