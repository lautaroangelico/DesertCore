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

class Language {
	
	private static $languageInfoFile = 'info.json';
	private static $languageFile = 'language.php';
	private static $infoFileRequiredElements = array(
		'active',
		'locale',
		'author',
		'website',
		'version',
	);
	
	/**
	 * Phrase
	 * 
	 */
	public static function Phrase($phrase, $args=array()) {
		global $lang;
		if(config('language_debug')) return $phrase;
		if(!array_key_exists($phrase, $lang)) return 'ERROR';
		$result = @vsprintf($lang[$phrase], $args);
		if(!$result) return 'ERROR';
		return $result;
	}
	
	/**
	 * getLanguagePhraseList
	 * 
	 */
	public static function getLanguagePhraseList() {
		global $lang;
		return $lang;
	}
	
	/**
	 * getInstalledLanguagePacks
	 * 
	 */
	public static function getInstalledLanguagePacks() {
		$languagePacks = glob(__PATH_LANGUAGES__ . '*', GLOB_ONLYDIR);
		if(!is_array($languagePacks)) return;
		
		foreach($languagePacks as $languagePack) {
			$paths = explode('/', $languagePack);
			$languageDir = end($paths);
			
			// required language files
			if(!file_exists($languagePack . '/' . self::$languageInfoFile)) continue;
			if(!file_exists($languagePack . '/' . self::$languageFile)) continue;
			
			// language information
			$languageInfo = self::_loadLanguageInfo($languageDir);
			if(!is_array($languageInfo)) continue;
			
			$result[$languageDir] = $languageInfo;
		}
		
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getLocaleList
	 * 
	 */
	public static function getLocaleList() {
		$localeList = loadConfig('locales');
		if(!is_array($localeList)) return;
		return $localeList;
	}
	
	/**
	 * getLocaleTitle
	 * 
	 */
	public static function getLocaleTitle($locale) {
		if(!check($locale)) return;
		
		$localeList = self::getLocaleList();
		if(!is_array($localeList)) return;
		
		if(!array_key_exists($locale, $localeList)) return;
		return $localeList[$locale];
	}
	
	/**
	 * switchLanguageStatus
	 * 
	 */
	public static function switchLanguageStatus($languageDir) {
		// load language info
		$languageInfo = self::_loadLanguageInfo($languageDir);
		if(!is_array($languageInfo)) return;
		
		// new status
		$newStatus = $languageInfo['active'] == true ? false : true;
		
		// check write permissions
		$infoFilePath = __PATH_LANGUAGES__ . $languageDir . '/' . self::$languageInfoFile;
		if(!is_writable($infoFilePath)) return;
		
		// update value
		$languageInfo['active'] = $newStatus;
		
		// save file
		$languageInfoJson = json_encode($languageInfo, JSON_PRETTY_PRINT);
		
		$file = fopen($infoFilePath, 'w');
		if(!$file) return;
		fwrite($file, $languageInfoJson);
		fclose($file);
	}
	
	/**
	 * getLanguageDirectoryByShortName
	 * 
	 */
	public static function getLanguageDirectoryByShortName($shortname) {
		$languagePacks = self::getInstalledLanguagePacks();
		if(!is_array($languagePacks)) return;
		foreach($languagePacks as $key => $row) {
			if($shortname == $row['short_name']) return $key;
		}
		return;
	}
	
	/**
	 * loadDefaultLanguage
	 * 
	 */
	public static function loadDefaultLanguage() {
		global $lang;
		self::loadLanguage(config('language_default'));
	}
	
	/**
	 * loadLanguage
	 * 
	 */
	public static function loadLanguage($dir) {
		global $lang;
		include_once(__PATH_LANGUAGES__ . $dir . '/' . self::$languageFile);
	}
	
	/**
	 * _loadLanguageInfo
	 * 
	 */
	private static function _loadLanguageInfo($languageDir) {
		if(!check($languageDir)) return;
		
		$infoFilePath = __PATH_LANGUAGES__ . $languageDir . '/' . self::$languageInfoFile;
		if(!file_exists($infoFilePath)) return;
		
		$languageFile = file_get_contents($infoFilePath);
		$languageInfo = json_decode($languageFile, true);
		if(!is_array($languageInfo)) return;
		if(!self::_isValidLanguageInfo($languageInfo)) return;
		return $languageInfo;
	}
	
	/**
	 * _isValidLanguageInfo
	 * 
	 */
	private static function _isValidLanguageInfo($languageInfo) {
		if(!is_array($languageInfo)) return;
		if(!is_array(self::$infoFileRequiredElements)) return true;
		
		// required elements
		foreach(self::$infoFileRequiredElements as $requiredKey) {
			if(!array_key_exists($requiredKey, $languageInfo)) return;
		}
		
		// check locale
		if(!self::_isValidLocale($languageInfo['locale'])) return;
		
		return true;
	}
	
	/**
	 * _isValidLocale
	 * 
	 */
	private static function _isValidLocale($locale) {
		if(!check($locale)) return;
		
		$localeList = self::getLocaleList();
		if(!is_array($localeList)) return;
		
		if(!array_key_exists($locale, $localeList)) return;
		return true;
	}
	
}