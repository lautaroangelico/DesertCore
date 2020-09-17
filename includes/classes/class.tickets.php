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

class Tickets {
	
	private $_configurationFile = 'account.tickets';
	
	protected $_id;
	protected $_subject;
	protected $_username;
	protected $_message;
	
	protected $_subjectMinLen = 1;
	protected $_subjectMaxLen = 100;
	protected $_messageMinLen = 1;
	protected $_messageMaxLen = 1000;
	protected $_messageOrder = 'ASC';
	
	protected $_sendTicketOpenEmailNotification = true;
	protected $_sendTicketReplyEmailNotification = true;
	protected $_sendTicketCloseEmailNotification = true;
	protected $_sendTicketStaffEmailNotification = true;
	
	protected $_isStaff = false;
	
	protected $_viewTicketPath = 'account/tickets/view/id/';
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->we = Handler::loadDB('WebEngine');
		
		// configs
		$cfg = loadModuleConfig($this->_configurationFile);
		if(!is_array($cfg)) throw new Exception(lang('error_66'));
		
		$this->_subjectMinLen = $cfg['subject_min_len'];
		$this->_subjectMaxLen = $cfg['subject_max_len'];
		$this->_messageMinLen = $cfg['message_min_len'];
		$this->_messageMaxLen = $cfg['message_max_len'];
		$this->_messageOrder = $cfg['message_order'];
		$this->_sendTicketOpenEmailNotification = $cfg['open_email_notification'];
		$this->_sendTicketReplyEmailNotification = $cfg['reply_email_notification'];
		$this->_sendTicketCloseEmailNotification = $cfg['close_email_notification'];
		
	}
	
	public function setId($id) {
		if(!Validator::UnsignedNumber($id)) throw new Exception('The provided ticket id is not valid.');
		$this->_id = $id;
	}
	
	public function setSubject($subject) {
		if(!Validator::Length($subject, $this->_subjectMaxLen, $this->_subjectMinLen)) throw new Exception('The ticket subject exceeds the length limits.');
		$this->_subject = $subject;
	}
	
	public function setUsername($username) {
		if(!Validator::AccountUsername($username)) throw new Exception(lang('error_91'));
		$this->_username = $username;
	}
	
	public function setMessage($message) {
		if(!Validator::Length($message, $this->_messageMaxLen, $this->_messageMinLen)) throw new Exception('The ticket message exceeds the length limits.');
		$this->_message = $message;
	}
	
	public function getTicketData() {
		if(!check($this->_id)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._WE_TICKETS_." WHERE `id` = ?", array($this->_id));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getTicketMessages() {
		if(!check($this->_id)) return;
		$result = $this->we->queryFetch("SELECT * FROM "._WE_TICKETMESSAGES_." WHERE `ticket_id` = ? ORDER BY `id` ".$this->_messageOrder."", array($this->_id));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function submitTicket() {
		if(!check($this->_subject)) throw new Exception(lang('error_4'));
		if(!check($this->_message)) throw new Exception(lang('error_4'));
		if(!check($this->_username)) throw new Exception(lang('error_4'));
		
		$submitData = array(
			'subject' => $this->_subject,
			'username' => $this->_username
		);
		$submitTicket = $this->we->query("INSERT INTO "._WE_TICKETS_." (`subject`, `username`, `create_date`, `last_reply_by`, `last_reply_date`) VALUES (:subject, :username, CURRENT_TIMESTAMP, :username, CURRENT_TIMESTAMP)", $submitData);
		if(!$submitTicket) throw new Exception('There was an error submitting your ticket, try again later.');
		
		$ticketId = $this->we->db->lastInsertId();
		if(!check($ticketId)) throw new Exception('There was an error retrieving the ticket id.');
		$this->setId($ticketId);
		
		$submitMessage = $this->submitMessage();
		
		if($this->_sendTicketOpenEmailNotification) {
			try {
				$Account = new Account();
				$Account->setUserid($_SESSION['userid']);
				$accountData = $Account->getAccountData();
				if(!is_array($accountData)) throw new Exception();
				if(!check($accountData['email'])) throw new Exception();
				
				$ticketLink = __BASE_URL__ . $this->_viewTicketPath . $ticketId;
				
				$email = new Email();
				$email->setTemplate('TICKET_OPEN_NOTIFICATION');
				$email->addVariable('{USERNAME}', $this->_username);
				$email->addVariable('{TICKET_ID}', $ticketId);
				$email->addVariable('{TICKET_SUBJECT}', $this->_subject);
				$email->addVariable('{TICKET_LINK}', $ticketLink);
				$email->addAddress($accountData['email']);
				$email->send();
			} catch (Exception $ex) {
				# TODO logs system
			}
		}
		
		if($this->_sendTicketStaffEmailNotification) {
			$this->_sendNewTicketStaffNotificationEmail($ticketId, $this->_subject, $this->_username);
		}
	}
	
	public function submitMessage() {
		if(!check($this->_id)) throw new Exception(lang('error_4'));
		if(!check($this->_message)) throw new Exception(lang('error_4'));
		if(!check($this->_username)) throw new Exception(lang('error_4'));
		
		$submitData = array(
			'id' => $this->_id,
			'message' => $this->_message,
			'username' => $this->_username
		);
		$submitMessage = $this->we->query("INSERT INTO "._WE_TICKETMESSAGES_." (`ticket_id`, `message`, `username`, `create_date`) VALUES (:id, :message, :username, CURRENT_TIMESTAMP)", $submitData);
		if(!$submitMessage) throw new Exception('There was an error submitting your message, try again later.');
		
		$updateTicket = $this->we->query("UPDATE "._WE_TICKETS_." SET `last_reply_by` = ?, `last_reply_date` = CURRENT_TIMESTAMP WHERE `id` = ?", array($this->_username, $this->_id));
		if(!$updateTicket) throw new Exception('There was an error updating the ticket.');
		
		if($this->_isStaff && $this->_sendTicketReplyEmailNotification) {
			try {
				$ticketData = $this->getTicketData();
				if(!is_array($ticketData)) throw new Exception();
				
				$Account = new Account();
				$Account->setUsername($ticketData['username']);
				$accountData = $Account->getAccountData();
				if(!is_array($accountData)) throw new Exception();
				if(!check($accountData['email'])) throw new Exception();
				
				$ticketLink = __BASE_URL__ . $this->_viewTicketPath . $this->_id;
				
				$email = new Email();
				$email->setTemplate('TICKET_REPLY_NOTIFICATION');
				$email->addVariable('{REPLY_MESSAGE}', nl2br($this->_message));
				$email->addVariable('{TICKET_ID}', $this->_id);
				$email->addVariable('{TICKET_SUBJECT}', $ticketData['subject']);
				$email->addVariable('{TICKET_LINK}', $ticketLink);
				$email->addAddress($accountData['email']);
				$email->send();
			} catch (Exception $ex) {
				# TODO logs system
			}
		}
	}
	
	public function closeTicket() {
		if(!check($this->_id)) return;
		$result = $this->we->query("UPDATE "._WE_TICKETS_." SET `closed` = 1 WHERE `id` = ?", array($this->_id));
		if(!$result) return;
		
		if($this->_sendTicketCloseEmailNotification) {
			try {
				$ticketData = $this->getTicketData();
				if(!is_array($ticketData)) throw new Exception();
				
				$Account = new Account();
				$Account->setUsername($ticketData['username']);
				$accountData = $Account->getAccountData();
				if(!is_array($accountData)) throw new Exception();
				if(!check($accountData['email'])) throw new Exception();
				
				$ticketLink = __BASE_URL__ . $this->_viewTicketPath . $this->_id;
				
				$email = new Email();
				$email->setTemplate('TICKET_CLOSE_NOTIFICATION');
				$email->addVariable('{USERNAME}', $ticketData['username']);
				$email->addVariable('{TICKET_ID}', $this->_id);
				$email->addVariable('{TICKET_SUBJECT}', $ticketData['subject']);
				$email->addVariable('{TICKET_LINK}', $ticketLink);
				$email->addAddress($accountData['email']);
				$email->send();
			} catch (Exception $ex) {
				# TODO logs system
			}
		}
		
		return $result;
	}
	
	public function openTicket() {
		if(!check($this->_id)) return;
		$result = $this->we->query("UPDATE "._WE_TICKETS_." SET `closed` = 0 WHERE `id` = ?", array($this->_id));
		if(!$result) return;
		return $result;
	}
	
	public function getAccountTickets() {
		if(!check($this->_username)) return;
		$result = $this->we->queryFetch("SELECT * FROM "._WE_TICKETS_." WHERE `username` = ? ORDER BY `last_reply_date` DESC, `id` DESC", array($this->_username));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function redirectToTicket() {
		if(!check($this->_id)) return;
		redirect($this->_viewTicketPath . $this->_id);
	}
	
	public function getOpenTickets() {
		$result = $this->we->queryFetch("SELECT * FROM "._WE_TICKETS_." WHERE `closed` = ? ORDER BY `last_reply_date` DESC, `id` DESC", array(0));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getClosedTickets() {
		$result = $this->we->queryFetch("SELECT * FROM "._WE_TICKETS_." WHERE `closed` = ? ORDER BY `last_reply_date` DESC, `id` DESC", array(1));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function getAwaitingResponseTickets() {
		$result = $this->we->queryFetch("SELECT * FROM "._WE_TICKETS_." WHERE `username` == `last_reply_by` AND `closed` = ? ORDER BY `last_reply_date` DESC, `id` DESC", array(0));
		if(!is_array($result)) return;
		return $result;
	}
	
	public function setStaffReply() {
		$this->_isStaff = true;
	}
	
	protected function _getAdminsEmailList() {
		$Account = new Account();
		$adminAccounts = $Account->getAccountsByAccessLevel(4);
		if(!is_array($adminAccounts)) return;
		foreach($adminAccounts as $row) {
			if(!check($row['email'])) continue;
			if(!Validator::Email($row['email'])) continue;
			$emails[] = $row['email'];
		}
		if(!is_array($emails)) return;
		return $emails;
	}
	
	protected function _sendNewTicketStaffNotificationEmail($ticketId, $ticketSubject, $username) {
		$staffEmalList = $this->_getAdminsEmailList();
		if(!is_array($staffEmalList)) return;
		try {
			$email = new Email();
			$email->setTemplate('TICKET_NEW_STAFF_NOTIFICATION');
			$email->addVariable('{USERNAME}', $username);
			$email->addVariable('{TICKET_ID}', $ticketId);
			$email->addVariable('{TICKET_SUBJECT}', $ticketSubject);
			foreach($staffEmalList as $staffEmail) {
				$email->addAddress($staffEmail);
			}
			$email->send();
		} catch (Exception $ex) {
			# TODO logs system
		}
	}
}