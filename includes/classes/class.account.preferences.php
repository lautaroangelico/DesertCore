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

class AccountPreferences extends Account {
	
	function __construct() {
		parent::__construct();
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
	}
	
	/**
	 * setLanguage
	 * 
	 */
	public function setLanguage($lang) {
		$this->_language = $lang;
	}
	
	/**
	 * getAccountPreferencesFromUsername
	 * 
	 */
	public function getAccountPreferencesFromUsername() {
		if(!check($this->_username)) return;
		
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_ACCPREF_." WHERE `username` = ?", array($this->_username));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getAccountPreferencesFromFacebookId
	 * 
	 */
	public function getAccountPreferencesFromFacebookId() {
		if(!check($this->_facebookId)) return;
		
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_ACCPREF_." WHERE `facebook_id` = ?", array($this->_facebookId));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getAccountPreferencesFromGoogleId
	 * 
	 */
	public function getAccountPreferencesFromGoogleId() {
		if(!check($this->_googleId)) return;
		
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_ACCPREF_." WHERE `google_id` = ?", array($this->_googleId));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * linkFacebook
	 * 
	 */
	public function linkFacebook() {
		if(!check($this->_username)) return;
		if(!check($this->_facebookId)) return;
		if(!check($this->_facebookName)) return;
		
		$checkFacebookId = $this->getAccountPreferencesFromFacebookId();
		if(is_array($checkFacebookId)) return;
		
		$accountPreferences = $this->getAccountPreferencesFromUsername();
		if(!is_array($accountPreferences)) return;
		if(check($accountPreferences['facebook_id'])) return;
		
		$result = $this->we->query("UPDATE "._WE_ACCPREF_." SET `facebook_id` = ?, `facebook_name` = ? WHERE `username` = ?", array($this->_facebookId, $this->_facebookName, $this->_username));
		if(!$result) return;
		return true;
	}
	
	/**
	 * unlinkFacebook
	 * 
	 */
	public function unlinkFacebook() {
		if(!check($this->_username)) return;
		
		$accountPreferences = $this->getAccountPreferencesFromUsername();
		if(!is_array($accountPreferences)) return;
		if(!check($accountPreferences['facebook_id'])) return;
		
		$result = $this->we->query("UPDATE "._WE_ACCPREF_." SET `facebook_id` = NULL, `facebook_name` = NULL WHERE `username` = ?", array($accountPreferences['username']));
		if(!$result) return;
		return true;
	}
	
	/**
	 * linkGoogle
	 * 
	 */
	public function linkGoogle() {
		if(!check($this->_username)) return;
		if(!check($this->_googleId)) return;
		if(!check($this->_googleName)) return;
		
		$checkGoogleId = $this->getAccountPreferencesFromGoogleId();
		if(is_array($checkGoogleId)) return;
		
		$accountPreferences = $this->getAccountPreferencesFromUsername();
		if(!is_array($accountPreferences)) return;
		if(check($accountPreferences['google_id'])) return;
		
		$result = $this->we->query("UPDATE "._WE_ACCPREF_." SET `google_id` = ?, `google_name` = ? WHERE `username` = ?", array($this->_googleId, $this->_googleName, $this->_username));
		if(!$result) return;
		return true;
	}
	
	/**
	 * unlinkGoogle
	 * 
	 */
	public function unlinkGoogle() {
		if(!check($this->_username)) return;
		
		$accountPreferences = $this->getAccountPreferencesFromUsername();
		if(!is_array($accountPreferences)) return;
		if(!check($accountPreferences['google_id'])) return;
		
		$result = $this->we->query("UPDATE "._WE_ACCPREF_." SET `google_id` = NULL, `google_name` = NULL WHERE `username` = ?", array($accountPreferences['username']));
		if(!$result) return;
		return true;
	}
	
	/**
	 * createProfilePreferences
	 * 
	 */
	public function createAccountPreferences() {
		if(!check($this->_username)) return;
		
		$accountPreferences = $this->getAccountPreferencesFromUsername();
		if(is_array($accountPreferences)) return;
		
		$result = $this->we->query("INSERT INTO "._WE_ACCPREF_." (`username`) VALUES (?)", array($this->_username));
		if(!$result) return;
		return true;
	}
	
	/**
	 * setDefaultLanguage
	 * 
	 */
	public function setDefaultLanguage() {
		if(!check($this->_username)) return;
		if(!check($this->_language)) return;
		
		$result = $this->we->query("UPDATE "._WE_ACCPREF_." SET `default_language` = ? WHERE `username` = ?", array($this->_language, $this->_username));
		if(!$result) return;
		return true;
	}
	
}