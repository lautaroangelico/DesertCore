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

// login module configurations
$cfg = loadModuleConfig('login');
if(!is_array($cfg)) throw new Exception(lang('error_66'));

// social configurations
$socialCfg = loadConfig('social');
if(!is_array($socialCfg)) throw new Exception(lang('error_66'));

// social status
if(!$socialCfg['enabled'] || !$socialCfg['provider']['google']['enabled']) throw new Exception(lang('error_79', array(Handler::websiteLink('login'))));

// adapter configuration
$adapterConfig = adapterConfig('google', 'social/login/google');
if(!is_array($adapterConfig)) throw new Exception(lang('error_85'));

try {
	
	// hybridauth
    try {
		$adapter = new Hybridauth\Provider\Google($adapterConfig);
		$adapter->authenticate();
		$isConnected = $adapter->isConnected();
		$userProfile = $adapter->getUserProfile();
		$adapter->disconnect();
	} catch(Exception $ex) {
		if(config('debug')) {
			throw new Exception($ex->getMessage());
		} else {
			throw new Exception(lang('error_80', array(Handler::websiteLink('login'))));
		}
	}
	
	// check social id
	if(!check($userProfile->identifier)) throw new Exception(lang('error_80', array(Handler::websiteLink('login'))));
	
	// account login
	$Login = new AccountLogin();
	$Login->setGoogleId($userProfile->identifier);
	$Login->googleLogin();
	
	// redirect
	redirect('account/profile');
	
} catch(Exception $ex) {
	if($adapter) $adapter->disconnect();
    message('error', $ex->getMessage());
}