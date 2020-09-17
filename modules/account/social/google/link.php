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

// social configurations
$socialCfg = loadConfig('social');
if(!is_array($socialCfg)) throw new Exception(lang('error_66'));

// social status
if(!$socialCfg['enabled'] || !$socialCfg['provider']['google']['enabled']) redirect('account/profile');

// account preferences
$AccountPreferences = new AccountPreferences();
$AccountPreferences->setUsername($_SESSION['username']);
$accountPreferencesData = $AccountPreferences->getAccountPreferencesFromUsername();

// check if already linked
if(check($accountPreferencesData['google_id'])) {
	redirect('account/profile');
	die();
}

// adapter configuration
$adapterConfig = adapterConfig('google', 'account/social/google/link');
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
			throw new Exception(lang('error_84', array(Handler::websiteLink('account/profile'))));
		}
	}
	
	// check social id and name
	if(!check($userProfile->identifier)) throw new Exception(lang('error_84', array(Handler::websiteLink('account/profile'))));
	if(!check($userProfile->displayName)) throw new Exception(lang('error_84', array(Handler::websiteLink('account/profile'))));
	
	// link social account
	$AccountPreferences->setGoogleId($userProfile->identifier);
	$AccountPreferences->setGoogleName($userProfile->displayName);
	if(!$AccountPreferences->linkGoogle()) throw new Exception(lang('error_84', array(Handler::websiteLink('account/profile'))));
	
	// redirect
	redirect('account/profile');
	
} catch(Exception $ex) {
	if($adapter) $adapter->disconnect();
	message('error', $ex->getMessage());
}