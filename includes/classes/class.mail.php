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

final class ItemMail {
	
	private $_accountId;
	private $_senderName = 'DesertCore CMS';
	private $_mailSubject = 'Web Shop Purchase';
	private $_mailMessage = 'Thanks for your purchase.';
	private $_itemId;
	private $_enchantLevel = 0;
	private $_itemCount = 1;
	
	function __construct() {
		
		// database
		$this->db = Handler::loadDB('BDO');
		
	}
	
	public function setAccountId($id) {
		$this->_accountId = $id;
	}
	
	public function setSenderName($name) {
		$this->_senderName = $name;
	}
	
	public function setMailSubject($subject) {
		$this->_mailSubject = $subject;
	}
	
	public function setMailMessage($message) {
		$this->_mailMessage = $message;
	}
	
	public function setItemId($id) {
		$this->_itemId = $id;
	}
	
	public function setEnchantLevel($level) {
		$this->_enchantLevel = $level;
	}
	
	public function setItemCount($count) {
		$this->_itemCount = $count;
	}
	
	public function mailItem() {
		if(!check($this->_accountId, $this->_itemId)) return;
		if(!Validator::UnsignedNumber($this->_accountId)) return;
		if(!Validator::UnsignedNumber($this->_itemId)) return;
		if(!Validator::UnsignedNumber($this->_enchantLevel)) return;
		if(!Validator::UnsignedNumber($this->_itemCount)) return;
		
		$lastMailId = $this->_getLastMailId();
		$currentDate = round(microtime(true)*1000);
		$newMailData = array(
			'_id' => $lastMailId+1,
			'accountId' => (int) $this->_accountId,
			'senderAccountId' => (int) 0,
			'name' => (string) $this->_senderName,
			'mailSubject' => (string) $this->_mailSubject,
			'mailMessage' => (string) $this->_mailMessage,
			'receivedTime' => (int) $currentDate,
			'item' => array(
				'objectId' => (int) -1,
				'itemId' => (int) $this->_itemId,
				'regionId' => (int) 1,
				'enchantLevel' => (int) $this->_enchantLevel,
				'count' => (int) $this->_itemCount,
				'endurance' => (int) 100,
				'maxEndurance' => (int) 100,
				'expirationPeriod' => (int) -1,
				'isVested' => false,
				'price' => (int) 0,
				'alchemyStoneExp' => (int) 0,
				'colorPaletteType' => (int) 0,
				'jewels' => (array) array(),
				'colorPalettes' => (array) array()
			),
			'buyCashItem' => null,
			'type' => (int) 0
		);
		
		try {
			$this->db->gameserver->mails->insertOne($newMailData);
			return true;
		} catch(Exception $ex) {
			if(config('debug')) throw new Exception($ex->getMessage());
			return;
		}
	}
	
	private function _getLastMailId() {
		try {
			$collection = $this->db->gameserver->mails;
			$result = $collection->findOne(
				[],
				[
					'sort' => ['_id' => -1],
				]
			);
			if(!check($result->_id)) return 0;
			return $result->_id;
		} catch(Exception $ex) {
			if(config('debug')) throw new Exception($ex->getMessage());
			return 0;
		}
	}
	
}