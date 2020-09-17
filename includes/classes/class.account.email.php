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

class AccountEmail extends Account {
	
	private $_configurationFile = 'account.email';
	private $_changeEmailVerificationEnabled = true;
	private $_changeEmailVerificationTimeLimit = 3600;
	private $_changeEmailVerificationUrl = 'verification/email/type/change/';
	
	private $_newEmail;
	
	function __construct() {
		parent::__construct();
		
		$cfg = loadModuleConfig($this->_configurationFile);
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		
		$this->_changeEmailVerificationEnabled = $cfg['require_verification'] ? true : false;
		$this->_changeEmailVerificationTimeLimit = check($cfg['request_timeout']) ? $cfg['request_timeout'] : 3600;
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
	}
	
	/**
	 * setNewEmail
	 * 
	 */
	public function setNewEmail($email) {
		if(!Validator::AccountEmail($email)) throw new Exception(lang('error_9'));
		$this->_newEmail = $email;
	}
	
	/**
	 * changeEmail
	 * 
	 */
	public function changeEmail() {
		if(!check($this->_userid)) throw new Exception(lang('error_90'));
		if(!check($this->_username)) throw new Exception(lang('error_91'));
		if(!check($this->_newEmail)) throw new Exception(lang('error_4'));
		
		if($this->emailExists($this->_newEmail)) throw new Exception(lang('error_11'));
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12'));
		$this->setEmail($accountData['email']);
		
		if($this->isOnline()) throw new Exception(lang('error_14'));
		
		// email verification
		if($this->_changeEmailVerificationEnabled) {
			if(is_array($this->_getEmailChangeRequestData())) throw new Exception(lang('error_75'));
			if(!$this->_createEmailChangeRequest()) throw new Exception(lang('error_21'));
			if(!$this->_sendEmailChangeVerificationEmail()) throw new Exception(lang('error_20'));
			return;
		}
		
		// update email
		$this->_updateEmail();
	}
	
	/**
	 * verifyEmail
	 * 
	 */
	public function verifyEmail() {
		if(!check($this->_username)) throw new Exception(lang('error_21'));
		if(!check($this->_verificationKey)) throw new Exception(lang('error_27'));
		
		$accountData = $this->getAccountData();
		if(!is_array($accountData)) throw new Exception(lang('error_12'));
		$this->setUserid($accountData['_id']);
		
		// get saved request data
		$requestData = $this->_getEmailChangeRequestData();
		if(!is_array($requestData)) throw new Exception(lang('error_21'));
		$this->setNewEmail($requestData['request_data']);
		
		// check key
		if($requestData['request_key'] != $this->_verificationKey) {
			throw new Exception(lang('error_27'));
		}
		
		// check date
		if(time() > (strtotime($requestData['request_date'])+$this->_changeEmailVerificationTimeLimit)) {
			$this->_deleteEmailChangeRequest();
			throw new Exception(lang('error_76'));
		}
		
		// update password
		$this->_updateEmail();
		$this->_deleteEmailChangeRequest();
	}
	
	/**
	 * disableEmailVerification
	 * 
	 */
	public function disableEmailVerification() {
		$this->_changeEmailVerificationEnabled = false;
	}
	
	/**
	 * manualEmailChange
	 * manually changes the email of an account
	 */
	public function manualEmailChange() {
		if(!check($this->_userid)) throw new Exception(lang('error_21'));
		if(!check($this->_newEmail)) throw new Exception(lang('error_4'));
		if($this->emailExists($this->_newEmail)) throw new Exception(lang('error_11'));
		$this->_updateEmail();
	}
	
	/**
	 * _createEmailChangeRequest
	 * 
	 */
	private function _createEmailChangeRequest() {
		if(!check($this->_userid)) return;
		if(!check($this->_newEmail)) return;
		
		$this->_verificationKey = $this->_generateVerificationKey();
		
		$data = array(
			'userid' => $this->_userid,
			'type' => 'email',
			'newemail' => $this->_newEmail,
			'key' => $this->_verificationKey
		);
		
		$query = "INSERT INTO "._WE_CHANGEREQ_." (request_userid,request_type,request_data,request_key,request_date) VALUES (:userid, :type, :newemail, :key, CURRENT_TIMESTAMP)";
		
		$result = $this->we->query($query, $data);
		if(!$result) return;
		
		return true;
	}
	
	/**
	 * _sendEmailChangeVerificationEmail
	 * 
	 */
	private function _sendEmailChangeVerificationEmail() {
		if(!check($this->_username)) return;
		if(!check($this->_newEmail)) return;
		if(!check($this->_email)) return;
		try {
			$expirationTime = sec_to_hms($this->_changeEmailVerificationTimeLimit);
			
			$email = new Email();
			$email->setTemplate('CHANGE_EMAIL_VERIFICATION');
			$email->addVariable('{USERNAME}', $this->_username);
			$email->addVariable('{DATE}', date("Y-m-d H:i A"));
			$email->addVariable('{IP_ADDRESS}', $_SERVER['REMOTE_ADDR']);
			$email->addVariable('{LINK}', $this->_buildEmailVerificationLink());
			$email->addVariable('{EXPIRATION_TIME}', $expirationTime[0]);
			$email->addVariable('{NEW_EMAIL}', $this->_newEmail);
			$email->addAddress($this->_email);
			$email->send();
			
			return true;
		} catch (Exception $ex) {
			# TODO logs system
			return;
		}
	}
	
	/**
	 * _buildEmailVerificationLink
	 * builds the email verification link url
	 */
	private function _buildEmailVerificationLink() {
		if(!check($this->_username)) return;
		if(!check($this->_verificationKey)) return;
		$verificationLink = __BASE_URL__ . $this->_changeEmailVerificationUrl;
		$verificationLink .= 'user/';
		$verificationLink .= $this->_username;
		$verificationLink .= '/key/';
		$verificationLink .= $this->_verificationKey;
		return $verificationLink;
	}
	
	/**
	 * _getEmailChangeRequestData
	 * returns the email request data
	 */
	private function _getEmailChangeRequestData() {
		if(!check($this->_userid)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_CHANGEREQ_." WHERE request_type = ? AND request_userid = ?", array('email', $this->_userid));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _deleteEmailChangeRequest
	 * deletes the email request from the database
	 */
	private function _deleteEmailChangeRequest() {
		if(!check($this->_userid)) return;
		$result = $this->we->query("DELETE FROM "._WE_CHANGEREQ_." WHERE request_type = ? AND request_userid = ?", array('email', $this->_userid));
		if(!$result) return;
		return true;
	}
	
	/**
	 * _updateEmail
	 * 
	 */
	private function _updateEmail() {
		if(!check($this->_userid)) return;
		if(!check($this->_newEmail)) return;
		
		try {
			$collection = $this->db->loginserver->accounts;
			$updateResult = $collection->updateOne(
				['_id' => (int) $this->_userid],
				['$set' => ['email' => $this->_newEmail]]
			);
			
			if($updateResult->getMatchedCount() == 0) return;
			if($updateResult->getModifiedCount() == 0) return;
			
			return true;
		} catch(Exception $ex) {
			return;
		}
	}
	
}