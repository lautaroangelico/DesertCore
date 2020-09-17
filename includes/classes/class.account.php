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

class Account {
	
	protected $_userid;
	protected $_username;
	protected $_password;
	protected $_newPassword;
	protected $_email;
	protected $_serial = '111111111111';
	protected $_accessLevel;
	
	protected $_accountData;
	protected $_gameserverAccountData;
	protected $_verificationKey;
	
	protected $_facebookId;
	protected $_facebookName;
	protected $_googleId;
	protected $_googleName;
	
	protected $_accessLevelMin = 0;
	protected $_accessLevelMax = 4;
	
	protected $_limit = 100;
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->db = Handler::loadDB('BDO');
	}
	
	/**
	 * setVerificationKey
	 * sets the verification key value
	 */
	public function setVerificationKey($value) {
		if(!check($value)) throw new Exception(lang('error_27'));
		if(!Validator::Number($value)) throw new Exception(lang('error_27'));
		if(!Validator::UnsignedNumber($value)) throw new Exception(lang('error_27'));
		
		$this->_verificationKey = $value;
	}
	
	/**
	 * setUserid
	 * sets the user id
	 */
	public function setUserid($value) {
		if(!check($value)) throw new Exception(lang('error_90'));
		if(!Validator::AccountId($value)) throw new Exception(lang('error_90'));
		
		$this->_userid = $value;
	}
	
	/**
	 * setUsername
	 * sets the username
	 */
	public function setUsername($value) {
		if(!check($value)) throw new Exception(lang('error_91'));
		if(!Validator::AccountUsername($value)) throw new Exception(lang('error_91'));
		
		$this->_username = $value;
	}
	
	/**
	 * setPassword
	 * sets the password
	 */
	public function setPassword($value) {
		if(!check($value)) throw new Exception(lang('error_1'));
		if(!Validator::AccountPassword($value)) throw new Exception(lang('error_1'));
		
		$this->_password = $value;
	}
	
	/**
	 * setNewPassword
	 * sets the new password
	 */
	public function setNewPassword($value) {
		if(!check($value)) throw new Exception(lang('error_92'));
		if(!Validator::AccountPassword($value)) throw new Exception(lang('error_92'));
		
		$this->_newPassword = $value;
	}
	
	/**
	 * setEmail
	 * sets the email
	 */
	public function setEmail($value) {
		if(!check($value)) throw new Exception(lang('error_9'));
		if(!Validator::AccountEmail($value)) throw new Exception(lang('error_9'));
		
		$this->_email = $value;
	}
	
	/**
	 * setFacebookId
	 * sets the facebook user identifier
	 */
	public function setFacebookId($id) {
		if(!Validator::FacebookId($id)) throw new Exception(lang('error_81'));
		$this->_facebookId = $id;
	}
	
	/**
	 * setGoogleId
	 * sets the google user identifier
	 */
	public function setGoogleId($id) {
		if(!Validator::GoogleId($id)) throw new Exception(lang('error_82'));
		$this->_googleId = $id;
	}
	
	/**
	 * setFacebookName
	 * sets the facebook name
	 */
	public function setFacebookName($name) {
		if(!Validator::FacebookName($name)) throw new Exception(lang('error_81'));
		$this->_facebookName = $name;
	}
	
	/**
	 * setGoogleName
	 * sets the google name
	 */
	public function setGoogleName($name) {
		if(!Validator::GoogleName($name)) throw new Exception(lang('error_82'));
		$this->_googleName = $name;
	}
	
	/**
	 * setAccessLevel
	 * sets the account access level
	 */
	public function setAccessLevel($level) {
		if(!Validator::Number($level, $this->_accessLevelMax, $this->_accessLevelMin)) throw new Exception(lang('error_59'));
		$this->_accessLevel = $level;
	}
	
	/**
	 * setLimit
	 * 
	 */
	public function setLimit($value) {
		$this->_limit = $value;
	}
	
	/**
	 * usernameExists
	 * checks if the username is in use
	 */
	public function usernameExists() {
		if(!check($this->_username)) return;
		
		$collection = $this->db->loginserver->accounts;
		$result = $collection->findOne(
			[
				'accountName' => $this->_username,
			]
		);
		if(!check($result->accountName)) return;
		return true;
	}
	
	/**
	 * emailExists
	 * checks if the email address is in use
	 */
	public function emailExists($email='') {
		$checkEmail = check($email) ? $email : $this->_email;
		if(!check($checkEmail)) return;
		
		$collection = $this->db->loginserver->accounts;
		$result = $collection->findOne(
			[
				'email' => $checkEmail,
			]
		);
		if(!check($result->email)) return;
		return true;
	}
	
	/**
	 * getAccountData
	 * returns the account data
	 */
	public function getAccountData() {
		$this->_loadAccountData();
		return $this->_accountData;
	}
	
	/**
	 * getGameserverAccountData
	 * returns the account data (from gameserver database)
	 */
	public function getGameserverAccountData() {
		$this->_loadGameserverAccountData();
		return $this->_gameserverAccountData;
	}
	
	/**
	 * blockAccount
	 * bans an account depending on the identificator set
	 */
	public function blockAccount() {
		if(check($this->_userid)) {
			
		}
		
		if(check($this->_username)) {
			
		}
		
		if(check($this->_email)) {
			
		}
		
		return;
	}
	
	/**
	 * unblockAccount
	 * unbans an account depending on the identificator set
	 */
	public function unblockAccount() {
		if(check($this->_userid)) {
			
		}
		
		if(check($this->_username)) {
			
		}
		
		if(check($this->_email)) {
			
		}
		
		return;
	}
	
	/**
	 * isOnline
	 * checks if the account is online
	 */
	public function isOnline() {
		
		return;
	}
	
	/**
	 * getFullAccountList
	 * returns a list of all accounts on the database
	 */
	public function getFullAccountList() {
		
		$collection = $this->db->loginserver->accounts;
		$result = $collection->find(
			[],
			[
				'sort' => ['_id' => -1],
			]
		);
		
		foreach($result as $accountData) {
			$accountList[] = [
				'_id' => $accountData->_id,
				'accountName' => $accountData->accountName,
				'email' => $accountData->email
			];
		}
		
		if(!is_array($accountList)) return;
		return $accountList;
	}
	
	/**
	 * getOnlineAccountList
	 * returns a list of all accounts connected to the game
	 */
	public function getOnlineAccountList() {
		
		return;
	}
	
	/**
	 * getBannedAccountsList
	 * returns a list of all banned accounts
	 */
	public function getBannedAccountsList() {
		
		return;
	}
	
	/**
	 * getTotalAccountCount
	 * 
	 */
	public function getTotalAccountCount() {
		$result = $this->db->loginserver->accounts->count();
		if(!check($result)) return 0;
		return $result;
	}
	
	/**
	 * getTotalOnlineAccountCount
	 * 
	 */
	public function getTotalOnlineAccountCount() {
		
		return 0;
	}
	
	/**
	 * addCash
	 * 
	 */
	public function addCash($amount) {
		if(!check($this->_userid)) return;
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) return;
		
		$currentCash = $accountData['cash'];
		$newCash = $currentCash + abs($amount);
		
		try {
			$collection = $this->db->loginserver->accounts;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_userid],
				['$set' => ['cash' => (int) $newCash]]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
		} catch(Exception $ex) {
			return;
		}
	}
	
	/**
	 * subtractCash
	 * 
	 */
	public function subtractCash($amount) {
		if(!check($this->_userid)) return;
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) return;
		
		$currentCash = $accountData['cash'];
		$newCash = $currentCash - abs($amount);
		if($newCash < 1) return;
		
		try {
			$collection = $this->db->loginserver->accounts;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_userid],
				['$set' => ['cash' => (int) $newCash]]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
		} catch(Exception $ex) {
			return;
		}
	}
	
	/**
	 * recoverUsername
	 * username recovery process
	 */
	public function recoverUsername() {
		if(!check($this->_email)) throw new Exception(lang('error_4',true));
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12',true));
		$this->setUsername($accountData['accountName']);
		
		if(!$this->_sendUsernameRecoveryEmail()) throw new Exception(lang('error_25',true));
	}
	
	/**
	 * updateAccessLevel
	 * updates the access level of an account
	 */
	public function updateAccessLevel() {
		if(!check($this->_userid)) throw new Exception(lang('error_4',true));
		if(!check($this->_accessLevel)) throw new Exception(lang('error_4',true));
		
		try {
			$collection = $this->db->loginserver->accounts;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_userid],
				['$set' => ['accessLvl' => (int) $this->_accessLevel]]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
		} catch(Exception $ex) {
			return;
		}
	}
	
	/**
	 * getAccountsByAccessLevel
	 * returns a list of accounts by the given access level
	 */
	public function getAccountsByAccessLevel($accessLevel=4) {
		$result = $this->_getAccountsByAccessLevel($accessLevel);
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * getTopAccountCash
	 * 
	 */
	public function getTopAccountCash() {
		try {
			$collection = $this->db->loginserver->accounts;
			$result = $collection->find(
				[
					'cash' => ['$gte' => 1],
				],
				[
					'projection' => [
						'accountName' => 1,
						'cash' => 1,
					],
					'limit' => (int) $this->_limit,
					'sort' => ['cash' => -1],
				]
			);
			
			if(!check($result)) return;
			foreach($result as $account) {
				$accountList[] = (array) $account;
			}
			
			return $accountList;
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * getAccountIpList
	 * 
	 */
	public function getAccountIpList() {
		if(!check($this->_userid)) return;
		try {
			$collection = $this->db->loginserver->logs;
			$result = $collection->distinct(
				'ip',
				[
					'accountId' => (int) $this->_userid
				],
				[
					'projection' => [
						'date' => 1,
						'ip' => 1
					]
				]
			);
			
			if(!check($result)) return;
			foreach($result as $ipData) {
				$ipList[] = (array) $ipData;
			}
			
			return $ipList;
			
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * _loadAccountData
	 * loads the account data depending on the identificator set
	 */
	protected function _loadAccountData() {
		try {
			$collection = $this->db->loginserver->accounts;
			
			if(check($this->_userid)) {
				$result = $collection->findOne(
					[
						'_id' => (int) $this->_userid,
					]
				);
				
				if(!check($result)) return;
				$accountData = (array) $result;
				if(!is_array($accountData)) return;
				$this->_accountData = $accountData;
				return;
			}
			
			if(check($this->_username)) {
				$result = $collection->findOne(
					[
						'accountName' => $this->_username,
					]
				);
				
				if(!check($result)) return;
				$accountData = (array) $result;
				if(!is_array($accountData)) return;
				$this->_accountData = $accountData;
				return;
			}
			
			if(check($this->_email)) {
				$result = $collection->findOne(
					[
						'email' => $this->_email,
					]
				);
				
				if(!check($result)) return;
				$accountData = (array) $result;
				if(!is_array($accountData)) return;
				$this->_accountData = $accountData;
				return;
			}
			
			return;
		} catch(Exception $ex) {
			if(config('debug')) throw new Exception($ex->getMessage());
			return;
		}
	}
	
	/**
	 * _createAccount
	 * creates a new account in the database
	 */
	protected function _createAccount() {
		if(!check($this->_username)) return;
		if(!check($this->_password)) return;
		if(!check($this->_email)) return;
		
		$lastAccountId = $this->_getLastAccountId();
		if(!$lastAccountId) return;
		
		$accountId = $lastAccountId+1;
		
		$passwordHash = $this->_encryptPassword($this->_password);
		if(!check($passwordHash)) return;
		
		$registrationDate = round(microtime(true) * 1000);
		
		$newAccountData = array(
			'_id' => $accountId,
			'accountName' => $this->_username,
			'email' => $this->_email,
			'password' => $passwordHash,
			'pin' => '000001',
			'family' => '',
			'accessLvl' => 0,
			'characterSlots' => 0,
			'cash' => 0,
			'confirmationHash' => '',
			'changePasswordHash' => '',
			'registrationDate' => $registrationDate,
		);
		
		try {
			$this->db->loginserver->accounts->insertOne($newAccountData);
			return true;
		} catch(Exception $ex) {
			return false;
		}
		
	}
	
	/**
	 * _validateAccount
	 * checks if the username and password are correct
	 */
	protected function _validateAccount() {
		if(!check($this->_username)) return;
		if(!check($this->_password)) return;
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) return;
		
		if(password_verify($this->_password, $accountData['password'])) return true;
		return;
	}
	
	/**
	 * _generateVerificationKey
	 * generates a 6-digit random number
	 */
	protected function _generateVerificationKey() {
		return mt_rand(111111,999999);
	}
	
	/**
	 * _updatePassword
	 * changes the account password
	 */
	protected function _updatePassword() {
		if(!check($this->_userid)) return;
		if(!check($this->_newPassword)) return;
		
		$passwordHash = $this->_encryptPassword($this->_newPassword);
		
		try {
			$collection = $this->db->loginserver->accounts;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_userid],
				['$set' => ['password' => $passwordHash]]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
		} catch(Exception $ex) {
			return;
		}
	}
	
	/**
	 * _sendUsernameRecoveryEmail
	 * sends the account username via email address
	 */
	protected function _sendUsernameRecoveryEmail() {
		if(!check($this->_username)) return;
		if(!check($this->_email)) return;
		
		try {
			$email = new Email();
			$email->setTemplate('USERNAME_RECOVERY');
			$email->addVariable('{USERNAME}', $this->_username);
			$email->addAddress($this->_email);
			$email->send();
			return true;
		} catch (Exception $ex) {
			# TODO logs system
			return;
		}
	}
	
	/**
	 * _getLastAccountId
	 * 
	 */
	protected function _getLastAccountId() {
		$collection = $this->db->loginserver->accounts;
		$result = $collection->findOne(
			[],
			[
				'sort' => ['_id' => -1],
			]
		);
		if(!check($result)) return 1;
		if(!check($result->_id)) return 1;
		if(!is_numeric($result->_id)) return;
		return $result->_id;
	}
	
	/**
	 * _encryptPassword
	 * 
	 */
	protected function _encryptPassword($password) {
		if(!check($password)) return;
		$result = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10));
		$result = str_replace('$2y$10', '$2a$10', $result);
		return $result;
	}
	
	/**
	 * _getAccountsByAccessLevel
	 * 
	 */
	protected function _getAccountsByAccessLevel($accessLevel=4) {
		$accessLevelList = custom('accessLevel');
		if(!array_key_exists($accessLevel, $accessLevelList)) return;
		
		try {
			$collection = $this->db->loginserver->accounts;
			$result = $collection->find(
				[
					'accessLvl' => (int) $accessLevel
				],
				[
					'projection' => [
						'_id' => 1,
						'accountName' => 1,
						'email' => 1
					]
				]
			);
			
			if(!check($result)) return;
			foreach($result as $account) {
				$accountList[] = (array) $account;
			}
			
			return $accountList;
			
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				return;
			}
		}
	}
	
	/**
	 * _loadGameserverAccountData
	 * loads the account data depending on the identificator set (gameserver database)
	 */
	protected function _loadGameserverAccountData() {
		try {
			
			if(check($this->_username)) {
				$accountData = $this->getAccountData();
				if(!is_array($accountData)) return;
				$this->_userid = $accountData['_id'];
			}
			
			if(check($this->_email)) {
				$accountData = $this->getAccountData();
				if(!is_array($accountData)) return;
				$this->_userid = $accountData['_id'];
			}
			
			$collection = $this->db->gameserver->accounts;
			
			if(check($this->_userid)) {
				$result = $collection->findOne(
					[
						'_id' => (int) $this->_userid,
					],
					[
						'projection' => [
							'_id' => 1,
							'lastLogout' => 1,
							'lastLogin' => 1,
							'firstLogin' => 1,
							'playedTime' => 1,
							'familyRewardCoolTime' => 1,
							'guildCoolTime' => 1,
							'matingAuctionCoolTime' => 1,
							'activityPoints' => 1
						]
					]
				);
				
				if(!check($result)) return;
				$gameserverAccountData = (array) $result;
				if(!is_array($gameserverAccountData)) return;
				$this->_gameserverAccountData = $gameserverAccountData;
				return;
			}
			
			return;
		} catch(Exception $ex) {
			if(config('debug')) throw new Exception($ex->getMessage());
			return;
		}
	}
	
}