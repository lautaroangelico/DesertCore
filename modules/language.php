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

if(config('language_switch_active')) {

	// check request data
	if(!check($_GET['switch'])) redirect($_SERVER['HTTP_REFERER']);

	// get language dir
	$languageDir = Language::getLanguageDirectoryByShortName($_GET['switch']);
	if(!check($languageDir)) redirect($_SERVER['HTTP_REFERER']);

	// check current default language
	if(check($_SESSION['default_language'])) {
		if($_SESSION['default_language'] == $languageDir) redirect($_SERVER['HTTP_REFERER']);
	}

	// set session language
	$_SESSION['default_language'] = $languageDir;

	// save preferences
	if(isLoggedIn()) {
		$AccountPreferences = new AccountPreferences();
		$AccountPreferences->setUsername($_SESSION['username']);
		$AccountPreferences->setLanguage($languageDir);
		$AccountPreferences->createAccountPreferences();
		$AccountPreferences->setDefaultLanguage();
	}

}

redirect($_SERVER['HTTP_REFERER']);