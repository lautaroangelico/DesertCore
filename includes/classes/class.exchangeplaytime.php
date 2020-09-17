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

final class ExchangePlayTime {
	
	private $_userid;
	private $_username;
	private $_totalPlayTime = 0;
	private $_exchangedHours = 0;
	
	private $_minimumExchangeLimit = 5;
	private $_cashPerHour = 100;
	
	function __construct() {
		
		// offline mode
		if(config('offline_mode')) throw new Exception(lang('offline_mode_error'));
		
		// database object
		$this->db = Handler::loadDB('BDO');
		$this->we = Handler::loadDB('WebEngine');
		
		// configs
		$cfg = loadModuleConfig('account.exchange');
		if(!is_array($cfg)) throw new Exception(lang('error_66',true));
		
		$this->_minimumExchangeLimit = $cfg['min_exchange_limit'];
		$this->_cashPerHour = $cfg['cash_per_hour'];
		
	}
	
	public function setUsername($username) {
		$Account = new Account();
		$Account->setUsername($username);
		$accountInfo = $Account->getAccountData();
		if(!is_array($accountInfo)) throw new Exception(lang('error_12'));
		$accountInfoGameserver = $Account->getGameserverAccountData();
		if(!is_array($accountInfoGameserver)) throw new Exception(lang('error_12'));
		
		$this->_username = $username;
		$this->_userid = $accountInfo['_id'];
		
		if($accountInfoGameserver['playedTime'] >= 1) {
			$this->_totalPlayTime = round($accountInfoGameserver['playedTime']/1000);
		}
		$this->_loadTotalExchangedHours();
	}
	
	public function getTotalPlayTime() {
		if(!check($this->_username)) return 0;
		return $this->_totalPlayTime;
	}
	
	public function getTotalExchangedHours() {
		if(!check($this->_username)) return 0;
		return $this->_exchangedHours;
	}
	
	public function exchange() {
		if(!check($this->_username)) throw new Exception(lang('error_12'));
		if(!check($this->_userid)) throw new Exception(lang('error_12'));
		if($this->_totalPlayTime < 1) throw new Exception(lang('error_306'));
		
		// check play hours
		$playTime = sec_to_hms($this->_totalPlayTime);
		$playHours = $playTime[0];
		
		// check available hours
		$availableHours = $playHours-$this->_exchangedHours;
		if($availableHours < $this->_minimumExchangeLimit) throw new Exception(lang('error_307'));
		
		// calculate reward
		$cashReward = $availableHours*$this->_cashPerHour;
		
		// add exchange log
		if(!$this->_addExchangeLog($availableHours, $cashReward)) throw new Exception(lang('error_308'));
		
		// give cash reward
		$Account = new Account();
		$Account->setUserid($this->_userid);
		if(!$Account->addCash($cashReward)) throw new Exception(lang('error_309'));
		
		// redirect to success message
		redirect('account/exchange/success/1/h/'.$availableHours.'/c/'.$cashReward.'/');
	}
	
	public function getLogs($limit=100) {
		if(check($this->_username)) {
			$result = $this->we->queryFetch("SELECT * FROM "._DC_EXCHANGELOGS_." WHERE `username` = ? ORDER BY `id` DESC", array($this->_username));
		} else {
			$result = $this->we->queryFetch("SELECT * FROM "._DC_EXCHANGELOGS_." ORDER BY `id` DESC LIMIT ?", array($limit));
		}
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _loadTotalExchangedHours() {
		if(!check($this->_username)) return;
		$result = $this->we->queryFetchSingle("SELECT SUM(`exchanged_hours`) as `totalExchangedHours` FROM "._DC_EXCHANGELOGS_." WHERE `username` = ?", array($this->_username));
		if(!is_array($result)) return;
		$this->_exchangedHours = $result['totalExchangedHours'];
	}
	
	private function _getLastExchange() {
		if(!check($this->_username)) return;
		$result = $this->we->queryFetchSingle("SELECT * FROM "._DC_EXCHANGELOGS_." WHERE `username` = ? ORDER BY `id` DESC", array($this->_username));
		if(!is_array($result)) return;
		return $result;
	}
	
	private function _addExchangeLog($exchangedHours, $receivedCash) {
		$result = $this->we->query("INSERT INTO "._DC_EXCHANGELOGS_." (`username`, `exchanged_hours`, `received_cash`, `exchange_datetime`) VALUES (?, ?, ?, CURRENT_TIMESTAMP)", array($this->_username, $exchangedHours, $receivedCash));
		if(!$result) return;
		return true;
	}
	
}