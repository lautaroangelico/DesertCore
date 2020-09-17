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

class Email {
	
	private $_configurationFile = 'email';
	
	private $_active = false;
	private $_smtp = false;
	
	private $_from;
	private $_name;
	private $_templates = array();
	private $_templatesPath = __PATH_EMAILS__;
	
	private $_smtpDebug = 0;
	private $_smtpHost;
	private $_smtpIpv6;
	private $_smtpPort = 587;
	private $_smtpSecure = 'tls';
	private $_smtpAuth = true;
	private $_smtpUser;
	private $_smtpPass;
	
	private $_template;
	private $_message;
	private $_to = array();
	private $_subject;
	private $_variables = array();
	private $_values = array();
	
	private $_templateFileNameMaxLen = 100;
	private $_templateTitleMaxLen = 50;
	
	function __construct() {
		# load configs
		$cfg = loadConfig($this->_configurationFile);
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
		# set configurations
		$this->_active = $cfg['active'];
		$this->_smtp = $cfg['smtp_active'];
		$this->_smtpDebug = $cfg['smtp_debug'];
		$this->_from = $cfg['send_from'];
		$this->_name = $cfg['send_name'];
		$this->_smtpHost = $cfg['smtp_host'];
		$this->_smtpIpv6 = $cfg['smtp_ipv6'];
		$this->_smtpPort = $cfg['smtp_port'];
		$this->_smtpSecure = $cfg['smtp_secure'];
		$this->_smtpAuth = $cfg['smtp_auth'];
		$this->_smtpUser = $cfg['smtp_user'];
		$this->_smtpPass = $cfg['smtp_pass'];
		
		# server name variable
		$this->addVariable("{SERVER_NAME}", config('server_name'));
		
		// additional common variables
		$this->addVariable("{EMAIL_WIDTH}", 600);
		$this->addVariable("{HEADER_IMAGE}", $cfg['email_header_image']);
		$this->addVariable("{HEADER_IMAGE_WIDTH}", 600);
		$this->addVariable("{HEADER_IMAGE_HEIGHT}", 222);
		$this->addVariable("{CURRENT_YEAR}", date("Y"));
		$this->addVariable("{SUBSCRIPTION_LINK}", __BASE_URL__);
		
		# phpmailer instance
		$this->mail = new PHPMailer\PHPMailer\PHPMailer(true);
		
	}
	
	/**
	 * setSubject
	 * 
	 */
	public function setSubject($subject) {
		$this->_subject = $subject;
	}
	
	/**
	 * setFrom
	 * 
	 */
	public function setFrom($email, $name="Unknown") {
		$this->_from = $email;
		$this->_name = $name;
	}
	
	/**
	 * setMessage
	 * 
	 */
	public function setMessage($message) {
		$this->_message = $message;
	}
	
	/**
	 * setTemplate
	 * 
	 */
	public function setTemplate($template) {
		if(!$this->_templateExists($template)) throw new Exception(lang('error_172'));		
		$templateData = $this->getTemplateData($template);
		if(!is_array($templateData)) throw new Exception(lang('error_172'));
		
		$this->_template = $templateData['template'];
		$subject = lang($templateData['title']) == 'ERROR' ? $templateData['title'] : lang($templateData['title']);
		if(!check($subject)) throw new Exception(lang('error_173'));
		
		$this->_subject = str_replace("{SERVER_NAME}", config('server_name'), $subject);
	}
	
	/**
	 * addVariable
	 * 
	 */
	public function addVariable($variable, $value) {
		$this->_variables[] = $variable;
		$this->_values[] = $value;
	}
	
	/**
	 * addAddress
	 * 
	 */
	public function addAddress($email) {
		$email = Filter::RemoveAllSpaces($email);
		if(!Validator::Email($email)) throw new Exception(lang('error_9'));
		$this->_to[] = $email;
	}
	
	/**
	 * send
	 * 
	 */
	public function send() {
		// Return true if email system is not active
		if(!$this->_active) return true;
		
		// Check message or template
		if(!$this->_message) {
			if(!$this->_template) throw new Exception(lang('error_174'));
		}
		
		// Check recipients
		if(!is_array($this->_to)) throw new Exception(lang('error_175'));
		
		// SMTP Enabled
		if($this->_smtp) {
			$this->mail->IsSMTP();
			
			// SMTP Debugging
			$this->mail->SMTPDebug = $this->_smtpDebug;
			
			// SMTP Authentication
			$this->mail->SMTPAuth = $this->_smtpAuth;
			
			// SMTP Host
			if($this->_smtpIpv6) {
				$this->mail->Host = $this->_smtpHost;
			} else {
				// network does not support SMTP over IPv6
				$this->mail->Host = gethostbyname($this->_smtpHost);
			}
			
			// SMTP Port
			$this->mail->Port = $this->_smtpPort;
			
			// SMTP Secure
			$this->mail->SMTPSecure = $this->_smtpSecure;
			
			// SMTP Authentication
			if($this->_smtpAuth) {
				$this->mail->Username = $this->_smtpUser;
				$this->mail->Password = $this->_smtpPass;
			}
		}
		
		// Set From
		$this->mail->SetFrom($this->_from, $this->_name);
		
		// Addresses
		foreach($this->_to as $address) {
			$this->mail->AddAddress($address);
		}
		
		// Email Subject
		if(!$this->_subject) throw new Exception(lang('error_176'));
		$this->mail->Subject = $this->_subject;
		
		// Email Body
		if(!$this->_message) {
			$this->mail->MsgHTML($this->_prepareTemplate());
		} else {
			$this->mail->MsgHTML($this->_message);
		}
		
		// Alt Body
		$this->mail->AltBody = '...';
		
		// Send
		try {
			$this->mail->Send();
		} catch(Exception $ex) {
			if(config('debug')) {
				throw new Exception($ex->getMessage());
			} else {
				throw new Exception(lang('error_177'));
			}
		}
		
		return true;
	}
	
	/**
	 * loadTemplateList
	 * 
	 */
	public function loadTemplateList() {
		$result = $this->we->queryFetch("SELECT * FROM `"._WE_EMAILTEMPLATES_."`");
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * addTemplate
	 * 
	 */
	public function addTemplate($template, $title) {
		if(!Validator::Length($template, $this->_templateFileNameMaxLen, 1)) throw new Exception(lang('error_237'));
		if(!Validator::Length($title, $this->_templateTitleMaxLen, 1)) throw new Exception(lang('error_238'));
		if($this->_templateExists($template)) throw new Exception(lang('error_59'));
		
		$this->_template = $template;
		if(!$this->_templateFileExists()) throw new Exception(lang('error_172'));
		$result = $this->we->query("INSERT INTO `"._WE_EMAILTEMPLATES_."` (`template`, `title`) VALUES (?, ?)", array($template, $title));
		if(!$result) throw new Exception(lang('success_7'));
	}
	
	/**
	 * deleteTemplate
	 * 
	 */
	public function deleteTemplate() {
		if(!check($this->_template)) return;
		$result = $this->we->query("DELETE FROM `"._WE_EMAILTEMPLATES_."` WHERE `template` = ?", array($this->_template));
		if(!$result) return;
		return true;
	}
	
	/**
	 * getTemplateData
	 * 
	 */
	public function getTemplateData($template) {
		$result = $this->we->queryFetchSingle("SELECT * FROM `"._WE_EMAILTEMPLATES_."` WHERE `template` = ?", array($template));
		if(!is_array($result)) return;
		return $result;
	}
	
	/**
	 * _templateExists
	 * 
	 */
	private function _templateExists($template) {
		$result = $this->getTemplateData($template);
		if(!is_array($result)) return;
		return true;
	}
	
	/**
	 * _loadTemplate
	 * 
	 */
	private function _loadTemplate() {
		if(!check($this->_template)) throw new Exception(lang('error_174'));
		if(!$this->_templateFileExists()) throw new Exception(lang('error_172'));
		return file_get_contents($this->_templatesPath . $this->_template . '.txt');
	}
	
	/**
	 * _prepareTemplate
	 * 
	 */
	private function _prepareTemplate() {
		return str_replace($this->_variables, $this->_values, $this->_loadTemplate());
	}
	
	/**
	 * _templateFileExists
	 * 
	 */
	private function _templateFileExists() {
		if(!check($this->_template)) return;
		if(!file_exists($this->_templatesPath . $this->_template . '.txt')) return;
		return true;
	}
	
}