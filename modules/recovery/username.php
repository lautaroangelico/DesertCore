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

// recaptcha configs
$recaptchaCfg = loadConfig('recaptcha');
if(!is_array($recaptchaCfg)) throw new Exception(lang('error_66'));

// form submit
if(check($_POST['webenginePasswordRecovery_submit'])) {
	try {
		if($recaptchaCfg['username_recovery']) {
			$recaptcha = new \ReCaptcha\ReCaptcha($recaptchaCfg['secret_key']);
			
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], Handler::userIP());
			if(!$resp->isSuccess()) {
				// recaptcha failed
				$errors = $resp->getErrorCodes();
				throw new Exception(lang('error_18'));
			}
		}
		
		$Recovery = new Account();
		$Recovery->setEmail($_POST['webengineEmail']);
		$Recovery->recoverUsername();
		message('success', lang('success_23'));
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// form
echo '<div class="row">';
	echo '<div class="col-6 offset-3">';
		// text
		echo '<p>'.lang('recovery_txt_4').'</p><br />';
		
		echo '<form action="" method="post">';
			echo '<div class="form-group">';
				echo '<label for="webengineEmail">'.lang('recovery_txt_5').'</label>';
				echo '<input type="text" class="form-control" id="webengineEmail" name="webengineEmail" required autofocus>';
			echo '</div>';
			
			// recaptcha
			if($recaptchaCfg['username_recovery']) {
				echo '<div class="form-group">';
					echo '<div class="g-recaptcha" data-sitekey="'.$recaptchaCfg['site_key'].'"></div>';
				echo '</div>';
				echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
			}
			
			echo '<div class="form-group">';
				echo '<button type="submit" name="webenginePasswordRecovery_submit" value="submit" class="btn btn-primary">'.lang('recovery_txt_7').'</button>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
echo '</div>';