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

final class RedeemCode {
	
	private $_configurationFile = 'shop.redeem';
	
	private $_titleMaxLen = 50;
	private $_codeMaxLen = 50;
	private $_codeTypes = array(
		'regular',
		'limited',
		'account'
	);
	
	private $_title;
	private $_code;
	private $_codeType;
	private $_limit = null;
	private $_expiration = null;
	private $_user = null;
	private $_reward = 0;
	private $_sendEmailNotification = true;
	
	// codes:
	// 		regular: can be used by everyone, once
	//		limited: can be used N amount of times
	//		account: can be used by one account, once
	
	// CONSTRUCTOR
	
	function __construct() {
		
		// load database
		$this->db = Handler::loadDB('WebEngine');
		
		// configs
		$cfg = loadModuleConfig($this->_configurationFile);
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		
		$this->_sendEmailNotification = $cfg['email_notification'];
	}
	
	// PUBLIC FUNCTIONS
	
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) return;
		$this->_id = $id;
	}
	
	public function setTitle($title) {
		if(strlen($title) > $this->_titleMaxLen) throw new Exception(lang('error_256'));
		$this->_title = $title;
	}
	
	public function setCode($code) {
		if(strlen($code) > $this->_codeMaxLen) throw new Exception(lang('error_251'));
		$this->_code = $code;
	}
	
	public function setCodeType($type) {
		if(!in_array(strtolower($type), $this->_codeTypes)) throw new Exception(lang('error_252'));
		$this->_codeType = strtolower($type);
	}
	
	public function setLimit($limit) {
		if(!Validator::UnsignedNumber($limit)) return;
		$this->_limit = $limit;
	}
	
	public function setExpiration($date) {
		$this->_expiration = $date;
	}
	
	public function setUser($user) {
		$this->_user = $user;
	}
	
	public function setReward($reward) {
		if(!Validator::UnsignedNumber($reward)) return;
		$this->_reward = $reward;
	}
	
	public function addRewardCode() {
		if(!check($this->_title)) throw new Exception(lang('error_253'));
		if(!check($this->_code)) throw new Exception(lang('error_253'));
		if(!check($this->_codeType)) throw new Exception(lang('error_253'));
		
		if($this->_codeType == 'limited') {
			if(!check($this->_limit)) throw new Exception(lang('error_253'));
		} else {
			$this->_limit = null;
		}
		
		if($this->_codeType == 'account') {
			if(!check($this->_user)) throw new Exception(lang('error_253'));
		} else {
			$this->_user = null;
		}
		
		if(!check($this->_reward)) throw new Exception(lang('error_253'));
		
		if($this->_codeExists($this->_code)) throw new Exception(lang('error_254'));
		
		$data = array(
			$this->_title,
			$this->_code,
			$this->_codeType,
			$this->_limit,
			$this->_expiration,
			$this->_user,
			$this->_reward
		);
		
		$result = $this->db->query("INSERT INTO "._DC_REDEEMCODES_." (`redeem_title`, `redeem_code`, `redeem_type`, `redeem_limit`, `redeem_expiration`, `redeem_account`, `redeem_cash`) VALUES (?, ?, ?, ?, ?, ?, ?)", $data);
		if(!$result) throw new Exception(lang('error_255'));
	}
	
	public function redeemCode() {
		if(!check($this->_code)) throw new Exception(lang('error_251'));
		
		// code data
		$redeemCodeData = $this->_getRedeemCodeData();
		if(!is_array($redeemCodeData)) throw new Exception(lang('error_251'));
		
		// code status
		if($redeemCodeData['active'] != 1) throw new Exception(lang('error_257'));
		
		// check expiration
		if(check($redeemCodeData['redeem_expiration'])) {
			if(time() > strtotime($redeemCodeData['redeem_expiration'])) throw new Exception(lang('error_257'));
		}
		
		// account data
		$Account = new Account();
		$Account->setUsername($_SESSION['username']);
		$accountData = $Account->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12'));
		$Account->setUserid($accountData['_id']);
		$accountUsername = $accountData['accountName'];
		
		// code type
		switch($redeemCodeData['redeem_type']) {
			case 'regular':
				
				// check if user has already redeemed
				if($this->_hasUserRedeemedCode($redeemCodeData['id'], $accountUsername)) throw new Exception(lang('error_257'));
				
				// reward user
				$Account->addCash($redeemCodeData['redeem_cash']);
				
				// add log
				$this->_addRedeemLog($redeemCodeData['id'], $redeemCodeData['redeem_title'], $redeemCodeData['redeem_cash'], $accountUsername);
				
				break;
			case 'limited':
			
				// check if user has already redeemed
				if($this->_hasUserRedeemedCode($redeemCodeData['id'], $accountUsername)) throw new Exception(lang('error_257'));
				
				// get redeem count
				$redeemCount = $this->_getCodeRedeemCount($redeemCodeData['id']);
				
				// check redeem limit
				if(!check($redeemCodeData['redeem_limit'])) throw new Exception(lang('error_257'));
				if($redeemCount >= $redeemCodeData['redeem_limit']) {
					$this->_disableRedeemCode($redeemCodeData['id']);
					throw new Exception(lang('error_257'));
				}
				
				// reward user
				$Account->addCash($redeemCodeData['redeem_cash']);
				
				// add log
				$this->_addRedeemLog($redeemCodeData['id'], $redeemCodeData['redeem_title'], $redeemCodeData['redeem_cash'], $accountUsername);
				
				break;
			case 'account':
				
				// check if user has already redeemed
				if($this->_hasUserRedeemedCode($redeemCodeData['id'], $accountUsername)) throw new Exception(lang('error_257'));
				
				// check user identifier
				if($redeemCodeData['redeem_account'] != $accountUsername) throw new Exception(lang('error_258'));
				
				// reward user
				$Account->addCash($redeemCodeData['redeem_cash']);
				
				// add log
				$this->_addRedeemLog($redeemCodeData['id'], $redeemCodeData['redeem_title'], $redeemCodeData['redeem_cash'], $accountUsername);
				
				// disable code
				$this->_disableRedeemCode($redeemCodeData['id']);
				
				break;
			default:
				throw new Exception(lang('error_251'));
		}
		
		if($this->_sendEmailNotification) {
			try {
				if(!check($accountData['email'])) throw new Exception();
				
				$email = new Email();
				$email->setTemplate('REDEEMED_CODE_NOTIFICATION');
				$email->addVariable('{USERNAME}', $accountUsername);
				$email->addVariable('{REDEEM_TITLE}', $redeemCodeData['redeem_title']);
				$email->addVariable('{REDEEM_CASH}', number_format($redeemCodeData['redeem_cash']));
				$email->addAddress($accountData['email']);
				$email->send();
			} catch (Exception $ex) {
				# TODO logs system
			}
		}
		
	}
	
	public function getRedeemCodesList() {
		$result = $this->db->queryFetch("SELECT * FROM "._DC_REDEEMCODES_." ORDER BY `id` DESC");
		if(!is_array($result)) return;
		return $result;
	}
	
	public function disableCode($id) {
		$this->_disableRedeemCode($id);
	}
	
	public function getLogs() {
		if(check($this->_id)) {
			$result = $this->db->queryFetch("SELECT * FROM "._DC_REDEEMLOGS_." WHERE `code_id` = ? ORDER BY `id` DESC", array($this->_id));
		} else {
			$result = $this->db->queryFetch("SELECT * FROM "._DC_REDEEMLOGS_." ORDER BY `id` DESC");
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getUserLogs() {
		if(!check($this->_user)) return;
		
		$result = $this->db->queryFetch("SELECT * FROM "._DC_REDEEMLOGS_." WHERE `account_username` = ? ORDER BY `id` DESC", array($this->_user));
		if(!is_array($result)) return;
		return $result;
	}
	
	// PRIVATE FUNCTIONS
	
	private function _getRedeemCodeData() {
		if(!check($this->_code)) return;
		$result = $this->db->queryFetchSingle("SELECT * FROM "._DC_REDEEMCODES_." WHERE `redeem_code` = ?", array($this->_code));
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _hasUserRedeemedCode($codeId, $accountUsername) {
		$result = $this->db->queryFetchSingle("SELECT * FROM "._DC_REDEEMLOGS_." WHERE `code_id` = ? AND `account_username` = ?", array($codeId, $accountUsername));
		if(!is_array($result)) return;
		return true;
	}
	
	private function _addRedeemLog($codeId, $title, $reward, $accountUsername) {
		$result = $this->db->query("INSERT INTO "._DC_REDEEMLOGS_." (`code_id`, `redeem_title`, `redeem_cash`, `date_redeemed`, `account_username`) VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?)", array($codeId, $title, $reward, $accountUsername));
		if(!$result) return;
		return true;
	}
	
	private function _getCodeRedeemCount($codeId) {
		$result = $this->db->queryFetchSingle("SELECT COUNT(*) as result FROM "._DC_REDEEMLOGS_." WHERE `code_id` = ?", array($codeId));
		if(!is_array($result)) return 0;
		return $result['result'];
	}
	
	private function _disableRedeemCode($codeId) {
		$result = $this->db->query("UPDATE "._DC_REDEEMCODES_." SET `active` = 0 WHERE `id` = ?", array($codeId));
		if(!$result) return;
		return true;
	}
	
	private function _codeExists($code) {
		$result = $this->db->queryFetchSingle("SELECT * FROM "._DC_REDEEMCODES_." WHERE `redeem_code` = ?", array($code));
		if(!is_array($result)) return;
		return true;
	}
	
}