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
if(check($_POST['redeem_submit'])) {
	try {
		if($recaptchaCfg['redeem_code']) {
			$recaptcha = new \ReCaptcha\ReCaptcha($recaptchaCfg['secret_key']);
			
			$resp = $recaptcha->verify($_POST['g-recaptcha-response'], Handler::userIP());
			if(!$resp->isSuccess()) {
				// recaptcha failed
				$errors = $resp->getErrorCodes();
				throw new Exception(lang('error_18'));
			}
		}
		
		$RedeemCode = new RedeemCode();
		$RedeemCode->setCode($_POST['redeem_code']);
		$RedeemCode->redeemCode();
		
		message('success', lang('success_24'));
		
	} catch (Exception $ex) {
		message('error', $ex->getMessage());
	}
}

// form
echo '<div class="row">';
	echo '<div class="col-6 offset-3">';
		// text
		echo '<h2>'.strtoupper(lang('shop_redeem_txt_1')).'</h2><br />';
		
		echo '<form action="" method="post">';
			echo '<div class="form-group">';
				echo '<input type="text" class="form-control form-control-lg" id="redeemCode" name="redeem_code" placeholder="'.lang('shop_redeem_txt_2').'" required autofocus>';
			echo '</div>';
			
			// recaptcha
			if($recaptchaCfg['redeem_code']) {
				echo '<div class="form-group">';
					echo '<div class="g-recaptcha" data-sitekey="'.$recaptchaCfg['site_key'].'"></div>';
				echo '</div>';
				echo '<script src=\'https://www.google.com/recaptcha/api.js\'></script>';
			}
			
			echo '<div class="form-group">';
				echo '<button type="submit" name="redeem_submit" value="submit" class="btn btn-lg btn-primary btn-block">'.lang('shop_redeem_txt_3').'</button>';
			echo '</div>';
		echo '</form>';
	echo '</div>';
echo '</div>';