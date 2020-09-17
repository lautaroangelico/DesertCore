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
$cfg = loadModuleConfig('register');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// recaptcha configs
$recaptchaCfg = loadConfig('recaptcha');
if(!is_array($recaptchaCfg)) throw new Exception(lang('error_66'));

// registration process
if(check($_POST['webengineRegister_submit'])) {
	try {
		if($recaptchaCfg['registration']) {
			$recaptcha = new \ReCaptcha\ReCaptcha($recaptchaCfg['secret_key']);
			
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], Handler::userIP());
			if(!$resp->isSuccess()) {
				// recaptcha failed
				$errors = $resp->getErrorCodes();
				throw new Exception(lang('error_18'));
			}
		}
		
		if($_POST['webengineRegister_pwd'] != $_POST['webengineRegister_pwdc']) throw new Exception(lang('error_8'));
		
		$Registration = new AccountRegister();
		$Registration->setUsername($_POST['webengineRegister_user']);
		$Registration->setPassword($_POST['webengineRegister_pwd']);
		$Registration->setEmail($_POST['webengineRegister_email']);
		$Registration->registerAccount();
		
		if($cfg['verify_email']) {
			// email verification required
			message('success', lang('success_18'));
		} else {
			// account created
			message('success', lang('success_1'));
			htmlRedirect('login', 3);
		}
		
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

echo '<div class="row">';
	echo '<div class="col-6">';
		echo '<p><strong>'.lang('register_txt_11').'</strong></p>';
		echo '<div>';
			echo '<a href="'.Handler::websiteLink('social/register/facebook').'" class="facebook-button"><span class="social-logo"></span><span class="social-text">'.lang('login_txt_7').'</span></a>';
			echo '<a href="'.Handler::websiteLink('social/register/google').'" class="google-button"><span class="social-logo"></span><span class="social-text">'.lang('login_txt_8').'</span></a>';
		echo '</div>';
	echo '</div>';
	echo '<div class="col-6">';
		echo '<form class="form-horizontal" action="" method="post">';
			echo '<div class="form-group">';
				echo '<input type="text" class="form-control" name="webengineRegister_user" placeholder="'.lang('register_txt_1').'" required autofocus>';
				echo '<span id="helpBlock" class="help-block">'.lang('register_txt_6').'</span>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<input type="password" class="form-control" name="webengineRegister_pwd" placeholder="'.lang('register_txt_2').'" required>';
				echo '<span id="helpBlock" class="help-block">'.lang('register_txt_7').'</span>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<input type="password" class="form-control" name="webengineRegister_pwdc" placeholder="'.lang('register_txt_3').'" required>';
				echo '<span id="helpBlock" class="help-block">'.lang('register_txt_8').'</span>';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<input type="text" class="form-control" name="webengineRegister_email" placeholder="'.lang('register_txt_4').'" required>';
				echo '<span id="helpBlock" class="help-block">'.lang('register_txt_9').'</span>';
			echo '</div>';
			
			if($recaptchaCfg['registration']) {
				// recaptcha v2
				echo '<div class="form-group">';
					echo '<div class="g-recaptcha" data-sitekey="'.$recaptchaCfg['site_key'].'"></div>';
				echo '</div>';
				echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
			}
			
			echo '<div class="form-group">';
				echo '<button type="submit" name="webengineRegister_submit" value="submit" class="btn btn-login btn-block">'.lang('register_txt_5').'</button>';
			echo '</div>';
			echo '<div class="form-group login-help-block">';
				echo '<p>'.lang('register_txt_10', array(Handler::websiteLink('terms-of-service'), Handler::websiteLink('privacy-policy'))).'</p>';
				echo '<ul>';
					echo '<li>';
						echo lang('register_txt_12');
						echo ' <a href="'.Handler::websiteLink('login').'" class="pull-right">'.lang('register_txt_14').'</a>';
					echo '</li>';
					echo '<li>';
						echo lang('register_txt_13');
						echo ' <a href="'.Handler::websiteLink('recovery').'" class="pull-right">'.lang('register_txt_15').'</a>';
					echo '</li>';
				echo '</ul>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
echo '</div>';