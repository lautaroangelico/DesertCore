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

class sessionControl {
	
	private static $_adminAccessLvl = 4;
	
	private static $we;
	private static $_ignoreLastLocation = array(
		'faviconico/',
		'favicon.ico/',
	);
	
	/**
	 * beginSession
	 * initiates the php session
	 */
	public static function beginSession() {
		//session_name('DesertCore120');
		//session_set_cookie_params(0, '/', 'desertcore.com');
		session_start();
		@ob_start();
	}
	
	/**
	 * initSessionControl
	 * initiates the session control system
	 * 
	 * @param string $type
	 */
	public static function initSessionControl($type='') {
		self::$we = Handler::loadDB('WebEngine');
		
		if(config('ip_block_system_enable')) {
			if(self::_checkBlockedIp()) {
				die();
			}
		}
		
		switch($type) {
			case 'user':
				$sessionData = self::_sessionInfo(session_id(), 'sessionid');
				if(!is_array($sessionData)) self::logout();
				
				if($sessionData['username'] != $_SESSION['username']) self::logout();
				if($sessionData['ip_address'] != Handler::userIP()) self::logout();
				
				self::_isSessionIDLE(databaseTime($sessionData['last_activity']));
				self::_updateSession('user');
				break;
			default:
				if($_SESSION['guest']) {
					$sessionData = self::_sessionInfo(session_id(), 'sessionid');
					if(!is_array($sessionData)) {
						self::_newGuestSession();
					} else {
						self::_updateSession();
					}
				} else {
					self::_newGuestSession();
				}
		}
	}
	
	/**
	 * newSession
	 * deletes the guest session data, regenerates the session id and creates a new user session
	 * 
	 * @param int $userid
	 * @param string $username
	 */
	public static function newSession($userid, $username, $accessLvl=0, $defaultLanguage=null) {
		self::_deleteSession(session_id());
		$_SESSION['guest'] = false;
		$_SESSION['failed_logins'] = 0;
		
		session_regenerate_id();
		$_SESSION['valid'] = true;
		$_SESSION['userid'] = $userid;
		$_SESSION['username'] = $username;
		$_SESSION['admin'] = $accessLvl == self::$_adminAccessLvl ? true : false;
		$_SESSION['accessLvl'] = $accessLvl;
		if(check($defaultLanguage)) $_SESSION['default_language'] = $defaultLanguage;
		
		self::_deleteMultipleSessions($username);
		$data = array($username, session_id(), $_SESSION['last_location'], Handler::userIP());
		
		try {
			self::$we->query("INSERT INTO `"._WE_SESSION_."` (`username`, `session_id`, `last_location`, `ip_address`, `last_activity`) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)", $data);
		} catch(Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * isLoggedIn
	 * checks if user session data exists
	 * 
	 * @return boolean
	 */
	public static function isLoggedIn() {
		if(check($_SESSION['valid'], $_SESSION['userid'], $_SESSION['username'])) {
			return true;
		}
		return false;
	}
	
	/**
	 * logout
	 * deletes the session data from the database and destroys the session data
	 */
	public static function logout() {
		self::_destroySessionData();
		redirect();
	}
	
	/**
	 * lastUserLocation
	 * updates the last user location
	 * 
	 * @param string $location
	 */
	public static function lastUserLocation($location) {
		$_SESSION['last_location'] = (check($location) ? $location : "/");
	}
	
	/**
	 * deleteInactiveSessions
	 * purges inactive sessions from the database
	 */
	public static function deleteInactiveSessions() {
		self::$we = Handler::loadDB('WebEngine');
		$loginCfg = loadModuleConfig('login');
		if($loginCfg['enable_session_timeout'] == 0) return;
		$loginSessionTimeout = check($loginCfg['session_timeout']) ? $loginCfg['session_timeout'] : 300;
		$sessionTimeout = '-'.$loginSessionTimeout.' seconds';
		$result = self::$we->query("DELETE FROM `"._WE_SESSION_."` WHERE `last_activity` < datetime('now', ?)", array($sessionTimeout));
		if(!$result) return;
		return true;
	}
	
	/**
	 * blockIp
	 * adds an ip to the blocked ip list
	 */
	public static function blockIp($ip) {
		if(!Validator::Ip($ip)) throw new Exception(lang('error_219'));
		if(self::_isIpBlocked($ip)) throw new Exception(lang('error_220'));
		
		$result = self::$we->query("INSERT INTO "._WE_IPBLOCK_." (`ip_address`, `blocked_by`, `blocked_date`) VALUES (?, ?, CURRENT_TIMESTAMP)", array($ip, $_SESSION['username']));
		if(!$result) throw new Exception(lang('error_221'));
	}
	
	/**
	 * unblockIp
	 * removes an ip from the blocked ip list
	 */
	public static function unblockIp($ip) {
		if(!Validator::Ip($ip)) throw new Exception(lang('error_219'));
		if(!self::_isIpBlocked($ip)) throw new Exception(lang('error_222'));
		
		$result = self::$we->query("DELETE FROM "._WE_IPBLOCK_." WHERE `ip_address` = ?", array($ip));
		if(!$result) throw new Exception(lang('error_223'));
	}
	
	/**
	 * getBlockedIpList
	 * returns a list of blocked ip addresses
	 */
	public static function getBlockedIpList() {
		$result = self::$we->queryFetch("SELECT * FROM "._WE_IPBLOCK_." ORDER BY `blocked_date` DESC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _sessionInfo
	 * returns the session information from the database
	 * 
	 * @param string $identifier
	 * @param string $data
	 * @return array
	 */
	private static function _sessionInfo($data, $identifier='') {
		if(!check($data)) return;
		switch($identifier) {
			case 'sessionid':
				$query = "SELECT * FROM `"._WE_SESSION_."` WHERE `session_id` = ?";
				break;
			default:
				$query = "SELECT * FROM `"._WE_SESSION_."` WHERE `username` = ?";
		}
		try {
			$result = self::$we->queryFetchSingle($query, array($data));
			if(!is_array($result)) return;
			return $result;
		} catch (Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * _newGuestSession
	 * creates a new guest session in the database
	 * 
	 * @return boolean
	 */
	private static function _newGuestSession() {
		$_SESSION['guest'] = true;
		try {
			$data = array(session_id(), $_SESSION['last_location'], Handler::userIP());
			self::$we->query("INSERT INTO `"._WE_SESSION_."` (`session_id`, `last_location`, `ip_address`, `last_activity`) VALUES (?, ?, ?, CURRENT_TIMESTAMP)", $data);
			return true;
		} catch(Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * _updateSession
	 * updates the session information in the database
	 * 
	 * @param string $type
	 * @return boolean
	 */
	private static function _updateSession($type='') {
		if(in_array($_SESSION['last_location'], self::$_ignoreLastLocation)) return;
		switch($type) {
			case "user":
				$data = array($_SESSION['last_location'], session_id());
				$query = "UPDATE `"._WE_SESSION_."` SET `last_location` = ?, `last_activity` = CURRENT_TIMESTAMP WHERE `session_id` = ?";
				break;
			default:
				$data = array($_SESSION['last_location'], Handler::userIP(), session_id());
				$query = "UPDATE `"._WE_SESSION_."` SET `last_location` = ?, `ip_address` = ?, `last_activity` = CURRENT_TIMESTAMP WHERE `session_id` = ?";
		}
		try {
			self::$we->query($query, $data);
			return true;
		} catch (Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * _deleteSession
	 * deletes the session information from the database
	 * 
	 * @param string $sessionid
	 * @return boolean
	 */
	private static function _deleteSession($sessionid) {
		if(!check($sessionid)) return;
		try {
			self::$we->query("DELETE FROM `"._WE_SESSION_."` WHERE `session_id` = ?", array($sessionid));
			return true;
		} catch (Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * _deleteMultipleSessions
	 * deletes the session information of a specific user from the database
	 * 
	 * @param int $username
	 * @return boolean
	 */
	private static function _deleteMultipleSessions($username) {
		if(!check($username)) return;
		try {
			self::$we->query("DELETE FROM `"._WE_SESSION_."` WHERE `username` = ?", array($username));
			return true;
		} catch (Exception $ex) {
			// Log system: session control error log
		}
	}
	
	/**
	 * _isSessionIDLE
	 * checks if a session is idle for over 5 minutes and logouts the user
	 * 
	 * @param datetime $last_action
	 */
	private static function _isSessionIDLE($last_action) {
		$loginCfg = loadModuleConfig('login');
		if($loginCfg['enable_session_timeout'] == 0) return;
		$loginSessionTimeout = check($loginCfg['session_timeout']) ? $loginCfg['session_timeout'] : 300;
		
		$lastAction = strtotime($last_action);
		$idleTime = time() - $lastAction;
		if($idleTime >= $loginSessionTimeout) {
			self::_destroySessionData();
		}
	}
	
	/**
	 * _checkBlockedIp
	 * checks if the user's session ip is blocked
	 */
	private static function _checkBlockedIp() {
		return self::_isIpBlocked(Handler::userIP());
	}
	
	/**
	 * _isIpBlocked
	 * checks if the ip is blocked from accessing the website
	 */
	private static function _isIpBlocked($ip) {
		if(!Validator::Ip($ip)) return;
		$result = self::$we->queryFetchSingle("SELECT * FROM "._WE_IPBLOCK_." WHERE `ip_address` = ?", array($ip));
		if(!is_array($result)) return;
		return true;
	}
	
	/**
	 * _destroySessionData
	 * destroys all session data
	 */
	private static function _destroySessionData() {
		self::_deleteSession(session_id());
		@session_destroy();
		@session_unset();
	}
	
}