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

class AccountRegister extends Account {
	
	private $_configurationFile = 'register';
	private $_verificationEnabled = true;
	private $_welcomeEmailEnabled = true;
	private $_verificationUrl = 'verification/email/';
	private $_verificationTimeLimit = 86400;
	private $_createAccountPreferences = true;
	
	function __construct() {
		parent::__construct();
		
		$cfg = loadModuleConfig($this->_configurationFile);
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		
		$this->_verificationEnabled = $cfg['verify_email'] ? true : false;
		$this->_welcomeEmailEnabled = $cfg['send_welcome_email'] ? true : false;
		$this->_verificationTimeLimit = check($cfg['verification_timelimit']) ? $cfg['verification_timelimit'] : 86400;
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
	}
	
	/**
	 * registerAccount
	 * new account registration process
	 */
	public function registerAccount() {
		if(!check($this->_username)) throw new Exception(lang('error_4'));
		if(!check($this->_password)) throw new Exception(lang('error_4'));
		if(!check($this->_email)) throw new Exception(lang('error_4'));
		
		if($this->usernameExists()) throw new Exception(lang('error_10'));
		if($this->emailExists()) throw new Exception(lang('error_11'));
		if($this->_emailExistsInSavedRegistrations()) throw new Exception(lang('error_11'));
		
		// email verification
		if($this->_verificationEnabled) {
			$saveRegistration = $this->_saveRegistration();
			if(!$saveRegistration) throw new Exception(lang('error_22'));
			if(!$this->_sendVerificationEmail()) throw new Exception(lang('error_20'));
			return;
		}
		
		// regular registration
		$createAccount = $this->_createAccount();
		if(!$createAccount) throw new Exception(lang('error_22'));
		
		// account preferences
		if($this->_createAccountPreferences) {
			$AccountPreferences = new AccountPreferences();
			$AccountPreferences->setUsername($this->_username);
			$AccountPreferences->createAccountPreferences();
		}
		
		// welcome email
		if($this->_welcomeEmailEnabled) $this->_sendWelcomeEmail();
	}
	
	/**
	 * verifyEmail
	 * verifies a saved registration and creates the account
	 */
	public function verifyEmail() {
		if(!check($this->_username)) throw new Exception(lang('error_24'));
		if(!check($this->_verificationKey)) throw new Exception(lang('error_27'));
		
		// get saved registration data
		$registrationData = $this->_getVerificationAccountData();
		if(!is_array($registrationData)) throw new Exception(lang('error_21'));
		
		// check key
		if($registrationData['registration_key'] != $this->_verificationKey) {
			throw new Exception(lang('error_27'));
		}
		
		// check date
		if(time() > (strtotime($registrationData['registration_date'])+$this->_verificationTimeLimit)) {
			$this->_deleteSavedRegistration();
			throw new Exception(lang('error_114'));
		}
		
		// create account
		$this->setPassword($registrationData['registration_password']);
		$this->setEmail($registrationData['registration_email']);
		
		if($this->usernameExists()) throw new Exception(lang('error_10'));
		if($this->emailExists()) throw new Exception(lang('error_11'));
		
		$createAccount = $this->_createAccount();
		if(!$createAccount) throw new Exception(lang('error_22'));
		
		// delete saved registration data
		$this->_deleteSavedRegistration();
		
		// account preferences
		if($this->_createAccountPreferences) {
			$AccountPreferences = new AccountPreferences();
			$AccountPreferences->setUsername($this->_username);
			$AccountPreferences->createAccountPreferences();
		}
		
		// welcome email
		if($this->_welcomeEmailEnabled) $this->_sendWelcomeEmail();
	}
	
	/**
	 * disableVerification
	 * manually disables email verification
	 */
	public function disableVerification() {
		$this->_verificationEnabled = false;
	}
	
	/**
	 * disableWelcomeEmail
	 * manually disables welcome email
	 */
	public function disableWelcomeEmail() {
		$this->_welcomeEmailEnabled = false;
	}
	
	/**
	 * getUnverifiedAccountsList
	 * returns a list of all unverified accounts
	 */
	public function getUnverifiedAccountsList() {
		$result = $this->we->queryFetch("SELECT * FROM "._WE_REGISTER_." ORDER BY registration_date DESC");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * verifySavedRegistration
	 * manually verifies a saved registration
	 */
	public function verifySavedRegistration() {
		if(!check($this->_username)) throw new Exception(lang('error_21'));
		
		// get saved registration data
		$registrationData = $this->_getVerificationAccountData();
		if(!is_array($registrationData)) throw new Exception(lang('error_12'));
		
		// create account
		$this->setPassword($registrationData['registration_password']);
		$this->setEmail($registrationData['registration_email']);
		
		if($this->usernameExists()) throw new Exception(lang('error_10'));
		if($this->emailExists()) throw new Exception(lang('error_11'));
		
		$createAccount = $this->_createAccount();
		if(!$createAccount) throw new Exception(lang('error_22'));
		
		// delete saved registration data
		$this->_deleteSavedRegistration();
		
		// account preferences
		if($this->_createAccountPreferences) {
			$AccountPreferences = new AccountPreferences();
			$AccountPreferences->setUsername($this->_username);
			$AccountPreferences->createAccountPreferences();
		}
		
		// welcome email
		if($this->_welcomeEmailEnabled) $this->_sendWelcomeEmail();
	}
	
	/**
	 * removeSavedRegistration
	 * manually removed a saved registration
	 */
	public function removeSavedRegistration() {
		if(!check($this->_username)) throw new Exception(lang('error_21'));
		
		// get saved registration data
		$registrationData = $this->_getVerificationAccountData();
		if(!is_array($registrationData)) throw new Exception(lang('error_12'));
		
		// delete saved registration data
		$this->_deleteSavedRegistration();
	}
	
	/**
	 * _saveRegistration
	 * saves the account registration data
	 */
	private function _saveRegistration() {
		if($this->_usernamePendingVerification()) throw new Exception(lang('error_49'));
		if($this->_emailPendingVerification()) throw new Exception(lang('error_115'));
		
		$this->_verificationKey = $this->_generateVerificationKey();
		
		$data = array(
			'account' => $this->_username,
			'password' => $this->_password,
			'email' => $this->_email,
			'key' => $this->_verificationKey
		);
		
		$query = "INSERT INTO "._WE_REGISTER_." (registration_username,registration_password,registration_email,registration_date,registration_key) VALUES (:account, :password, :email, CURRENT_TIMESTAMP, :key)";
		
		$result = $this->we->query($query, $data);
		if(!$result) return;
		
		return true;
	}
	
	/**
	 * _usernamePendingVerification
	 * checks if the username is pending verification
	 */
	private function _usernamePendingVerification() {
		if(!check($this->_username)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_REGISTER_." WHERE registration_username = ?", array($this->_username));
		if(!is_array($result)) return;
		return true;
	}
	
	/**
	 * _emailPendingVerification
	 * checks if the email is pending verification
	 */
	private function _emailPendingVerification() {
		if(!check($this->_email)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_REGISTER_." WHERE registration_email = ?", array($this->_email));
		if(!is_array($result)) return;
		return true;
	}
	
	/**
	 * _buildVerificationLink
	 * builds the verification link url
	 */
	private function _buildVerificationLink() {
		if(!check($this->_username)) return;
		if(!check($this->_verificationKey)) return;
		$verificationLink = __BASE_URL__ . $this->_verificationUrl;
		$verificationLink .= 'user/';
		$verificationLink .= $this->_username;
		$verificationLink .= '/key/';
		$verificationLink .= $this->_verificationKey;
		return $verificationLink;
	}
	
	/**
	 * _sendVerificationEmail
	 * sends a verification email to the player
	 */
	private function _sendVerificationEmail() {
		try {
			$email = new Email();
			$email->setTemplate('WELCOME_EMAIL_VERIFICATION');
			$email->addVariable('{USERNAME}', $this->_username);
			$email->addVariable('{LINK}', $this->_buildVerificationLink());
			$email->addAddress($this->_email);
			$email->send();
			return true;
		} catch (Exception $ex) {
			# TODO logs system
			return;
		}
	}
	
	/**
	 * _getVerificationAccountData
	 * returns saved registration data
	 */
	private function _getVerificationAccountData() {
		if(!check($this->_username)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_REGISTER_." WHERE registration_username = ?", array($this->_username));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _deleteSavedRegistration
	 * deletes saved registration data
	 */
	private function _deleteSavedRegistration() {
		if(!check($this->_username)) return;
		$result = $this->we->query("DELETE FROM "._WE_REGISTER_." WHERE registration_username = ?", array($this->_username));
		if(!$result) return;
		return true;
	}
	
	/**
	 * _sendWelcomeEmail
	 * sends welcome email to the player
	 */
	private function _sendWelcomeEmail() {
		if(!check($this->_username)) return;
		if(!check($this->_email)) return;
		
		try {
			$email = new Email();
			$email->setTemplate('WELCOME_EMAIL');
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
	 * _emailExistsInSavedRegistrations
	 * checks if the email address exists in saved registrations
	 */
	private function _emailExistsInSavedRegistrations() {
		if(!check($this->_email)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_REGISTER_." WHERE registration_email = ?", array($this->_email));
		if(!is_array($result)) return;
		return true;
	}
	
}