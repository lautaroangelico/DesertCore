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

// register module configurations
$cfg = loadModuleConfig('register');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// social configurations
$socialCfg = loadConfig('social');
if(!is_array($socialCfg)) throw new Exception(lang('error_66'));

// social status
if(!$socialCfg['enabled'] || !$socialCfg['provider']['google']['enabled']) throw new Exception(lang('error_87', array(Handler::websiteLink('register'))));

// adapter configuration
$adapterConfig = adapterConfig('google', 'social/register/google');
if(!is_array($adapterConfig)) throw new Exception(lang('error_85'));

try {
	
	// hybridauth
	try {
		$adapter = new Hybridauth\Provider\Google($adapterConfig);
		$adapter->authenticate();
		$isConnected = $adapter->isConnected();
		$userProfile = $adapter->getUserProfile();
		//$adapter->disconnect();
	} catch(Exception $ex) {
		if(config('debug')) {
			throw new Exception($ex->getMessage());
		} else {
			throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
		}
	}
	
	// check social id
	if(!check($userProfile->identifier)) throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
	if(!check($userProfile->profileURL)) throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
	if(!check($userProfile->emailVerified)) throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
	if(!check($userProfile->photoURL)) throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
	if(!check($userProfile->displayName)) throw new Exception(lang('error_88', array(Handler::websiteLink('register'))));
	
	// registration process
	if(check($_POST['webengineRegister_submit'])) {
		try {
			
			if($_POST['webengineRegister_pwd'] != $_POST['webengineRegister_pwdc']) throw new Exception(lang('error_8'));
			
			$Registration = new AccountRegister();
			$Registration->setUsername($_POST['webengineRegister_user']);
			$Registration->setPassword($_POST['webengineRegister_pwd']);
			$Registration->setEmail($_POST['webengineRegister_email']);
			$Registration->disableVerification();
			$Registration->registerAccount();
			
			// link google account
			$AccountPreferences = new AccountPreferences();
			$AccountPreferences->setUsername($_POST['webengineRegister_user']);
			$AccountPreferences->setGoogleId($userProfile->identifier);
			$AccountPreferences->setGoogleName($userProfile->displayName);
			if(!$AccountPreferences->linkGoogle()) throw new Exception(lang('error_82'));
			
			
			// login account
			$Login = new AccountLogin();
			$Login->setGoogleId($userProfile->identifier);
			$Login->googleLogin();
			
			// disconnect adapter
			$adapter->disconnect();
			
			// redirect
			redirect('account/profile');
			
		} catch (Exception $ex) {
			message('error', $ex->getMessage());
		}
	}
	
	echo '<div class="row">';
		echo '<div class="col-6">';
			echo '<div class="row register-social-block">';
				echo '<div class="col-3">';
					echo '<img src="'.$userProfile->photoURL.'" width="50px" height="auto" />';
				echo '</div>';
				echo '<div class="col-9">';
					echo '<a href="'.$userProfile->profileURL.'" target="_blank" class="social-name">'.$userProfile->displayName.'</a><br />';
					echo '<a href="'.$userProfile->profileURL.'" target="_blank">'.$userProfile->emailVerified.'</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="col-6">';
			echo '<form class="form-horizontal" action="'.Handler::websiteLink('social/register/google').'" method="post">';
				echo '<input type="hidden" name="webengineRegister_email" value="'.$userProfile->emailVerified.'"/>';
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
					echo '<button type="submit" name="webengineRegister_submit" value="submit" class="btn btn-login btn-block">'.lang('register_txt_5').'</button>';
				echo '</div>';
				echo '<div class="form-group login-help-block">';
					echo '<p>'.lang('register_txt_10', array(Handler::websiteLink('terms-of-service'), Handler::websiteLink('privacy-policy'))).'</p>';
					echo '<ul>';
						echo '<li>';
							echo lang('register_txt_12');
							echo '<a href="'.Handler::websiteLink('login').'" class="pull-right">'.lang('register_txt_14').'</a>';
						echo '</li>';
						echo '<li>';
							echo lang('register_txt_13');
							echo '<a href="'.Handler::websiteLink('recovery').'" class="pull-right">'.lang('register_txt_15').'</a>';
						echo '</li>';
					echo '</ul>';
				echo '</div>';
			echo '</form>';
		echo '</div>';
	echo '</div>';
	
} catch(Exception $ex) {
	if($adapter) $adapter->disconnect();
    message('error', $ex->getMessage());
}